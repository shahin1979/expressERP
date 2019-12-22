<?php


namespace App\Traits;


use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Trans\Transaction;
use Illuminate\Support\Facades\DB;

trait AccountTrait
{
    public function get_account_balance($acc_no, $company_id, $from_date, $to_date)
    {
        $ledger = GeneralLedger::query()->where('company_id',$company_id)
            ->where('acc_no',$acc_no)->first();

         $trans_before_date = Transaction::query()->where('company_id',$company_id)
                    ->where('acc_no',$acc_no)  ->where('tr_state',false)
                    ->whereDate('tr_date','<',$from_date)
                    ->sum(DB::Raw('dr_amt-cr_amt'));

        return $acc_no;
    }

}
