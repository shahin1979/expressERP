<?php

namespace App\Http\Controllers\Inventory\Receives;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Common\UserActivity;
use App\Models\Company\CompanyProperty;
use App\Models\Inventory\Movement\Delivery;
use App\Models\Inventory\Movement\ProductHistory;
use App\Models\Inventory\Movement\Purchase;
use App\Models\Inventory\Movement\Receive;
use App\Models\Inventory\Movement\ReturnItem;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ProductMO;
use App\Traits\AccountTrait;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ApproveReceiveCO extends Controller
{
    use TransactionsTrait;

    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>54010,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

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
                $rt_challan = null;
                $rt_ref = null;
                $rt_date = null;
                $rt_type = null;

                if(!empty($return))
                {
                    $rt_type = isset($return->return_type) == 'LP' ? 'Purchase Return' : ($return->return_type == 'PR' ? 'Return To Production' : 'Return Imported Items');
                    $rt_challan = $return->challan_no;
                    $rt_ref = $return->ref_no;
                    $rt_date = $return->return_date;
                }


                return '
                    <button  data-remote="viewItems/' . $query->id . '"

                        data-challan="' . $query->challan_no . '"
                        data-date="' . $query->receive_date . '"
                        data-type="' . $type . '"
                        data-ref_no="' . $query->ref_no . '"

                        data-rt_challan="' . $rt_challan . '"
                        data-rt_date="' . $rt_date . '"
                        data-rt_type="' . $rt_type . '"
                        data-rt_ref="' . $rt_ref . '"
                        id="details-receive" type="button" class="btn btn-receive-details btn-xs btn-primary">Details</button>
                    ';

            })
            ->rawColumns(['product','supplier','quantity','action'])
            ->make(true);
    }

    public function ajaxData($id)
    {
        $invoice = Receive::query()->where('id',$id)->first();
        $return = ReturnItem::query()->where('company_id',$this->company_id)
            ->where('ref_no',$invoice->ref_no)->first();

        $receives = $this->receive_items($id);
        $tr_receive = $this->transactions($id,1);

        $returns=null;
        $tr_return = null;


        if(is_null($return)) {
            $returns = null;
            $tr_return = null;
        }else{
            $returns = $this->return_items($return->id);
            $tr_return = $this->transactions($return->id,2);
        }


        $response = [
            'receives' => $receives,
            'returns' => $returns,
            'tr_receive' =>$tr_receive,
            'tr_return' =>$tr_return
        ];

        return response()->json($response);

    }

    public function receive_items($id)
    {
        return TransProduct::query()->where('company_id',$this->company_id)
            ->where('ref_id',$id)->where('ref_type','C')
            ->with('supplier')
            ->with('item')->with('receive') ->get();
    }

    public function transactions($id, $type)
    {
        //Type 1=Receive. 2=Return;

        $company = CompanyProperty::query()->where('company_id',$this->company_id)->first();
        $accounts = GeneralLedger::query()->where('company_id',$this->company_id)->get();

        if($type == 1)
        {
            $receives = $this->receive_items($id);

            $trans = Collect();;

            // Voucher For Receive Data
            foreach ($receives as $row)
            {

                $head = $this->get_in_stock_gl_head($row->product_id,$this->company_id);
                $name = $this->get_account_name($this->company_id,$head);


                $line=[];
                $line['gl_head'] = $head.' : '.$name;
                $line['dr_amt'] = $row->unit_price * $row->quantity;
                $line['cr_amt'] = 0;

                $trans->push($line);

                $line=[];
                $line['gl_head'] = $company->default_purchase.' : '.$this->get_account_name($this->company_id,$company->default_purchase);
                $line['dr_amt'] = 0;
                $line['cr_amt'] = $row->unit_price * $row->quantity;

                $trans->push($line);
            }
        }



        // Voucher For Return Data

        if($type == 2)
        {
            $returns = $this->return_items($id);

            $trans = Collect();;

            if($returns)
            {
                foreach ($returns as $row)
                {
                    $line=[];
                    $line['gl_head'] = $row->supplier->ledger_acc_no.' : '.$row->supplier->acc_name->acc_name.'(Return)';
                    $line['dr_amt'] = $row->unit_price * $row->quantity;
                    $line['cr_amt'] = 0;

                    $trans->push($line);

                    $line=[];
                    $line['gl_head'] = $company->default_purchase.' : '.$this->get_account_name($this->company_id,$company->default_purchase);
                    $line['dr_amt'] = 0;
                    $line['cr_amt'] = $row->unit_price * $row->quantity;

                    $trans->push($line);
                }
            }
        }


        $result = $trans->groupBy('gl_head')->map(function ($row)  {
            $grouped = Collect();
            $grouped->debit = $row->sum('dr_amt');
            $grouped->credit = $row->sum('cr_amt');
            return $grouped;
        });

        $final = collect();

        foreach ($result as $head=>$row)
        {
            $tran['head'] = $head;
            $tran['debit'] = $row->debit;
            $tran['credit'] = $row->credit;

            $final->push($tran);
        }

        return $final;
    }

    public function return_items($id)
    {

        return TransProduct::query()->where('company_id',$this->company_id)
            ->where('ref_id',$id)->where('ref_type','T')
            ->with('supplier')
            ->with('item')->with('returnItem') ->get();
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


                // Update Trans Product Table
                TransProduct::query()->where('id',$item->id)->update(['received'=>$item->received]);

            }

            // Update Purchase Table
            Purchase::query()->where('id',$purchase->id)->update(['status'=>'RC']);

            // Create Account Voucher for Receive Items

            $transactions = $this->transactions($receive->id, 1);
            $period = $this->get_fiscal_data_from_current_date($this->company_id);
