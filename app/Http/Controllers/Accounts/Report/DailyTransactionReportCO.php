<?php

namespace App\Http\Controllers\Accounts\Report;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Trans\Transaction;
use Carbon\Carbon;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class DailyTransactionReportCO extends Controller
{
    public function index(Request $request)
    {

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

                    $dates = $trans->unique('trans_date');

                    $data = array(
                        'trans'  => $trans,
                        'dates'  => $dates
                    );

                    return response()->json($data);
                }

            }else
                    {
                    $date_from = Carbon::createFromFormat('d-m-Y',$request['date_from'])->format('Y-m-d');
                    $date_to = Carbon::createFromFormat('d-m-Y',$request['date_to'])->format('Y-m-d');
//                    $company_id = $this->company_id;
                    $trans = Transaction::query()->where('company_id',$this->company_id)
                        ->whereBetween('trans_date',[$date_from,$date_to])
                        ->with(['purchase'=>function($q)  {
                            // Query the name field in status table
                            $q->where('company_id', $this->company_id);
                        }])
                        ->orderBy('trans_date')
                        ->orderBy('voucher_no','ASC')
                        ->get();

//                    dd($trans);

                    $dates = $trans->unique('trans_date');
                    $params = collect();

                    switch($request['action'])
                    {
                        case 'preview':

                            $params['date_from'] = $date_from;
                            $params['date_to'] = $date_to;
                            $params['dates'] = $dates;

                            /// USING THIS PORTION FOR PAGINATION

//                            $trans = $this->paginate($trans,5);
//                            $trans->appends($request->all())
//                                ->setPath('dailyTransactionIndex');

                        /////////////////////////////////////////////////

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

                }
            }
        return view('accounts.report.transaction.daily-transaction-index',compact('trans','dates'));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

}
