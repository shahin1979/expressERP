<?php

namespace App\Http\Controllers\Accounts\Ledger;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Previous\GeneralLedgerBackup;
use App\Models\Accounts\Previous\TransactionBackup;
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
            $ledgers = GeneralLedger::query()->where('company_id',$this->company_id)->get();

            $toDate = Carbon::createFromFormat('d-m-Y',$request['date_to'])->format('Y-m-d');
            $fromDate = Carbon::createFromFormat('d-m-Y',$request['date_to'])->format('Y-m-01');

            $trans = Transaction::query()->where('company_id',$this->company_id)
                ->where('tr_state',false)
                ->whereBetween('trans_date',[$fromDate, $toDate])
                ->select('acc_no',DB::Raw('sum(dr_amt) dr_amt, sum(cr_amt) cr_amt'))
                ->groupBy('acc_no')
                ->get();


            $fp_no = get_fp_from_month_sl(Carbon::createFromFormat('d-m-Y',$request['date_to'])->format('m'),$this->company_id);

            $report = collect();
            $ln = [];

            foreach ($ledgers as $row)
            {
                $ln['acc_no'] = $row->acc_no;
                $ln['acc_name'] = $row->acc_name;
                $ln['acc_type'] = $row->acc_type;
                $ln['ledger_code'] = $row->ledger_code;
                $ln['is_group'] = $row->is_group;

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

            $params = collect();

            $params['toDate'] = $toDate;
            $params['report_type'] = $request['report_type'] == 'A' ? false : true;

//            $group = $report->

            return view('accounts.report.ledger.rep-trial-balance-index',compact('report','params'));
        }

        return view('accounts.report.ledger.rep-trial-balance-index');
    }



    public function previousIndex(Request $request)
    {
        if(!empty($request['report_date']))
        {
            $fiscal_year = $request['report_year'];

            $ledgers = GeneralLedgerBackup::query()->where('company_id',$this->company_id)
                ->where('fiscal_year',$fiscal_year)->get();

            $toDate = Carbon::createFromFormat('d-m-Y',$request['report_date'])->format('Y-m-d');
            $fromDate = Carbon::createFromFormat('d-m-Y',$request['report_date'])->format('Y-m-01');

            $trans = TransactionBackup::query()->where('company_id',$this->company_id)
                ->where('tr_state',false)
                ->where('fiscal_year',$fiscal_year)
                ->whereBetween('trans_date',[$fromDate, $toDate])
                ->select('acc_no',DB::Raw('sum(dr_amt) dr_amt, sum(cr_amt) cr_amt'))
                ->groupBy('acc_no')
                ->get();


            $fp_no = get_fp_from_month_sl(Carbon::createFromFormat('d-m-Y',$request['report_date'])->format('m'),$this->company_id);

            $report = collect();
            $ln = [];

            foreach ($ledgers as $row)
            {
                $ln['acc_no'] = $row->acc_no;
                $ln['acc_name'] = $row->acc_name;
                $ln['acc_type'] = $row->acc_type;
                $ln['ledger_code'] = $row->ledger_code;
                $ln['is_group'] = $row->is_group;

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

            $params = collect();

            $params['toDate'] = $toDate;
            $params['report_type'] = $request['report_type'] == 'A' ? false : true;

//            $group = $report->

            return view('accounts.report.ledger.rep-trial-balance-index',compact('report','params'));
        }

        return view('accounts.report.previous.prev-trial-balance-index');
    }
}
