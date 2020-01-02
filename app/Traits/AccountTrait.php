<?php


namespace App\Traits;


use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Trans\Transaction;
use Illuminate\Support\Facades\DB;

trait AccountTrait
{
    public function get_account_balance($acc_no, $company_id, $from_date)
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

}
