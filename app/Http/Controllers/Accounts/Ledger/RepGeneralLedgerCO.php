<?php

namespace App\Http\Controllers\Accounts\Ledger;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Trans\Transaction;
use App\Models\Common\UserActivity;
use App\Traits\AccountTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RepGeneralLedgerCO extends Controller
{
    use AccountTrait;

    public function index(Request $request)
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>42055,'user_id'=>$this->user_id
            ]);

        $ledgers = GeneralLedger::query()->where('company_id',$this->company_id)
            ->where('is_group',false)->pluck('acc_name','acc_no');

        if(!empty($request['acc_no']))
        {
            $fromDate = Carbon::createFromFormat('d-m-Y',$request['date_from'])->format('Y-m-d');
            $toDate = Carbon::createFromFormat('d-m-Y',$request['date_to'])->format('Y-m-d');

            $ledger = GeneralLedger::query()->where('company_id',$this->company_id)
                ->where('acc_no',$request['acc_no'])->first();

            $opening_bal = $this->get_account_balance($request['acc_no'],$this->company_id,$fromDate);

            $trans = Transaction::query()->where('company_id',$this->company_id)
                ->where('tr_state',false)->where('acc_no',$request['acc_no'])
                ->whereBetween('trans_date',[$fromDate,$toDate])->get();

            $params = collect([
                'acc_no' => $ledger->acc_no,
                'acc_name' => $ledger->acc_name,
                'from_date' => $request['date_from'],
                'to_date' => $request['date_to'],
                'opening_bal' =>$opening_bal,
                'dr_cr' => $opening_bal > 0 ? 'Debit' : 'Credit',
                ]);

//            dd($params);

            $report = collect();
            $newLine = [];

            foreach ($trans as $row)
            {
                $newLine['voucher_no'] = $row->voucher_no;
                $newLine['tr_code'] = $row->tr_code;
                $newLine['tr_date'] = $row->trans_date;
                $newLine['contra'] = 'Contra';

                $newLine['dr_amt'] = $row->dr_amt;
                $newLine['cr_amt'] = $row->cr_amt;
                $newLine['description'] = $row->trans_desc1;

                $report->push($newLine);

            }

            return view('accounts.report.ledger.rep-general-ledger-index',compact('ledgers','report','params'));

        }

        return view('accounts.report.ledger.rep-general-ledger-index',compact('ledgers'));
    }
}
