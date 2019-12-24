<?php

namespace App\Http\Controllers\Accounts\Trans;

use App\Http\Controllers\Controller;
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

        $trans = Transaction::query()->where('post_flag',false)
            ->where('company_id',$this->company_id)
            ->select('voucher_no','trans_date','user_id', DB::raw('count(*) as trans_count'),
                DB::raw('sum(dr_amt) as trans_amt'))
            ->groupBy('voucher_no','trans_date','user_id')->with('user')->get();

        return DataTables::of($trans)
            ->addColumn('action', function ($trans) {

                return '<div class="btn-group btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="details/'.$trans->voucher_no.'"  type="button" class="btn btn-view btn-sm btn-secondary"><i class="fa fa-open">Detail</i></button>
                    <button data-remote="edit/' . $trans->id . '" data-rowid="'. $trans->id . '"
                        data-name="'. $trans->acc_name . '"
                        type="button" class="btn btn-sm btn-ledger-edit btn-primary pull-center"><i class="fa fa-edit" >Edit</i></button>
                        <button data-remote="head/delete/'.$trans->id.'"  type="button" class="btn btn-delete btn-sm btn-danger"><i class="fa fa-trash">Delete</i></button>
                    </div>
                    ';
            })
            ->addColumn('pending', function ($trans) {

                $now = Carbon::now()->format('Y-m-d'); // or your date as well
                $days =  dateDifference($trans->trans_date,$now);

                return ceil(abs($days)).' Days';
            })

            ->rawColumns(['action','status','pending'])
            ->make(true);
    }

    public function details($id)
    {
        $data = Transaction::query()->where('company_id',$this->company_id)
            ->where('voucher_no',$id)->get();

        return response()->json($data);
    }
}
