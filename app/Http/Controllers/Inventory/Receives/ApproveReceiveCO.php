<?php

namespace App\Http\Controllers\Inventory\Receives;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Movement\Delivery;
use App\Models\Inventory\Movement\ProductHistory;
use App\Models\Inventory\Movement\Purchase;
use App\Models\Inventory\Movement\Receive;
use App\Models\Inventory\Movement\ReturnItem;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ProductMO;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ApproveReceiveCO extends Controller
{
    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>54010,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

//        $id = 2;
//
//        $receives = TransProduct::query()->where('company_id',$this->company_id)
//            ->where('ref_id',$id)->where('ref_type','C')
//            ->with('supplier')
//            ->with('item')->with('receive') ->get();
//
//        foreach ($receives as $row)
//        {
//            foreach ($row->receive->returninfo->items as $item)
//            {
//                dd($item->product_id);
//            }
//
//        }
//
//        dd('here');
//        dd($receives);



        return view('inventory.receives.approve-receive-index');
    }

    public function getRData()
    {
        $query = Receive::query()->where('company_id',$this->company_id)
            ->where('status','CR')
            ->with(['items'=>function($q){
               $q->where('company_id',$this->company_id);
            }])
            ->with('user')->select('receives.*');


        return Datatables::eloquent($query)

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

            ->addColumn('action', function ($query) {

                $return = ReturnItem::query()->where('company_id',$this->company_id)->where('ref_no',$query->ref_no)->first();
                $type = $query->receive_type == 'LP' ? 'Local Purchase' : ($query->receive_type == 'PR' ? 'Production' : 'Import ');
                $rt_type = $return->return_type == 'LP' ? 'Purchase Return' : ($return->return_typee == 'PR' ? 'Return To Production' : 'Return Imported Items');

                return '
                    <button  data-remote="viewItems/' . $query->id . '"

                        data-challan="' . $query->challan_no . '"
                        data-date="' . $query->receive_date . '"
                        data-type="' . $type . '"
                        data-ref_no="' . $query->ref_no . '"

                        data-rt_challan="' . $return->challan_no . '"
                        data-rt_date="' . $return->return_date . '"
                        data-rt_type="' . $rt_type . '"
                        data-rt_ref="' . $return->ref_no . '"

                        id="details-receive" type="button" class="btn btn-receive-details btn-xs btn-primary">Details</button>
                    ';

            })
            ->rawColumns(['product','supplier','quantity','action'])
            ->make(true);
    }

    public function ajaxData($id)
    {
//        $receives = TransProduct::query()->where('company_id',$this->company_id)
//            ->where('ref_id',$id)->where('ref_type','C')
//            ->with('supplier')
//            ->with('item')->with('receive.returninfo.items') ->get();


        $receives = Receive::query()->where('company_id',$this->company_id)
            ->where('id',$id)
            ->with(['items.item'=>function($q){
                $q->where('company_id',$this->company_id);
            }])
            ->with(['items.supplier'=>function($q){
                $q->where('company_id',$this->company_id);
            }])
            ->with('returninfo.items.item')
            ->with('returninfo.items.supplier')
            ->get();

//        dd($receives);

        return response()->json($receives);

    }

    public function approve(Request $request)
    {
//
        $receive = Receive::query()->where('company_id',$this->company_id)
            ->where('challan_no',$request['challan'])
            ->with(['items'=>function($q){
                $q->where('company_id',$this->company_id);
            }])
            ->first();

//        dd($request->all());

        $purchase = Purchase::query()->where('company_id',$this->company_id)
            ->where('ref_no',$receive->ref_no)->first();

        $return = ReturnItem::query()->where('company_id',$this->company_id)
            ->where('ref_no',$purchase->ref_no)->first();

        $products = ProductMO::query()->where('company_id',$this->company_id)->get();


        DB::beginTransaction();

        try {
            $history = [];

            foreach ($receive->items as $item)
            {
                $history['company_id']=$this->company_id;
                $history['ref_no'] = $item->ref_no;
                $history['ref_id'] = $item->ref_id;
                $history['ref_type'] = 'P'; //Sales
                $history['contra_ref'] = $receive->ref_no;
                $history['relationship_id'] = $item->relationship_id;
                $history['tr_date']= Carbon::now();
                $history['product_id'] = $item->product_id;
                $history['name'] = $products->where('id',$item->product_id)->first()->name;
                $history['quantity_in'] = $item->quantity;
                $history['received'] = $item->quantity;
                $history['unit_price'] = $item->unit_price;
                $history['total_price'] = $item->total_price;

                $ids = ProductHistory::query()->create($history);

                //Update Product Table

                $product = ProductMO::query()->find($item->product_id);
                $product->on_hand = $products->where('id',$item->product_id)->first()->on_hand + $item->quantity;
                $product->purchase_qty = $products->where('id',$item->product_id)->first()->purchase_qty + $item->quantity;
                $product->save();

                // Update Purchase Table

                Purchase::query()->where('id',$purchase->id)->update(['status'=>'RC']);

                // Update Trans Product Table

                TransProduct::query()->where('id',$item->id)->update(['received'=>$item->received]);

            }
        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error','Approval Failed '.$error);
        }

        DB::commit();

        return response()->json(['success'=>'Approved Successfully'],200);

    }
}
