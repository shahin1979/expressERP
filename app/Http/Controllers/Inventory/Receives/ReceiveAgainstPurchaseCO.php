<?php

namespace App\Http\Controllers\Inventory\Receives;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Company\TransCode;
use App\Models\Inventory\Movement\ProductHistory;
use App\Models\Inventory\Movement\Purchase;
use App\Models\Inventory\Movement\Receive;
use App\Models\Inventory\Movement\ReturnItem;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ProductMO;
use App\Models\Inventory\Product\ProductUniqueId;
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
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>54002,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        return view('inventory.receives.receive-purchase-index');
    }

    public function getData()
    {
        $query = Purchase::query()->where('company_id',$this->company_id)
            ->where('status','AP')
            ->with(['items'=>function($q){
                $q->where('company_id',$this->company_id);
            }])
            ->with('user')->select('purchases.*');


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
                    <div class="btn-unit btn-group-sm" role="group" aria-label="Action Button">
                    <button  data-remote="view/' . $query->id . '"
                        data-order="' . $query->ref_no . '"
                        data-date="' . $query->po_date . '"
                        data-amount="' . number_format($query->invoice_amt,2) . '"
                        id="receive-invoice" type="button" class="btn btn-receive btn-sm btn-primary"><i>Receive</i></button>
                    <button data-remote="return/' . $query->id . '" type="button" class="btn btn-sm btn-return btn-danger pull-right"  ><i>Return</i></button>
                    </div>
                    ';
            })
            ->rawColumns(['product','quantity','unit_price','action','supplier'])
            ->make(true);
    }

    public function view($id)
    {
        $products = TransProduct::query()->where('company_id',$this->company_id)
            ->where('ref_id',$id)->where('ref_type','P')
            ->with('item')->with('purchase')->with('supplier')->get();

        $uniques = ProductUniqueId::query()->where('company_id',$this->company_id)
            ->where('purchase_ref_id',$id)->with('item')
            ->orderBy('product_id')
            ->get();

        $response = [
            'products' => $products,
            'uniques' => $uniques,
        ];

//        dd($response);

        return response()->json($response);
    }
    public function store(Request $request)
    {

        DB::beginTransaction();
        try{

            $fiscal_year = $this->get_fiscal_data_from_current_date($this->company_id);
            $products = ProductMO::query()->where('company_id',$this->company_id)->where('status',true)->get();

            $tr_code =  TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','IR')
                ->where('fiscal_year',$fiscal_year->fiscal_year)
                ->lockForUpdate()->first();

            $receive_no = $tr_code->last_trans_id;

            $request['company_id'] = $this->company_id;
            $request['challan_no'] = $receive_no;
            $request['ref_no'] = $request['purchase_order'];
            $request['receive_date'] = Carbon::now()->format('Y-m-d');
            $request['receive_type'] = 'LP';
            $request['user_id'] = $this->user_id;
            $request['status'] = 'CR'; //Created

            $inserted = Receive::query()->create($request->all()); //Insert Data Into Sales Table
            $new_receive_id = $inserted->id;

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

                        $move['company_id'] = $this->company_id;
                        $move['ref_no'] = $receive_no;
                        $move['ref_id'] = $inserted->id;
                        $move['ref_type'] = 'C'; //Receive
                        $move['relationship_id'] = $data->relationship_id;
                        $move['tr_date']= $request['receive_date'];
                        $move['product_id'] = $data->product_id;
                        $move['name'] = $products->where('id',$data->product_id)->first()->name;
                        $move['quantity'] = $item['receive'];
                        $move['received'] = $item['receive'];
                        $move['unit_price'] = $data->unit_price;
                        $move['total_price'] = $data->unit_price * $item['receive'];

                        TransProduct::query()->create($move);
                        TransProduct::query()->where('id',$data->id)->update(['received'=>$item['receive']]);
                        ProductMO::query()->where('id',$data->product_id)->increment('received_qty',$item['receive']);
                    }
                }
            }



            TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','IR')
                ->where('fiscal_year',$fiscal_year->fiscal_year)
                ->increment('last_trans_id');

            Purchase::query()->where('company_id',$this->company_id)
                ->where('ref_no',$request['purchase_order'])
                ->update(['status'=>'RC']);


            if($request['unique'])
            {
                foreach ($request['unique'] as $uid)
                {
                    if(isset($uid['receive']))
                    {
                        ProductUniqueId::query()->where('id',$uid['receive'])
                            ->update(['receive_ref_id'=>$new_receive_id]);
                    }
                }
            }

        // End of Receive

            /// Return

            if ($request['is_return'] > 0)
            {
                $tr_code =  TransCode::query()->where('company_id',$this->company_id)
                    ->where('trans_code','RT')
                    ->where('fiscal_year',$fiscal_year->fiscal_year)
                    ->lockForUpdate()->first();

                $receive_no = $tr_code->last_trans_id;

                $request['company_id'] = $this->company_id;
                $request['challan_no'] = $receive_no;
                $request['ref_no'] = $request['purchase_order'];
                $request['return_date'] = Carbon::now()->format('Y-m-d');
                $request['return_type'] = 'LP';
                $request['user_id'] = $this->user_id;
                $request['status'] = 'CR'; //Created

                $inserted = ReturnItem::query()->create($request->all()); //Insert Data Into Sales Table
                $new_return_id = $inserted->id;

                if ($request['item']) {

                    foreach ($request['item'] as $item) {
                        if ($item['return'] > 0)
                        {
                            $data = TransProduct::query()->where('id',$item['id'])->first();

                            if($data->quantity != $item['receive'] + $item['return'])
                            {
                                DB::rollBack();
                                $error = 'Receive + Return Quantity Mus Be Equal To Purchase Quantity';
                                return response()->json(['error' => $error], 404);
                            }

                            $return['company_id'] = $this->company_id;
                            $return['ref_no'] = $receive_no;
                            $return['ref_id'] = $inserted->id;
                            $return['ref_type'] = 'T'; //Return
                            $return['relationship_id'] = $data->relationship_id;
                            $return['tr_date']= $request['receive_date'];
                            $return['product_id'] = $data->product_id;
                            $return['name'] = $products->where('id',$data->product_id)->first()->name;
                            $return['quantity'] = $item['return'];
                            $return['returned'] = $item['return'];
                            $return['unit_price'] = $data->unit_price;
                            $return['total_price'] = $data->unit_price*$item['return'];

                            TransProduct::query()->create($return);
                            TransProduct::query()->where('id',$data->id)->update(['returned'=>$item['return']]);
                        }
                    }
                }

                if($request['unique'])
                {
                    foreach ($request['unique'] as $uid)
                    {
                        if(isset($uid['return']))
                        {
                            ProductUniqueId::query()->where('id',$uid['id'])
                                ->update(['return_ref_id'=>$new_return_id,'stock_status'=>false]);
                        }
                    }
                }

                TransCode::query()->where('company_id',$this->company_id)
                    ->where('trans_code','RT')
                    ->where('fiscal_year',$fiscal_year->fiscal_year)
                    ->increment('last_trans_id');
            } // End of Return





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
