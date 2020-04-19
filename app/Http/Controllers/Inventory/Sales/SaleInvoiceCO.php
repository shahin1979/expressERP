<?php

namespace App\Http\Controllers\Inventory\Sales;

use App\Http\Controllers\Company\CompanyPropertiesCO;
use App\Http\Controllers\Controller;
use App\Models\Company\CompanyProperty;
use App\Models\Company\Relationship;
use App\Models\Company\TransCode;
use App\Models\Inventory\Movement\Requisition;
use App\Models\Inventory\Movement\Sale;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ItemTax;
use App\Models\Inventory\Product\ProductMO;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SaleInvoiceCO extends Controller
{
    use TransactionsTrait;

    public function index()
    {
        $basic = CompanyProperty::query()->where('company_id',$this->company_id)->first();

        $customers = Relationship::query()->where('company_id',$this->company_id)
            ->where('relation_type','CS')
            ->orderBy('name')
            ->pluck('name','id');

        $taxes = ItemTax::query()->where('company_id',$this->company_id)->pluck('name','id');

        return view('inventory.sales.create-sales-invoice-index',compact('customers','taxes','basic'));
    }

    public function autocomplete(Request $request)
    {
        $term = $request['term'];

        $items = ProductMO::query()->select('id as item_id', 'name','unit_price','unit_name')
            ->where('company_id',$this->company_id)
            ->where('category_id',3)
            ->where('name', 'LIKE', '%'.$term.'%')->get();

        return response()->json($items);
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

    public function editIndex()
    {
        return view('inventory.sales.update-sales-invoice-index');
    }

    public function InvoiceData()
    {
        $query = Sale::query()->where('company_id',$this->company_id)
            ->where('status','CR')->with('items')->with('user')->with('customer')->select('sales.*');


        return Datatables::eloquent($query)
            ->addColumn('product', function (Sale $sales) {
                return $sales->items->map(function($items) {
                    return $items->item->name;
                })->implode('<br>');
            })

            ->addColumn('quantity', function (Sale $sales) {
                return $sales->items->map(function($items) {
                    return $items->quantity;
                })->implode('<br>');
            })

            ->addColumn('unit_price', function (Sale $sales) {
                return $sales->items->map(function($items) {
                    return $items->unit_price;
                })->implode('<br>');
            })
            ->addColumn('action', function ($sales) {

                $type = $sales->req_type == 'CS' ? 'CASH' : 'CREDIT';

                return '
                    <button  data-remote="edit/' . $sales->id . '"
                        data-invoice="' . $sales->invoice_no . '"
                        data-date="' . $sales->invoice_date . '"
                        data-customer="' . $sales->customer->name . '"
                        data-amount="' . number_format($sales->invoice_amt,2) . '"
                        data-type="' . $type . '"
                        id="edit-invoice" type="button" class="btn btn-edit btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</button>
                    <button data-remote="delete/' . $sales->id . '" type="button" class="btn btn-xs btn-delete btn-danger pull-right"  ><i class="glyphicon glyphicon-remove"></i>Delete</button>
                    ';
            })
            ->rawColumns(['product','quantity','unit_price','action'])
            ->make(true);
    }

    public function edit($id)
    {
        $sales = TransProduct::query()->where('company_id',$this->company_id)
            ->where('ref_id',$id)->where('ref_type','S')
            ->with('item')->with('invoice')->get();

        return response()->json($sales);
    }

    public function update(Request $request)
    {

//        dd($request->all());
        $invoice = Sale::query()->where('company_id',$this->company_id)
            ->where('invoice_no',$request['invoice'])->first();

        $items = TransProduct::query()->where('company_id',$this->company_id)
            ->where('ref_type','S')
            ->where('ref_no',$request['invoice'])->get();

        $invoice_amt = 0;

        DB::beginTransaction();

        try{

            foreach ($request['item'] as $item) {

                $tax = empty($item['tax_id']) ? 0 : $this->tax_amount($item['tax_id'],$item['price'],$item['quantity']);
                $total_price = $tax + $item['price'] * $item['quantity'];
                $invoice_amt = $invoice_amt + $total_price;

                $trans = TransProduct::query()->find($item['id']);
                $trans->quantity = $item['quantity'];
                $trans->total_price = $total_price;
                $trans->tax_total = $tax;

                $trans->save();

//                TransProduct::query()->where('id',$item['id'])
//                    ->update(['quantity'=>$item['quantity'],
//                        'total_price'=>$total_price,
//                        'tax_total'=>$tax]);
            }

            $invoice->invoice_amt = $invoice_amt;
            $invoice->due_amt = $invoice_amt - $invoice->paid_amt - $invoice->discount_amt;
            $invoice->save();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Invoice Item Quantity Updated'],200);
    }
}
