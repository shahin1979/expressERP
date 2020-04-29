<?php

namespace App\Http\Controllers\Inventory\Receives;

use App\Http\Controllers\Controller;
use App\Models\Company\TransCode;
use App\Models\Inventory\Movement\ProductHistory;
use App\Models\Inventory\Movement\Purchase;
use App\Models\Inventory\Movement\Receive;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ProductMO;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ReceiveAgainstPurchaseCO extends Controller
{
    use TransactionsTrait;

    public function index()
    {
        return view('inventory.receives.receive-purchase-index');
    }

    public function getData()
    {
        $query = Purchase::query()->where('company_id',$this->company_id)
            ->where('status','PR')->with('items')->with('user')->select('purchases.*');


        return DataTables::eloquent($query)
            ->addColumn('product', function ($query) {
                return $query->items->map(function($items) {
                    return $items->item->name;
                })->implode('<br>');
            })

            ->addColumn('supplier', function ($query) {
                return $query->items->map(function($items) {
                    return $items->supplier->name;
                })->implode('<br>');
            })

            ->addColumn('quantity', function ($query) {
                return $query->items->map(function($items) {
                    return $items->quantity;
                })->implode('<br>');
            })

            ->addColumn('unit_price', function ($query) {
                return $query->items->map(function($items) {
                    return $items->unit_price;
                })->implode('<br>');
            })
            ->addColumn('action', function ($query) {


                return '
                    <button  data-remote="view/' . $query->id . '"
                        data-order="' . $query->ref_no . '"
                        data-date="' . $query->po_date . '"
                        data-amount="' . number_format($query->invoice_amt,2) . '"
                        id="receive-invoice" type="button" class="btn btn-receive btn-xs btn-primary"><i class="fa fa-edit">Receive</i></button>
                    <button data-remote="return/' . $query->id . '" type="button" class="btn btn-xs btn-return btn-danger pull-right"  ><i class="fa fa-remove">Return</i></button>
                    ';
            })
            ->rawColumns(['product','quantity','unit_price','action','supplier'])
            ->make(true);
    }

    public function view($id)
    {
        $data = TransProduct::query()->where('company_id',$this->company_id)
            ->where('ref_id',$id)->where('ref_type','P')
            ->with('item')->with('purchase')->with('supplier')->get();

        return response()->json($data);
    }
    public function store(Request $request)
    {


        DB::beginTransaction();
        try{

            $fiscal_year = $this->get_fiscal_data_from_current_date($this->company_id);
//            $taxes = ItemTax::query()->where('company_id',$this->company_id)->where('status',true)->get();
            $products = ProductMO::query()->where('company_id',$this->company_id)->where('status',true)->get();

            $tr_code =  TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','RC')
                ->where('fiscal_year',$fiscal_year->fiscal_year)
                ->lockForUpdate()->first();

//            dd($fiscal_year);

            $receive_no = $tr_code->last_trans_id;

            $request['company_id'] = $this->company_id;
            $request['challan_no'] = $receive_no;
            $request['ref_no'] = $request['purchase_order'];
            $request['receive_date'] = Carbon::now()->format('Y-m-d');
            $request['receive_type'] = 'LP';
            $request['user_id'] = $this->user_id;
            $request['status'] = 'CR'; //Created

            $inserted = Receive::query()->create($request->all()); //Insert Data Into Sales Table

            if ($request['item']) {

                foreach ($request['item'] as $item) {
                    if ($item['receive'] > 0)
                    {
                        $data = TransProduct::query()->where('id',$item['id'])->first();

                        if($data->quantity != $item['receive'] + $item['return'])
                        {
                            DB::rollBack();
                            $error = 'Receive + Return Quantity Mus Be Equal To Purchase Quantity';
                            return response()->json(['error' => $error], 404);
                        }

                        $history['company_id'] = $this->company_id;
                        $history['ref_no'] = $receive_no;
                        $history['ref_id'] = $inserted->id;
                        $history['ref_type'] = 'R'; //Sales
                        $history['relationship_id'] = $data->relationship_id;
                        $history['tr_date']= $request['receive_date'];
                        $history['product_id'] = $data->product_id;
                        $history['name'] = $products->where('id',$data->product_id)->first()->name;
                        $history['quantity_in'] = $item['receive'];
                        $history['received'] = $item['receive'];
                        $history['unit_price'] = $data->unit_price;
                        $history['total_price'] = $data->total_price;

                        ProductHistory::query()->create($history);
                        ProductMO::query()->where('id',$data->product_id)->increment('received_qty',$item['receive']);
//                        ProductMO::query()->where('id',$data->product_id)->update([''])
                    }
                }
            }

            TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','RC')
                ->where('fiscal_year',$fiscal_year->fiscal_year)
                ->increment('last_trans_id');


        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error','Invoice Failed To Save '.$error);
        }

        DB::commit();

        return response()->json(['success'=>'Item Received for Approval'],200);


    }
}
