<?php

namespace App\Http\Controllers\Accounts\Report;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Trans\Transaction;
use App\Models\Common\UserActivity;
use App\Models\Company\TransCode;
use Carbon\Carbon;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PrintVoucherControllerCO extends Controller
{
    public function index(Request $request)
    {

        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>47015,'user_id'=>$this->user_id
            ],['updated_at'=>Carbon::now()
        ]);

        $category = TransCode::query()->whereBetween('id',[1,9])->pluck('trans_name','trans_code');


        $trans = null;
        $dates = null;

        if(!empty($request['date_from']))
        {

            if($request->ajax())
            {
                $query = $request->get('query');
                if($query != '')
                {
                    $date_from = Carbon::createFromFormat('d-m-Y',$request['date_from'])->format('Y-m-d');
                    $date_to = Carbon::createFromFormat('d-m-Y',$request['date_to'])->format('Y-m-d');

                    $trans = Transaction::query()->where('company_id',$this->company_id)
                        ->whereBetween('trans_date',[$date_from,$date_to])
                        ->where('voucher_no','LIKE', '%'.$query.'%')
                        ->orWhere('trans_desc1','LIKE', '%'.$query.'%')
                        ->orWhere('dr_amt','LIKE', '%'.$query.'%')
                        ->orWhere('cr_amt','LIKE', '%'.$query.'%')
                        ->orWhere('acc_no','LIKE', '%'.$query.'%')
                        ->orWhereHas('account', function (Builder $q) use ($query) {
                            $q->where('acc_name', 'like', '%'.$query.'%');
                        })
                        ->orderBy('trans_date')
                        ->orderBy('voucher_no','ASC')
                        ->with('account')
                        ->with('user')
                        ->get();

                    $vouchers = $trans->unique('voucher_no');

                    $data = array(
                        'trans'  => $trans,
                        'vouchers'  => $vouchers
                    );

                    return response()->json($data);
                }

            }else
            {
                $date_from = Carbon::createFromFormat('d-m-Y',$request['date_from'])->format('Y-m-d');
                $date_to = Carbon::createFromFormat('d-m-Y',$request['date_to'])->format('Y-m-d');
                $trans = Transaction::query()->where('company_id',$this->company_id)
                    ->where('tr_code',$request['trans_code'])
                    ->whereBetween('trans_date',[$date_from,$date_to])->with('code')
                    ->orderBy('trans_date')
                    ->orderBy('voucher_no','ASC')
                    ->get();

                $vouchers = $trans->unique('voucher_no');

                switch($request['action'])
                {
                    case 'preview':
                        return view('accounts.report.transaction.print-voucher-index',compact('vouchers','trans'));
                        break;

                    case 'print':

//                        dd('print');

                        $view = \View::make('accounts.report.transaction.pdf-voucher-print',compact('vouchers','trans'));
                        $html = $view->render();

                        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(216,147), true, 'UTF-8', false);
//                    $pdf = new TCPDF('L', PDF_UNIT, array(105,148), true, 'UTF-8', false);
//                    $pdf::setMargin(0,0,0);


                        $pdf::SetMargins(15, 0, 5,0);
                        $pdf::changeFormat(array(216,147));
                        $pdf::reset();
                        $pdf::AddPage('A');

                        $pdf::writeHTML($html, true, false, true, false, '');
                        $pdf::Output('transactions.pdf');

                        return view('accounts.report.transaction.pdf-daily-transactions');
//                    dd('print');

                        break;

                    default:

                }

            }
        }
        return view('accounts.report.transaction.print-voucher-index',compact('category'));
    }
}
