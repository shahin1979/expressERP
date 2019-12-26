<?php

namespace App\Http\Controllers\Accounts\Trans;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Trans\Transaction;
use App\Models\Common\UserActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AuthoriseTransactionCO extends Controller
{
    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>44035,'user_id'=>$this->user_id
            ]);

        return view('accounts.trans.authorise-trans-index');
    }

    public function getVoucherData()
    {
//        $trans = Transaction::query()->where('post_flag',false)
//            ->where('company_id',$this->company_id)->with('account');

        $vouchers = Transaction::query()->where('post_flag',false)
            ->where('company_id',$this->company_id)
            ->select('voucher_no','trans_date','tr_code','user_id', DB::raw('count(*) as trans_count'),
                DB::raw('sum(dr_amt) as trans_amt'))
            ->groupBy('voucher_no','trans_date','tr_code','user_id')->with('user')->get();

//        $vouchers = Transaction::query()
//            ->where('company_id',$this->company_id)
//            ->where('post_flag',false)
//            ->distinct()->get(['voucher_no','trans_date']);

        return DataTables::of($vouchers)

            ->editColumn('voucher', function ($vouchers) {

                return $vouchers->tr_code.'-'.$vouchers->voucher_no;
            })

            ->addColumn('ledger', function ($vouchers) {

                $accounts = Transaction::query()->where('company_id',$this->company_id)
                    ->where('voucher_no',$vouchers->voucher_no)->with('account')->get();

                return $accounts->map(function($items)  {
                    return $items->account->acc_name;
                })->implode('<br>');
            })

            ->addColumn('dr_amt', function ($vouchers) {

                $accounts = Transaction::query()->where('company_id',$this->company_id)
                    ->where('voucher_no',$vouchers->voucher_no)->get();

                return $accounts->map(function($items)  {
                    return number_format($items->dr_amt,2);
                })->implode('<br>');
            })

            ->addColumn('cr_amt', function ($vouchers) {

                $accounts = Transaction::query()->where('company_id',$this->company_id)
                    ->where('voucher_no',$vouchers->voucher_no)->with('account')->get();

                return $accounts->map(function($items)  {
                    return number_format($items->cr_amt,2);
                })->implode('<br>');
            })


            ->addColumn('action', function ($vouchers) {

                return '<div class="btn-group btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="authorise/'.$vouchers->voucher_no.'"  type="button" class="btn btn-approve btn-sm btn-primary"><i class="fa fa-open">Authorise</i></button>
                    <button data-remote="head/delete/'.$vouchers->id.'"  type="button" class="btn btn-delete btn-sm btn-danger"><i class="fa fa-trash">Reject</i></button>
                    </div>
                    ';
            })
            ->addColumn('pending', function ($vouchers) {

                $now = Carbon::now()->format('Y-m-d'); // or your date as well
                $days =  dateDifference($vouchers->trans_date,$now);

                return ceil(abs($days)).' Days';
            })

            ->rawColumns(['voucher','ledger','dr_amt','cr_amt','action','status','pending'])
            ->make(true);


//        $vouchers = Transaction::query()->distinct()

//        return DataTables::of($trans)
//            ->addColumn('action', function ($trans) {
//
//                return '<div class="btn-group btn-group-sm" role="group" aria-label="Action Button">
//                    <button data-remote="details/'.$trans->voucher_no.'"  type="button" class="btn btn-view btn-sm btn-secondary"><i class="fa fa-open">Detail</i></button>
//                    <button data-remote="edit/' . $trans->id . '" data-rowid="'. $trans->id . '"
//                        data-name="'. $trans->acc_name . '"
//                        type="button" class="btn btn-sm btn-ledger-edit btn-primary pull-center"><i class="fa fa-edit" >Edit</i></button>
//                        <button data-remote="head/delete/'.$trans->id.'"  type="button" class="btn btn-delete btn-sm btn-danger"><i class="fa fa-trash">Delete</i></button>
//                    </div>
//                    ';
//            })
//            ->addColumn('pending', function ($trans) {
//
//                $now = Carbon::now()->format('Y-m-d'); // or your date as well
//                $days =  dateDifference($trans->trans_date,$now);
//
//                return ceil(abs($days)).' Days';
//            })
//
//            ->rawColumns(['action','status','pending'])
//            ->make(true);
    }

    public function authorise($id)
    {
        $data = Transaction::query()->where('company_id',$this->company_id)
            ->where('voucher_no',$id)->get();

        DB::beginTransaction();

        try{

            foreach ($data as $row)
            {
                $var = str_pad($row->fp_no,2,"0",STR_PAD_LEFT);

                GeneralLedger::query()->where('company_id',$this->company_id)
                    ->where('acc_no',$row->acc_no)
                    ->increment('dr_'.$var, $row->dr_amt);

                GeneralLedger::query()->where('company_id',$this->company_id)
                    ->where('acc_no',$row->acc_no)
                    ->increment('cr_'.$var, $row->cr_amt);

                GeneralLedger::query()->where('company_id',$this->company_id)
                    ->where('ledger_code',substr($row->acc_no,0,3))
                    ->where('is_group',true)
                    ->increment('dr_'.$var, $row->dr_amt);

                GeneralLedger::query()->where('company_id',$this->company_id)
                    ->where('ledger_code',substr($row->acc_no,0,3))
                    ->where('is_group',true)
                    ->increment('cr_'.$var, $row->cr_amt);
            }

            Transaction::query()->where('company_id',$this->company_id)
                ->where('voucher_no',$id)
                ->update(['post_flag'=>true, 'post_date'=>Carbon::now(), 'authorizer_id'=>$this->user_id]);

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success','Voucher '.$id.' Successfully Approved'],200);
    }
}