//            dd($transactions);
            foreach ($transactions as $row)
            {
                $input=[];

                $input['company_id'] = $this->company_id;
                $input['project_id'] = null;
                $input['tr_code'] = 'IR';
                $input['fp_no'] = $period->fp_no;
                $input['trans_type_id'] = 9; //  Purchase
                $input['period'] = Str::upper(Carbon::now()->format('Y-M'));
                $input['trans_id'] = Carbon::now()->format('Ymdhmis');
                $input['trans_group_id'] = Carbon::now()->format('Ymdhmis');
                $input['trans_date'] = Carbon::now();
                $input['voucher_no'] = $receive->challan_no;
                $input['acc_no'] = Str::substr($row['head'],0,8);
                $input['ledger_code'] = Str::substr($input['acc_no'], 0, 3);
//                $input['contra_acc'] = $company_properties->default_purchase;
                $input['dr_amt'] = $row['debit'] ;
                $input['cr_amt'] = $row['credit'] ;
                $input['trans_amt'] = $row['debit'] + $row['credit'];
                $input['currency'] = get_currency($this->company_id);
                $input['fiscal_year'] = $period->fiscal_year;
                $input['trans_desc1'] = 'Receive Purchase Items';
                $input['trans_desc2'] = $receive->ref_ref;
                $input['post_flag'] = false;
                $input['user_id'] = $this->user_id;

                $output = $this->transaction_entry($input);
            }

            if(!is_null($return))
            {
                $transactions = $this->transactions($return->id, 2);

                foreach ($transactions as $row)
                {
                    $input=[];

                    $input['company_id'] = $this->company_id;
                    $input['project_id'] = null;
                    $input['tr_code'] = 'IR';
                    $input['fp_no'] = $period->fp_no;
                    $input['trans_type_id'] = 9; //  Purchase
                    $input['period'] = Str::upper(Carbon::now()->format('Y-M'));
                    $input['trans_id'] = Carbon::now()->format('Ymdhmis');
                    $input['trans_group_id'] = Carbon::now()->format('Ymdhmis');
                    $input['trans_date'] = Carbon::now();
                    $input['voucher_no'] = $return->challan_no;
                    $input['acc_no'] = Str::substr($row['head'],0,8);
                    $input['ledger_code'] = Str::substr($input['acc_no'], 0, 3);
//                $input['contra_acc'] = $company_properties->default_purchase;
                    $input['dr_amt'] = $row['debit'];
                    $input['cr_amt'] = $row['credit'];
                    $input['trans_amt'] = $row['debit'] + $row['credit'];
                    $input['currency'] = get_currency($this->company_id);
                    $input['fiscal_year'] = $period->fiscal_year;
                    $input['trans_desc1'] = 'Return Purchase Items';
                    $input['trans_desc2'] = $return->ref_ref;
                    $input['post_flag'] = false;
                    $input['user_id'] = $this->user_id;

                    $output = $this->transaction_entry($input);
                }
                //update Return Table
                ReturnItem::query()->find($return->id)->update(['status'=>'RT','account_post'=>true,'account_voucher'=>$return->challan_no,'approve_by'=>$this->user_id,'approve_date'=>Carbon::now()]);
            }


            Receive::query()->find($receive->id)->update(['status'=>'RC','account_post'=>true,'account_voucher'=>$receive->challan_no,'approve_by'=>$this->user_id,'approve_date'=>Carbon::now()]);

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error'=>$error],404);
        }

        DB::commit();

        return response()->json(['success'=>'Approved Successfully'],200);
    }

    public function reject(Request $request)
    {
        $receive = Receive::query()->where('company_id',$this->company_id)
            ->where('challan_no',$request['challan'])->first();
        $receive->update(['status'=>'RJ','approve_by'=>$this->user_id,'approve_date'=>Carbon::now()]);

        $return = ReturnItem::query()->where('company_id',$this->company_id)
            ->where('ref_no',$receive->ref_no)->first();

        $return->update(['status'=>'RJ','approve_by'=>$this->user_id,'approve_date'=>Carbon::now()]);
    }
}
