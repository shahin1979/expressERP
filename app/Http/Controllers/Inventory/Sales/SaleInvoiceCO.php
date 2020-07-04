<?php

namespace App\Http\Controllers\Inventory\Sales;

use App\Http\Controllers\Company\CompanyPropertiesCO;
use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Company\CompanyProperty;
use App\Models\Company\Relationship;
use App\Models\Company\TransCode;
use App\Models\Inventory\Movement\Delivery;
use App\Models\Inventory\Movement\ProductHistory;
use App\Models\Inventory\Movement\Requisition;
use App\Models\Inventory\Movement\Sale;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ItemTax;
use App\Models\Inventory\Product\ProductMO;
use App\Models\Inventory\Product\ProductUniqueId;
use App\Traits\CompanyTrait;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class SaleInvoiceCO extends Controller
{
    use TransactionsTrait, CompanyTrait;

    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>55008,'user_id'=>$this->user_id
            ],['updated_at'=>Carbon::now()
        ]);

        $basic = CompanyProperty::query()->where('company_id',$this->company_id)->first();

        $customers = Relationship::query()->where('company_id',$this->company_id)
            ->whereIn('relation_type',['CS','SP'])
            ->orderBy('name')
            ->pluck('name','id')
            ->prepend('Cash Sale','1',);

        $taxes = ItemTax::query()->where('company_id',$this->company_id)->pluck('name','id');
        $temp_id = rand_string(10);

        return view('inventory.sales.create-sales-invoice-index',compact('customers','taxes','basic','temp_id'));
    }

    public function autocomplete(Request $request)
    {
        $term = $request['term'];

        $items = ProductMO::query()->select('id as item_id', 'name','unit_price','unit_name','tax_id')
            ->where('company_id',$this->company_id)
//            ->where('category_id',3)
            ->where('name', 'LIKE', '%'.$term.'%')->get();

        return response()->json($items);
    }


    public function uniqueID(Request $request, $id)
    {

        $request->session()->flash('invoice_items');

        $sales_product= collect();

        try {

            if($request['product'])
            {
                foreach ($request['product'] as $row)
                {
                    if(Str::length($row['unique_code']) > 0)
                    {
                        $newline = [];

                        if(ProductUniqueId::query()->where('company_id',$this->company_id)
                            ->where('unique_id',$row['unique_code'])
                            ->where('product_id',$request['unique_prod_id'])
                            ->where('stock_status',true)
                            ->where('data_validity',true)->exists())
                        {

                            $newline['company_id'] = $this->company_id;
                            $newline['temp_id'] = $id;
                            $newline['product_id'] = $request['unique_prod_id'];
                            $newline['unique_id'] = $row['unique_code'];
                            $sales_product->push($newline);

                        }
                        else{
                            return response()->json(['error' => $row['unique_code'].' Not Available'], 404);
                        }
                    }
                }
            }

            Session::put('invoice_items',$sales_product);

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        return response()->json(['success'=>'Unique Id Saved'],200);
    }


    public function totalItem(Request $request)
    {

        $input_items = $request['item'];
        $json = new \stdClass;

        $sub_total = 0;
        $tax_total = 0;
        $item_tax_total = 0;

        $items = array();
        $taxes = array();

        if ($input_items) {
            foreach ($input_items as $key => $item) {
                $item_tax_total= 0;
                $item_sub_total = ($item['price'] * $item['quantity']); //Money function gets last two digit as decimal


                if (!empty($item['tax'])) {

                    $tax = ItemTax::query()->where('id', $item['tax'])->first();
                    if($tax->calculating_mode == 'P')
                    {
                        $item_tax_total = (($item['price'] * $item['quantity']) / 100) * $tax->rate;
                    }

                    if($tax->calculating_mode == 'F')
                    {
                        $item_tax_total = $item['quantity']*$tax->rate;
                    }

                }
//
                $sub_total += $item_sub_total;
                $tax_total += $item_tax_total;

                $total = $item_sub_total + $item_tax_total;

                $items[$key] = $total;
                $taxes[$key] = $item_tax_total;

            }
        }

        $json->items = $items;
        $json->taxes = $taxes;
        $json->sub_total = $sub_total;
        $json->tax_total = $tax_total;

        $grand_total = $sub_total + $tax_total;

        $json->grand_total = $grand_total;

        return response()->json($json);

    }

    public function store(Request $request)
    {
        dd($request->session()->get('invoice_items'));

        DB::beginTransaction();
        try{

            $fiscal_year = $this->get_fiscal_year($request['invoice_date'],$this->company_id);
            $taxes = ItemTax::query()->where('company_id',$this->company_id)->where('status',true)->get();
            $products = ProductMO::query()->where('company_id',$this->company_id)->where('status',true)->get();

            $tr_code =  TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','SL')
                ->where('fiscal_year',$fiscal_year)
                ->lockForUpdate()->first();

            $invoice_no = $tr_code->last_trans_id;

            $request['company_id'] = $this->company_id;
            $request['invoice_no'] = $invoice_no;
            $request['invoice_date'] = Carbon::createFromFormat('d-m-Y',$request['invoice_date'])->format('Y-m-d');
            $request['due_amt'] = $request['invoice_amt'] - $request['discount_amt'] - $request['paid_amt'];
            $request['invoice_type'] = $request['customer_id'] == 1 ? 'CA' : 'CR';
//            $request['customer_id']
//            $request['paid_amt'] =
//            $request['discount_amt'] =
            $request['user_id'] = $this->user_id;
            $request['status'] = 'CR'; //Created

            $inserted = Sale::query()->create($request->all()); //Insert Data Into Sales Table

            if ($request['item']) {
                foreach ($request['item'] as $item) {
                    if ($item['quantity'] > 0)
                    {
                        $sales_item['company_id'] = $this->company_id;
                        $sales_item['ref_no'] = $invoice_no;
                        $sales_item['ref_id'] = $inserted->id;
                        $sales_item['ref_type'] = 'S'; //Sales
                        $sales_item['relationship_id'] = $request['customer_id'];
                        $sales_item['tr_date']= $request['invoice_date'];
                        $sales_item['product_id'] = $item['item_id'];
                        $sales_item['name'] = $products->where('id',$item['item_id'])->first()->name;
                        $sales_item['quantity'] = $item['quantity'];
                        $sales_item['sold'] = $item['quantity'];
                        $sales_item['unit_price'] = $item['price'];
                        $sales_item['tax_id'] = $item['tax'];
                        $sales_item['tax_total'] = $item['tax_amt'];
                        $sales_item['total_price'] = $item['quantity']*$item['price'] + $item['tax_amt'];

                        TransProduct::query()->create($sales_item);
                        ProductMO::query()->where('id',$item['item_id'])->increment('committed',$item['quantity']);
                    }
                }
            }

            TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','SL')
                ->where('fiscal_year',$fiscal_year)
                ->increment('last_trans_id');


        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error','Invoice Failed To Save '.$error);
        }

        DB::commit();

        return redirect()->action('Inventory\Sales\SaleInvoiceCO@index')->with('success','Invoice Data Saved For Approval');

    }


}
