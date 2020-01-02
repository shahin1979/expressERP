<?php

namespace App\Http\Controllers\Accounts\Ledger;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Trans\Transaction;
use App\Models\Common\UserActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RepTrialBalanceCO extends Controller
{
    public function index(Request $request)
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>42050,'user_id'=>$this->user_id
            ]);

        if(!empty($request['date_to']))
        {
            $ledgers = GeneralLedger::query()->where('company_id',$this->company_id)
                ->where('is_group',false)->get();

            $fromDate = Carbon::createFromFormat('d-m-Y',$request['date_to'])->format('Y-m-d');
            $toDate = Carbon::createFromFormat('d-m-Y',$request['date_to'])->format('Y-m-01');

            $trans = Transaction::query()->where('company_id',$this->company_id)
                ->where('tr_state',false)
                ->whereBetween('trans_date',[$toDate,$fromDate])
                ->select('acc_no',DB::Raw('sum(dr_amt) dr_amt, sum(cr_amt) cr_amt'))
                ->groupBy('acc_no')
                ->get();

//            dd($trans->where('acc_no','10112102')->first()->dr_amt);

            $fp_no = get_fp_from_month_sl(Carbon::createFromFormat('d-m-Y',$request['date_to'])->format('m'),$this->company_id);

            $report = collect();
            $ln = [];

            foreach ($ledgers as $row)
            {
                $ln['acc_no'] = $row->acc_no;
                $ln['acc_name'] = $row->acc_name;
                $ln['acc_type'] = $row->acc_type;

                $dr_amt = $row->start_dr;
                $cr_amt = $row->start_cr;



                for($i = 1; $i< $fp_no; $i++)
                {
                    $var = str_pad($i,2,"0",STR_PAD_LEFT);
                    $field_dr = 'dr_'.$var;
                    $field_cr = 'cr_'.$var;

                    $dr_amt = $dr_amt + $row->{$field_dr};
                    $cr_amt = $cr_amt + $row->{$field_cr};

//                    dd($field_dr);
                }

                $ln['opening_dr'] = $dr_amt;
                $ln['opening_cr'] = $cr_amt;





                $ln['dr_tr'] = isset($trans->where('acc_no',$row->acc_no)->first()->dr_amt) ? $trans->where('acc_no',$row->acc_no)->first()->dr_amt : 0;
                $ln['cr_tr'] = isset($trans->where('acc_no',$row->acc_no)->first()->cr_amt) ? $trans->where('acc_no',$row->acc_no)->first()->cr_amt : 0;

                $ln['balance'] = ($ln['opening_dr'] + $ln['dr_tr']) - ($ln['opening_cr'] + $ln['cr_tr']);

                $report->push($ln);
            }

            return view('accounts.report.ledger.rep-trial-balance-index',compact('report','toDate'));
        }

//        dd (Carbon::createFromFormat('d-m-Y',$request['date_to'])->format('Y-m-01'));

        return view('accounts.report.ledger.rep-trial-balance-index');
    }
}
