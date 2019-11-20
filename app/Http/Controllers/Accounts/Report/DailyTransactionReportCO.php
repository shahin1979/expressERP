<?php

namespace App\Http\Controllers\Accounts\Report;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Trans\Transaction;
use Carbon\Carbon;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;

class DailyTransactionReportCO extends Controller
{
    public function index(Request $request)
    {

        $trans = null;
        $dates = null;

        if(!empty($request['date_from']))
        {
            $date_from = Carbon::createFromFormat('d-m-Y',$request['date_from'])->format('Y-m-d');
            $date_to = Carbon::createFromFormat('d-m-Y',$request['date_to'])->format('Y-m-d');
            $trans = Transaction::query()->where('company_id',$this->company_id)
                ->whereBetween('trans_date',[$date_from,$date_to])
                ->orderBy('trans_date')
                ->orderBy('voucher_no','ASC')
                ->get();

            $dates = $trans->unique('trans_date');

            switch($request['action'])
            {
                case 'preview':
                    break;

                case 'print':

                    $view = \View::make('accounts.report.transaction.pdf-daily-transactions',compact('dates','trans'));
                    $html = $view->render();

                    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);
//                    $pdf = new TCPDF('L', PDF_UNIT, array(105,148), true, 'UTF-8', false);
//                    $pdf::setMargin(0,0,0);


                    $pdf::SetMargins(15, 0, 5,0);

                    $pdf::AddPage();

                    $pdf::writeHTML($html, true, false, true, false, '');
                    $pdf::Output('transactions.pdf');

                    return view('accounts.report.transaction.pdf-daily-transactions');
//                    dd('print');

                    break;

                default:

            }





//            $summ = $trans->where('trans_date','2019-11-20')->sum('dr_amt');
//            dd($summ);
        }
        return view('accounts.report.transaction.daily-transaction-index',compact('trans','dates'));
    }
}
