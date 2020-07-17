<?php

namespace App\Http\Controllers\Accounts\Report;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Previous\GeneralLedgerBackup;
use App\Models\Accounts\Previous\TransactionBackup;
use App\Models\Accounts\Trans\Transaction;
use App\Models\Common\UserActivity;
use App\Traits\AccountTrait;
use Carbon\Carbon;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RepGeneralLedgerCO extends Controller
{
    use AccountTrait;

    public function index(Request $request)
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>47025,'user_id'=>$this->user_id
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
                ->where('tr_state',false)//->where('voucher_no',201920000618)
                ->whereIn('voucher_no',function ($query) use($acc_no,$fromDate,$toDate) {
                    $query->select('voucher_no')
                        ->from('transactions')
                        ->where('tr_state',false)
                        ->whereBetween('trans_date',[$fromDate,$toDate])
                        ->where('acc_no', $acc_no);
                })->with('account')
                ->orderBy('trans_date')
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
            $newLine['balance'] = $opening_bal;

            foreach ($trans as $row)
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

//                    $contraData = $contraData->unique('acc_no');

                    foreach ($contraData as $line)
                    {
                        $contraLine['id'] = $line->id;
                        $contraLine['acc_no'] = $line->acc_no;
                        $contraLine['acc_name'] = count($contraData) > 1 ? $line->account->acc_name.' : '.$line->cr_amt : $line->account->acc_name;
                        $contraLine['voucher_no'] = $line->voucher_no;
                        $contraLine['trans_amt'] = $line->cr_amt;
                        $exist = $contra->where('voucher_no',$line->voucher_no)->where('acc_no',$line->acc_no);
//                        dd($exist);
                        if(!count($exist) > 0)
                        {
                            $contra->push($contraLine);
                        }

                    }
                }

                // Contra Account for Credit Transactions

                if($row->cr_amt > 0)
                {
                    $contraData = $data->where('voucher_no',$row->voucher_no)
                        ->where('dr_amt','>',0);

                    foreach ($contraData as $line)
                    {
                        $contraLine['id'] = $line->id;
                        $contraLine['acc_no'] = $line->acc_no;
                        $contraLine['acc_name'] = count($contraData) > 1 ? $line->account->acc_name.' : '.$line->dr_amt : $line->account->acc_name;
                        $contraLine['voucher_no'] = $line->voucher_no;
                        $contraLine['trans_amt'] = $line->dr_amt;
                        $exist = $contra->where('voucher_no',$line->voucher_no)->where('acc_no',$line->acc_no);
                        if(!count($exist) > 0)
                        {
                            $contra->push($contraLine);
                        }
//                        $contra->push($contraLine);
                    }
                }
                $report->push($newLine);

            }

            foreach ($report as $multy)
            {
                $v_contra = $contra->where('voucher_no',$multy['voucher_no']);

            }

            switch ($request['action'])
            {
                case 'preview':
                    return view('accounts.report.ledger.rep-general-ledger-index',compact('ledgers','report','params','contra'));
                    break;

                case 'print':
                    $view = \View::make('accounts.report.ledger.pdf.print-general-ledger',compact('ledgers','report','params','contra'));
                    $html = $view->render();

                    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

                    $pdf::SetMargins(15, 5, 10,10);

                    $pdf::AddPage();

                    $pdf::writeHTML($html, true, false, true, false, '');
                    $pdf::Output('GeneralLedger.pdf');
            }





        }

        return view('accounts.report.ledger.rep-general-ledger-index',compact('ledgers'));
    }

    public function getAccount($id)
    {
        $data = GeneralLedgerBackup::query()->where('fiscal_year',$id)
            ->where('company_id',$this->company_id)
            ->where('is_group',false)
            ->select ('acc_name','acc_no')
            ->orderBy('acc_name','asc')
            ->pluck('acc_name','acc_no');

        return response()->json($data);

//        return json_encode($data);
    }

    public function previousGenLedgerIndex(Request $request)
    {

        if(!empty($request['report_year']))
        {
            $fromDate = Carbon::createFromFormat('d-m-Y',$request['date_from'])->format('Y-m-d');
            $toDate = Carbon::createFromFormat('d-m-Y',$request['date_to'])->format('Y-m-d');
            $acc_no = $request['acc_no'];
            $fiscal = $request['report_year'];

            $ledger = GeneralLedgerBackup::query()->where('company_id',$this->company_id)
                ->where('acc_no',$request['acc_no'])
                ->where('fiscal_year',$fiscal)->first();

            $opening_bal = $this->get_previous_account_opening_balance($request['acc_no'],$this->company_id,$fromDate,$fiscal);

            $data = TransactionBackup::query()->where('company_id',$this->company_id)
                ->where('tr_state',false)
                ->where('fiscal_year',$fiscal)
                ->whereIn('voucher_no',function ($query) use($acc_no,$fromDate,$toDate,$fiscal) {
                    $query->select('voucher_no')
                        ->from('transaction_backups')
                        ->where('tr_state',false)
                        ->where('fiscal_year',$fiscal)
                        ->whereBetween('trans_date',[$fromDate,$toDate])
                        ->where('acc_no', $acc_no);
                })->with('account')
                ->orderBy('trans_date')
                ->orderBy('voucher_no')
                ->get();

//            dd($data);

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
            $newLine['balance'] = $opening_bal;

            foreach ($trans as $row)
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

            return view('accounts.report.previous.previous-general-ledger-index',compact('report','params','contra'));

        }

        return view('accounts.report.previous.previous-general-ledger-index');
    }
}
