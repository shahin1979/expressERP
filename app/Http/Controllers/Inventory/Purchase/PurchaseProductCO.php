<?php

namespace App\Http\Controllers\Inventory\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Company\Relationship;
use App\Models\Company\TransCode;
use App\Models\Inventory\Movement\Purchase;
use App\Models\Inventory\Movement\Requisition;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ItemTax;
use App\Models\Inventory\Product\ProductMO;
use App\Models\Inventory\Product\ProductUniqueId;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseProductCO extends Controller
{
    use TransactionsTrait;

    public function index()
    {

        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>53005,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        $suppliers = Relationship::query()->where('company_id',$this->company_id)
            ->whereIn('relation_type',['LS','SP'])
            ->orderBy('name')
            ->pluck('name','id')
            ->prepend('Cash Purchase','1');



        $taxes = ItemTax::query()->where('company_id',$this->company_id)
            ->orderBy('name')
            ->pluck('name','id');

        $temp_id = rand_string(10);

        return view('inventory.purchase.purchase-product-index',compact('suppliers','taxes','temp_id'));
    }

    public function autocomplete(Request $request)
    {
        $term = $request['term'];

        $items = ProductMO::query()->select('id as item_id', 'name','unit_price','unit_name','tax_id')
            ->where('company_id',$this->company_id)
            ->where('name', 'LIKE', '%'.$term.'%')->get();

        return response()->json($items);
    }

    public function uniqueID(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $unique = [];

            if($request['product'])
            {
                foreach ($request['product'] as $row)
                {
                    if(Str::length($row['unique_code']) > 0)
                    {
//                        ProductUniqueId::query()->where('company_id',$this->company_id)
//                            ->where('unique_id',$row['unique_code'])
//                            ->where('data_validity',false)->delete();

                        $unique['company_id'] = $this->company_id;
                        $unique['temp_id'] = $id;
                        $unique['product_id'] = $request['unique_prod_id'];
                        $unique['unique_id'] = $row['unique_code'];
                        $unique['stock_status'] = false;
                        $unique['user_id'] = $this->user_id;

//                        ProductUniqueId::query()->create($unique);

                        ProductUniqueId::query()->updateOrCreate(
                            ['company_id'=>$this->company_id,'unique_id'=>$row['unique_code'],'data_validity'=>false],
                            [
                                'temp_id'=>$id,
                                'stock_status'=>false,
                                'product_id'=>$request['unique_prod_id'],
                                'user_id'=>$this->user_id
                            ]
                        );
                    }
                }
            }

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Unique Id Saved'],200);
    }

    public function store(Request $request)
    {

        DB::beginTransaction();

        try{
            $fiscal_year = $this->get_fiscal_year($request['invoice_date'],$this->company_id);

            $tr_code =  TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','PR')
                ->where('fiscal_year',$fiscal_year)
                ->lockForUpdate()->first();

            $pur_no = $tr_code->last_trans_id;

            $request['company_id'] = $this->company_id;
            $request['ref_no'] = $pur_no;
            $request['invoice_date'] = Carbon::createFromFormat('d-m-Y', $request['invoice_date'])->format('Y-m-d');
            $request['due_amt'] = $request['invoice_amt'] - $request['paid_amt'] - $request['discount'];
            $request['discount_amt'] = $request['discount'];
            $request['po_date'] = $request['invoice_date'];
            $request['purchase_type'] = $request['relationship_id'] == 0 ?  'CP' : 'LP';
            $request['status'] = 'CR';
            $request['user_id'] = $this->user_id;

            $inserted = Purchase::query()->create($request->all()); //Insert Data Into Requisition Table

            ProductUniqueId::query()->where('company_id',$this->company_id)
                ->where('user_id',$this->user_id)
                ->where('temp_id',$request['temp_ref_no'])
                ->update(['purchase_ref_id'=>$inserted->id,'temp_id'=>null,
                    'stock_status'=>false,
                    'data_validity'=>true]);

            if ($request['item']) {
                foreach ($request['item'] as $item) {
                    if ($item['quantity'] > 0)
                    {
                        $purchase_item['company_id'] = $this->company_id;
                        $purchase_item['ref_no'] = $pur_no;
                        $purchase_item['ref_id'] = $inserted->id;
                        $purchase_item['ref_type'] = 'P'; //Purchase
                        $purchase_item['relationship_id'] = $request['supplier_id'];
                        $purchase_item['tr_date']= $request['invoice_date'];
                        $purchase_item['product_id'] = $item['item_id'];
                        $purchase_item['quantity'] = $item['quantity'];
                        $purchase_item['purchased'] = $item['quantity'];
                        $purchase_item['unit_price'] = $item['price'];
                        $purchase_item['tax_id'] = $item['tax'];
                        $purchase_item['tax_total'] = $item['tax_amt'];
                        $purchase_item['total_price'] = $item['quantity']*$item['price'] + $item['tax_amt'];

                        TransProduct::query()->create($purchase_item);
                    }
                }
            }

            TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','PR')
                ->where('fiscal_year',$fiscal_year)
                ->increment('last_trans_id');

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error','Purchase Failed To Save '.$error);
        }

        DB::commit();

        return redirect()->action('Inventory\Purchase\PurchaseProductCO@index')->with('success','Purchase Data Saved For Authorisation');
    }
}
