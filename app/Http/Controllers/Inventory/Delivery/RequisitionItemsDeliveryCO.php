<?php

namespace App\Http\Controllers\Inventory\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Company\TransCode;
use App\Models\Inventory\Movement\Delivery;
use App\Models\Inventory\Movement\ProductHistory;
use App\Models\Inventory\Movement\Requisition;
use App\Models\Inventory\Movement\Sale;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ProductMO;
use App\Traits\CompanyTrait;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RequisitionItemsDeliveryCO extends Controller
{
    use TransactionsTrait, CompanyTrait;

    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>56008,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        return view('inventory.delivery.requisition-delivery-index');
    }

    public function getReqItems()
    {
        $query = Requisition::query()->where('company_id',$this->company_id)
            ->where('req_type','C')
            ->where('status',2)
            ->with('items')->with('user')->select('requisitions.*');


        return Datatables::eloquent($query)
            ->addColumn('product', function ($query) {
                return $query->items->map(function($items) {
                    return $items->item->name;
                })->implode('<br>');
            })

            ->addColumn('quantity', function ($query) {
                return $query->items->map(function($items) {
                    return $items->quantity;
                })->implode('<br>');
            })

            ->addColumn('req_for', function ($query) {
                return $query->items->map(function($items) {
                    return isset($items->relationship_id) ?  $items->location->name : 'None';
                })->implode('<br>');
            })

            ->addColumn('action', function ($query) {

                $type = $query->req_type == 'P' ? 'Purchase' : 'Consumption';

                return '
                    <button  data-remote="viewItems/' . $query->id . '"
                        data-requisition="' . $query->ref_no . '"
                        data-date="' . $query->req_date . '"
                        data-type="' . $type . '"
                        id="edit-requisition" type="button" class="btn btn-delivery-index btn-xs btn-primary">Delivery</button>
                    ';
            })
            ->rawColumns(['product','quantity','req_type','req_for','action'])
            ->make(true);
    }

    public function items($id)
    {
        $requisition = TransProduct::query()->where('company_id',$this->company_id)
            ->where('ref_id',$id)->where('ref_type','R')
            ->with('item')->with('requisition')->with('location') ->get();

        return response()->json($requisition);
    }

    public function store(Request $request)
    {

//        dd($request->all());

        DB::beginTransaction();

        try{

            $fiscal = $this->get_fiscal_data_from_current_date($this->company_id);

//            $invoice = Sale::query()->where('company_id',$this->company_id)
//                ->where('invoice_no',$id)->with('items')->first();

            $tr_code =  TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','DC')
                ->where('fiscal_year',$fiscal->fiscal_year)
                ->lockForUpdate()->first();

//                            dd($tr_code);

            $challan_no = $tr_code->last_trans_id;

            $data['company_id'] = $this->company_id;
            $data['challan_no'] = $challan_no;
            $data['ref_no'] = $request['req_no'];
            $data['delivery_type'] = 'CM';
            $data['delivery_date'] = Carbon::now();
            $data['user_id'] = $this->user_id;
            $data['status'] = 'CR'; //Created

            $inserted = Delivery::query()->create($data); //Insert Data Into Deliveries Table

            if ($request['item']) {
                foreach ($request['item'] as $item) {
                    if ($item['quantity'] > 0) {
                        $delivery['company_id'] = $this->company_id;
                        $delivery['ref_no'] = $challan_no;
                        $delivery['ref_id'] = $inserted->id;
                        $delivery['tr_date'] = $inserted->delivery_date;
                        $delivery['ref_type'] = 'D';
                        $delivery['product_id'] = $item['product_id'];
                        $delivery['quantity'] = $item['quantity'];
                        $delivery['unit_price'] = $item['unit_price'];
                        $delivery['total_price'] = $item['quantity'] * $item['unit_price'];
                        $delivery['relationship_id'] = $item['relationship_id'];

                        TransProduct::query()->create($delivery);
                        ProductMO::query()->where('id',$item['product_id'])->increment('committed',$item['quantity']);
                        //                TransProduct::query()->where('id',$item->id)->update(['delivered'=>$item['quantity']]);
                    }
                }
            }

            if($request->has('full_delivery'))
            {
                Requisition::query()->where('company_id',$this->company_id)
                    ->where('ref_no',$request['req_no'])
                    ->update(['status'=>3]);
            }

            TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','DC')
                ->where('fiscal_year',$fiscal->fiscal_year)
                ->increment('last_trans_id');


        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Delivery Successfully Completed For Approval'],200);

    }
}
