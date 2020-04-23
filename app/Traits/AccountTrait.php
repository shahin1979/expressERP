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

    }

}
