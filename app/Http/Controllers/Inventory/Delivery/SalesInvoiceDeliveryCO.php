<?php

namespace App\Http\Controllers\Inventory\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Company\TransCode;
use App\Models\Inventory\Movement\Delivery;
use App\Models\Inventory\Movement\Requisition;
use App\Models\Inventory\Movement\Sale;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ProductMO;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SalesInvoiceDeliveryCO extends Controller
{
    use TransactionsTrait;

    public function index()
    {

        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>56002,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        return view('inventory.delivery.delivery-sales-invoice-index');
    }

    public function getInvoiceItems()
    {
        $query = Sale::query()->where('company_id',$this->company_id)
            ->where('status','AP')
            ->where('delivery_status',false)
            ->with(['items'=>function($q){
                $q->where('company_id',$this->company_id)
                ->where('ref_type','S');
            }])
            ->with('user')
            ->with('customer')->select('sales.*');


        return Datatables::eloquent($query)

            ->addColumn('customer',function ($query){
                return $query->customer->name;
            })
            ->addColumn('product', function ($query) {
                return $query->items->map(function($items) {
                    return '<span style="color: #a71d2a; font-weight: bold">'. $items->item->name .'</span>';
                })->implode('<br>');
            })

            ->addColumn('quantity', function ($query) {
                return $query->items->map(function($items) {
                    return number_format($items->quantity,2);
                })->implode('<br>');
            })

            ->addColumn('due', function ($query) {
                return $query->items->map(function($items) {
                    return number_format(($items->quantity - $items->delivered),2);
                })->implode('<br>');
            })

            ->addColumn('action', function ($query) {

                return '
                    <button  data-remote="viewInvoiceItems/' . $query->id . '"
                        data-invoice="' . $query->invoice_no . '"
                        data-date="' . $query->invoice_date . '"
                        data-customer="'.$query->customer->name.'"
                        data-user="'.$query->user->name.'"
                        id="view-invoice" type="button" class="btn btn-delivery-index btn-xs btn-primary">Delivery</button>
                    ';
            })
            ->rawColumns(['product','quantity','due','action'])
            ->make(true);
    }

    public function ajax_call($id)
    {
        $items = TransProduct::query()->where('company_id',$this->company_id)
            ->where('ref_id',$id)->where('ref_type','S')
            ->with('item')->with('invoice')->get();

        return response()->json($items);
    }

    public function store(Request $request, $id)
    {

        DB::beginTransaction();

        try{

            $fiscal = $this->get_fiscal_data_from_current_date($this->company_id);

            $tr_code =  TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','DC')
                ->where('fiscal_year',$fiscal->fiscal_year)
                ->lockForUpdate()->first();

            $invoice = Sale::query()->where('company_id',$this->company_id)
                ->where('invoice_no',$id)->first();

            $challan_no = $tr_code->last_trans_id;

            $data['company_id'] = $this->company_id;
            $data['challan_no'] = $challan_no;
            $data['ref_no'] = $id;
            $data['delivery_type'] = 'SL';
            $data['delivery_date'] = Carbon::now()->format('Y-m-d');
            $data['user_id'] = $this->user_id;
            $data['relationship_id']=$invoice->customer_id;
            $data['status'] = 'CR'; //Created

            $inserted = Delivery::query()->create($data); //Insert Data Into Deliveries Table

            if ($request['item']) {
                foreach ($request['item'] as $item) {
                    if ($item['quantity'] > 0)
                    {
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
                    }
                }
            }

            TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','DC')
                ->where('fiscal_year',$fiscal->fiscal_year)
                ->increment('last_trans_id');

            Sale::query()->where('company_id',$this->company_id)
                ->where('invoice_no',$id)
                ->update(['status'=>'DL','direct_delivery'=>false]);

//            if($request->has('full_delivery'))
//            {
//                Requisition::query()->where('company_id',$this->company_id)
//                    ->where('ref_no',$request['req_no'])
//                    ->update(['status'=>3]);
//            }

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
