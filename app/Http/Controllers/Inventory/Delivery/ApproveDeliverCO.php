<?php

namespace App\Http\Controllers\Inventory\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Common\UserActivity;
use App\Models\Company\CompanyProperty;
use App\Models\Inventory\Movement\Delivery;
use App\Models\Inventory\Movement\ProductHistory;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ProductMO;
use App\Traits\ProductTrait;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ApproveDeliverCO extends Controller
{
    use TransactionsTrait, ProductTrait;

    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>57010,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        return view('inventory.delivery.approve-delivery-index');
    }

    public function getDeliveryItems()
    {
        $query = Delivery::query()->where('company_id',$this->company_id)
            ->where('status','CR')
            ->whereNotIn('delivery_type',['EX'])
            ->with('customer')->with('costcenter')
            ->with(['items'=>function($q){
                $q->where('company_id',$this->company_id)
                ->where('ref_type','D');
            }])
            ->with('user')->select('deliveries.*');


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

            ->addColumn('del_for', function ($query) {

                    return $query->delivery_type == 'SL' ? $query->customer->name : ($query->delivery_type == 'EX' ? $query->customer->name : $query->costcenter->name);

            })

            ->addColumn('del_type', function ($query) {
                return $query->delivery_type == 'SL' ? 'Sales' : ($query->delivery_type == 'CM' ? 'Consumption' : 'Export');
            })

            ->addColumn('action', function ($query) {

                return '
                    <button  data-remote="viewDeliveryItems/' . $query->id . '"
                        data-challan="' . $query->challan_no . '"
                        data-requisition="' . $query->ref_no . '"
                        data-against="' . ($query->delivery_type == 'SL' ? $query->customer->name : ($query->delivery_type == 'EX' ? $query->customer->name : $query->costcenter->name)) . '"
                        data-date="' . $query->delivery_date . '"
                        id="view-delivery-details" type="button" class="btn btn-delivery-details btn-xs btn-primary">Details</button>
                    ';

            })
            ->rawColumns(['product','quantity','del_type','del_for','action'])
            ->make(true);
    }

    public function ajax_call($id)
    {
        $challan = Delivery::query()->where('id',$id)
            ->with(['items'=>function($q){
                $q->where('company_id',$this->company_id)
                ->where('ref_type','D');
            }])
            ->first();

        $company = CompanyProperty::query()->where('company_id',$this->company_id)->first();
        $accounts = GeneralLedger::query()->where('company_id',$this->company_id)->get();

        $products = TransProduct::query()->where('company_id',$this->company_id)
            ->where('ref_id',$id)->where('ref_type','D')
            ->with('costcenter')->with('customer')->with('item')
            ->get();


        $transactions = $this->get_delivery_transactions_array($products,$this->company_id);

        $response = [
            'products'=>$products,
            'transactions' => $transactions,
        ];

        return json_encode($response, true) ;
    }

    public function approve(Request $request, $id)
    {

        switch ($request['action'])
        {
            case    'approve':

                DB::beginTransaction();

                try{
                    $delivery = Delivery::query()->where('company_id',$this->company_id)
                        ->where('challan_no',$id)
                        ->with('costcenter')->with('customer')
                        ->with(['items'=>function($q){
                            $q->where('company_id',$this->company_id)
                            ->where('ref_type','D');
                        }])
                        ->first();

                    foreach ($delivery->items as $item)
                    {
                        $history['company_id'] = $this->company_id;
                        $history['ref_no'] = $id;
                        $history['ref_id'] = $delivery->id;
                        $history['tr_date'] = Carbon::now();
                        $history['ref_type'] = $delivery->delivery_type == 'SL' ? 'S' : 'C'; // Sales Delivery
                        $history['contra_ref'] = $delivery->ref_no;
                        $history['product_id'] = $item->product_id;
                        $history['quantity_in'] = 0;
                        $history['quantity_out'] = $item->quantity;
                        $history['unit_price'] = $this->get_lifo_price($item->product_id,$this->company_id);
                        $history['total_price'] = $item->quantity * $history['unit_price'];
                        $history['relationship_id'] = $item->relationship_id;

                        ProductHistory::query()->create($history);
                        ProductMO::query()->where('id',$item['product_id'])->increment('sell_qty',$item['quantity']);
                        ProductMO::query()->where('id',$item['product_id'])->decrement('on_hand',$item['quantity']);
                        TransProduct::query()->where('id',$item->id)->update(['delivered'=>$item['quantity']]);
                    }

                    // Account Transaction for Sales Invoice
                    $t_data = $this->ajax_call($delivery->id);
                    $trans= json_decode($t_data,true);

                    $desc2 = $delivery->delivery_type == 'SL' ? $delivery->customer->name : $delivery->costcenter->name;
                    $period = $this->get_fiscal_data_from_current_date($this->company_id);

                    foreach ($trans['transactions'] as $row)
                    {
                        $input = $this->inputs($row,$period,$delivery,$desc2);
                        $this->transaction_entry($input);
                    }

                    Delivery::query()->where('challan_no',$id)
                        ->where('company_id',$this->company_id)
                        ->update(['status'=>'DL','approve_date'=>Carbon::now(),'approve_by'=>$this->user_id]);

                }catch (\Exception $e)
                {
                    DB::rollBack();
                    $error = $e->getMessage();
                    return response()->json(['error' => $error], 404);
                }

                DB::commit();

                break;

            case 'reject':
                DB::beginTransaction();

                try {
                    Delivery::query()->where('challan_no',$id)
                        ->where('company_id',$this->company_id)
                        ->update(['status','RJ','approve_date'=>Carbon::now(),'approve_by'=>$this->user_id]);

                }catch (\Exception $e)
                {
                    DB::rollBack();
                    $error = $e->getMessage();
                    return response()->json(['error' => $error], 404);
                }


        }
        return response()->json(['success'=>'Delivery Approved '.$id],200);
    }

    public function inputs($row, $period, $delivery, $desc2)
    {
        $input = [];

        $input['company_id'] = $this->company_id;
        $input['project_id'] = null;
        $input['cost_center_id'] = $delivery->delivery_type == 'SL' ? null : $delivery->relationship_id;
        $input['tr_code'] = 'DC';
        $input['fp_no'] = $period->fp_no;
        $input['trans_type_id'] = 10; //  Sales
        $input['period'] = Str::upper(Carbon::now()->format('Y-M'));
        $input['trans_id'] = Carbon::now()->format('Ymdhmis');
        $input['trans_group_id'] = Carbon::now()->format('Ymdhmis');
        $input['trans_date'] = Carbon::now();
        $input['voucher_no'] = $delivery->challan_no;
        $input['acc_no'] = $row['gl_head'];
        $input['ledger_code'] = Str::substr($input['acc_no'],0,3);
        $input['ref_no'] = $delivery->ref_no;
        $input['contra_acc'] = $delivery->relationship_id;
        $input['dr_amt'] = $row['debit_amt'];
        $input['cr_amt'] = $row['credit_amt'];
        $input['trans_amt'] = $row['debit_amt'] + $row['credit_amt'];
        $input['currency'] = get_currency($this->company_id);
        $input['fiscal_year'] = $period->fiscal_year;
        $input['trans_desc1'] = $delivery->delivery_type == 'SL' ? 'Sales Delivery' : 'Delivery for Consumption';;
        $input['trans_desc2'] = $desc2;
        $input['remote_desc'] = $delivery->relationship_id;
        $input['post_flag'] = false;
        $input['user_id'] = $this->user_id;

        return $input;
    }




}
