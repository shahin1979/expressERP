<?php

namespace App\Http\Controllers\Accounts\Statement;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Statement\StmtLine;
use App\Models\Accounts\Statement\StmtList;
use App\Models\Accounts\Trans\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrintStatementCO extends Controller
{
    public function index()
    {
        $fileList = StmtList::query()->where('company_id',$this->company_id)
            ->pluck('file_desc','file_no');

        return view('accounts.statement.print-statement-index',compact('fileList'));
    }

    public function prepare(Request $request)
    {
        $report_date = Carbon::createFromFormat('d-m-Y',$request['statement_date'])->format('Y-m-d') ;

        $trans = Transaction::query()->where('company_id',$this->company_id)
            ->where('tr_state',false)
            ->where('trans_date','<=',$report_date)
            ->select(DB::Raw('acc_no, sum(dr_amt - cr_amt) as trans_bal'))
            ->groupBy('acc_no')->get();

        $ledgers = GeneralLedger::query()->where('company_id',$this->company_id)->get();

//        dd($trans->where('acc_no','10112102')->first());

        $lines = StmtLine::query()->where('company_id',$this->company_id)
            ->where('sub_total','<>','')
//            ->where('file_no','BL01')
            ->get();

        $collection = collect();
        $accLine = [];


        foreach ($lines as $row)
        {
            $acc11 = $ledgers->whereBetween('acc_no',[$row->ac11, $row->acc12])->get('acc_no');

            foreach ($acc11 as $item)
            {
                $acc_bal = $trans->where('acc_no',$item->acc_no)->first();
                StmtLine::query()->where('id',$row->id)
                    ->increment('range_val1',$acc_bal->trans_bal);
            }

        }


        return response()->json(['success'=>$report_date],200);
    }

    public function show(Request $request)
    {


        dd($request['input_file']);
    }
}
