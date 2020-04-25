<?php


namespace App\Traits;


use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Previous\GeneralLedgerBackup;
use App\Models\Accounts\Previous\TransactionBackup;
use App\Models\Accounts\Trans\Transaction;
use Illuminate\Support\Facades\DB;

trait AccountTrait
{
    public function get_account_opening_balance($acc_no, $company_id, $from_date)
    {
        $opening = Transaction::query()->where('company_id',$company_id)
            ->where('tr_state',false)->where('acc_no',$acc_no)
            ->where('trans_date','<',$from_date)
            ->select(DB::Raw('acc_no, sum(dr_amt) as open_dr, sum(cr_amt) as open_cr'))->groupBy('acc_no')
            ->first();

        $gl = GeneralLedger::query()->where('company_id',$this->company_id)
            ->where('acc_no',$acc_no)->first();

        $opening_bal = isset($opening) ? ($opening->open_dr + $gl->start_dr) - ($opening->open_cr + $gl->start_cr) : $gl->start_dr - $gl->open_cr;

        return $opening_bal;
    }

    public function get_account_date_balance($acc_no, $company_id, $from_date)
    {
        $trans = Transaction::query()->where('company_id',$company_id)
            ->where('tr_state',false)->where('acc_no',$acc_no)
            ->whereDate('trans_date','<=',$from_date)
            ->select(DB::Raw('acc_no, sum(dr_amt) as open_dr, sum(cr_amt) as open_cr'))->groupBy('acc_no')
            ->first();

        $gl = GeneralLedger::query()->where('company_id',$this->company_id)
            ->where('acc_no',$acc_no)->first();

        $balance = isset($trans) ? ($trans->open_dr + $gl->start_dr) - ($trans->open_cr + $gl->start_cr) : $gl->start_dr - $gl->open_cr;

        return $balance;
    }


    public function get_previous_account_opening_balance($acc_no, $company_id, $from_date,$fiscal)
    {
        $opening = TransactionBackup::query()->where('company_id',$company_id)
            ->where('tr_state',false)->where('acc_no',$acc_no)
            ->where('trans_date','<',$from_date)
            ->where('fiscal_year',$fiscal)
            ->select(DB::Raw('acc_no, sum(dr_amt) as open_dr, sum(cr_amt) as open_cr'))->groupBy('acc_no')
            ->first();

        $gl = GeneralLedgerBackup::query()->where('company_id',$this->company_id)
            ->where('fiscal_year',$fiscal)
            ->where('acc_no',$acc_no)->first();

        $opening_bal = isset($opening) ? ($opening->open_dr + $gl->start_dr) - ($opening->open_cr + $gl->start_cr) : $gl->start_dr - $gl->open_cr;

        return $opening_bal;
    }

    public function get_account_name($company_id,$acc_no)
    {
        $ledger = GeneralLedger::query()->where('company_id',$company_id)
            ->where('acc_no',$acc_no)->first();

        return $ledger->acc_name;
    }

    public function get_account_ledger($company_id,$acc_no,$from_date,$to_date)
    {

        $json = new \stdClass;

        $opening_bal = $this->get_account_opening_balance($acc_no,$this->company_id,$from_date);

        $data = Transaction::query()->where('company_id',$company_id)
            ->where('tr_state',false)
            ->whereIn('voucher_no',function ($query) use($acc_no,$from_date,$to_date) {
                $query->select('voucher_no')
                    ->from('transactions')
                    ->where('tr_state',false)
                    ->whereBetween('trans_date',[$from_date,$to_date])
                    ->where('acc_no', $acc_no);
            })->with('account')
            ->orderBy('trans_date')
            ->orderBy('voucher_no')
            ->get();

        $transactions = $data->where('acc_no',$acc_no);

        $report = collect();
        $newLine = [];

        $contra = collect();
        $contraLine = [];
        $newLine['balance'] = $opening_bal;

        foreach ($transactions as $row)
        {
            $newLine['voucher_no'] = $row->voucher_no;
            $newLine['tr_code'] = $row->tr_code;
            $newLine['tr_date'] = $row->trans_date;
            $newLine['contra'] = 'Contra';
            $newLine['dr_amt'] = $row->dr_amt;
            $newLine['cr_amt'] = $row->cr_amt;
            $newLine['description'] = $row->trans_desc1;
            $newLine['balance'] = $newLine['balance'] + $row->dr_amt - $row->cr_amt;

            // Contra Account for Debit Transactions

            if($row->dr_amt > 0)
            {
                $contraData = $data->where('voucher_no',$row->voucher_no)
                    ->where('cr_amt','>',0);

                foreach ($contraData as $line)
                {
                    $contraLine['acc_no'] = $line->acc_no;
                    $contraLine['acc_name'] = count($contraData) > 1 ? $line->account->acc_name.' : '.$line->cr_amt : $line->account->acc_name;
                    $contraLine['voucher_no'] = $line->voucher_no;
                    $contraLine['trans_amt'] = $line->cr_amt;
                    $contra->push($contraLine);
                }
            }

            // Contra Account for Credit Transactions

            if($row->cr_amt > 0)
            {
                $contraData = $data->where('voucher_no',$row->voucher_no)
                    ->where('dr_amt','>',0);

                foreach ($contraData as $line)
                {
                    $contraLine['acc_no'] = $line->acc_no;
                    $contraLine['acc_name'] = count($contraData) > 1 ? $line->account->acc_name.' : '.$line->dr_amt : $line->account->acc_name;
                    $contraLine['voucher_no'] = $line->voucher_no;
                    $contraLine['trans_amt'] = $line->dr_amt;
                    $contra->push($contraLine);
                }
            }


            $report->push($newLine);

        }

        $json->report = $report;
        $json->contra = $contra;
        return json_encode($json);
    }

}
