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
            ->where('is_group',false)
            ->orderBy('acc_name')
            ->pluck('acc_name','acc_no');

        if(!empty($request['acc_no']))
        {
            $fromDate = Carbon::createFromFormat('d-m-Y',$request['date_from'])->format('Y-m-d');
            $toDate = Carbon::createFromFormat('d-m-Y',$request['date_to'])->format('Y-m-d');
            $acc_no = $request['acc_no'];

            $ledger = GeneralLedger::query()->where('company_id',$this->company_id)
                ->where('acc_no',$request['acc_no'])->first();

            $opening_bal = $this->get_account_opening_balance($request['acc_no'],$this->company_id,$fromDate);

            $data = Transaction::query()->where('company_id',$this->company_id)
                ->where('tr_state',false)
                ->whereIn('voucher_no',function ($query) use($acc_no,$fromDate,$toDate) {
                    $query->select('voucher_no')
                        ->from('transactions')
                        ->where('tr_state',false)
                        ->whereBetween('trans_date',[$fromDate,$toDate])
                        ->where('acc_no', $acc_no);
                })->with('account')
                ->orderBy('voucher_no')
                ->get();

            $trans = $data->where('acc_no',$acc_no);

            $params = collect([
                'acc_no' => $ledger->acc_no,
                'acc_name' => $ledger->acc_name,
                'from_date' => $request['date_from'],
                'to_date' => $request['date_to'],
                'opening_bal' =>$opening_bal,
                'dr_cr' => $opening_bal > 0 ? 'Debit' : 'Credit',
                ]);

            $report = collect();
            $newLine = [];

            $contra = collect();
            $contraLine = [];

            foreach ($trans as $row)
            {
                $newLine['voucher_no'] = $row->voucher_no;
                $newLine['tr_code'] = $row->tr_code;
                $newLine['tr_date'] = $row->trans_date;

                $newLine['contra'] = 'Contra';

                $newLine['dr_amt'] = $row->dr_amt;
                $newLine['cr_amt'] = $row->cr_amt;
                $newLine['description'] = $row->trans_desc1;

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

            return view('accounts.report.ledger.rep-general-ledger-index',compact('ledgers','report','params','contra'));

        }

        return view('accounts.report.ledger.rep-general-ledger-index',compact('ledgers'));
    }
}
