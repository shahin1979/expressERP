<?php


namespace App\Traits;


use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Trans\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait TrialBalanceTrait
{
    public function GroupHeadTrialBalanceData($company_id,$glhead,$todate)
    {

        $ledger = GeneralLedger::query()->where('company_id',$company_id)
            ->where('acc_no',$glhead)->first();

        $ledgers = GeneralLedger::query()->where('company_id',$company_id)
            ->whereBetween('acc_no', [$ledger->acc_no, $ledger->acc_range])
            ->get();

//        $toDate = Carbon::createFromFormat('d-m-Y', $request['date_to'])->format('Y-m-d');
        $fromdate = Carbon::createFromFormat('Y-m-d', $todate)->format('Y-m-01');


        $trans = Transaction::query()->where('company_id', $company_id)
            ->where('tr_state', false)
            ->whereBetween('trans_date', [$fromdate, $todate])
            ->whereBetween('acc_no', [$ledger->acc_no, $ledger->acc_range])
            ->select('acc_no', DB::Raw('sum(dr_amt) dr_amt, sum(cr_amt) cr_amt'))
            ->groupBy('acc_no')
            ->get();


        $fp_no = get_fp_from_month_sl(Carbon::createFromFormat('Y-m-d',$todate)->format('m'), $company_id);

//        dd($fp_no);

        $report = collect();
        $ln = [];

        foreach ($ledgers as $row) {
            $ln['acc_no'] = $row->acc_no;
            $ln['acc_name'] = $row->acc_name;
            $ln['acc_type'] = $row->acc_type;
            $ln['ledger_code'] = $row->ledger_code;
            $ln['is_group'] = $row->is_group;

            $dr_amt = $row->start_dr;
            $cr_amt = $row->start_cr;

            for ($i = 1; $i < $fp_no; $i++) {
                $var = str_pad($i, 2, "0", STR_PAD_LEFT);
                $field_dr = 'dr_' . $var;
                $field_cr = 'cr_' . $var;

                $dr_amt = $dr_amt + $row->{$field_dr};
                $cr_amt = $cr_amt + $row->{$field_cr};

//                    dd($field_dr);
            }

            $ln['opening_dr'] = $dr_amt;
            $ln['opening_cr'] = $cr_amt;

            $ln['dr_tr'] = isset($trans->where('acc_no', $row->acc_no)->first()->dr_amt) ? $trans->where('acc_no', $row->acc_no)->first()->dr_amt : 0;
            $ln['cr_tr'] = isset($trans->where('acc_no', $row->acc_no)->first()->cr_amt) ? $trans->where('acc_no', $row->acc_no)->first()->cr_amt : 0;

            $ln['balance'] = ($ln['opening_dr'] + $ln['dr_tr']) - ($ln['opening_cr'] + $ln['cr_tr']);

            $report->push($ln);
        }

        return $report;
    }

}
