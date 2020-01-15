<?php

namespace App\Http\Controllers\Accounts\Statement;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Statement\StmtLine;
use App\Models\Accounts\Statement\StmtList;
use App\Models\Accounts\Trans\Transaction;
use App\Traits\FStatementTrait;
use Carbon\Carbon;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrintStatementCO extends Controller
{
    use FStatementTrait;

    public function index(Request $request)
    {
//        $formula = StmtLine::query()->where('company_id',$this->company_id)
//            ->where('file_no','BL01')
//            ->where(DB::Raw('length(formula)'),'>',0)->get();
//
//        $formula->map(function($item, $key) {
//            return str_replace('+', '', $item->formula);
//        });


//        dd($formula);

        $fileList = StmtList::query()->where('company_id',$this->company_id)
            ->pluck('file_desc','file_no');

        return view('accounts.statement.print-statement-index',compact('fileList'));
    }

    public function prepare(Request $request)
    {
        StmtLine::query()->where('company_id',$this->company_id)
            ->update(['range_val1'=>0,'range_val2'=>0,'range_val3'=>0, 'print_val'=>0]);

        StmtList::query()->where('company_id',$this->company_id)
            ->update(['posted'=>false]);

        $report_date = Carbon::createFromFormat('d-m-Y',$request['statement_date'])->format('Y-m-d') ;

        $filesWithoutImport = StmtList::query()->where('company_id',$this->company_id)
//            ->where('file_no','MA01')
            ->where('import_line',0)->get();

//        dd($filesWithoutImport);

        DB::beginTransaction();

        try {

            foreach ($filesWithoutImport as $row) {

                $output = $this->range_value($this->company_id, $row->file_no, $report_date);

            }

        }catch (\Exception $e) {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();


//        dd($output);

        $filesWithImport = StmtList::query()->where('company_id',$this->company_id)
            ->where('import_line','>',0)->get();





//        DB::beginTransaction();
//
//        try {
//
//            StmtLine::query()->where('company_id',$this->company_id)
//                ->update(['range_val1'=>0,'range_val2'=>0]);
//
//            StmtList::query()->where('company_id',$this->company_id)
//                ->update(['value_date'=>$report_date]);
//
//
//            foreach ($lines as $row)
//            {
//                $acc11 = $ledgers->whereBetween('acc_no',[$row->ac11, $row->ac12]);
//
//                foreach ($acc11 as $item)
//                {
//                    $acc_bal = $trans->where('acc_no',$item->acc_no)->first();
//
//                    if(!empty($acc_bal))
//                    {
//                        StmtLine::query()->where('id',$row->id)
//                            ->increment('range_val1',$acc_bal->trans_bal + $item->start_dr - $item->start_cr);
//                    }
//
//                }
//
//
//                $acc22 = $ledgers->whereBetween('acc_no',[$row->ac21, $row->ac22]);
//
//                foreach ($acc22 as $item)
//                {
//                    $acc_bal = $trans->where('acc_no',$item->acc_no)->first();
//
//                    if(!empty($acc_bal))
//                    {
//                        StmtLine::query()->where('id',$row->id)
//                            ->increment('range_val2',$acc_bal->trans_bal + $item->start_dr - $item->start_cr);
//                    }
//
//                }
//
//                StmtLine::query()->where('id',$row->id)->update(['range_val3'=>DB::raw('range_val1 + range_val2')]);
//
//            }
//
//            $collection = StmtLine::query()->where('company_id',$this->company_id)->get(); //get stmt data
//
//            $formula = StmtLine::query()->where('company_id',$this->company_id)
//                ->where(DB::Raw('length(formula)'),'>',0)->get();
//
//            foreach ($formula as $sbt)
//            {
//                $amt = 0;
//
//                $result = str_split(str_replace("+","", $sbt->formula));  ;
//                    foreach ($result as $value)
//                    {
//                        $subtotal = $collection->where('sub_total',$value)
//                            ->where('file_no',$sbt->file_no)
//                            ->sum('range_val3');
//
//                        $amt += $subtotal;
//                    }
//
//                    StmtLine::query()->where('company_id',$this->company_id)
//                        ->where('id',$sbt->id)->update(['range_val3'=>$amt]);
//            }
//
//
//
//        } catch (\Exception $e) {
//            DB::rollBack();
//            $error = $e->getMessage();
//            return response()->json(['error' => $error], 404);
//        }
//
//        DB::commit();

        return response()->json(['success'=>$report_date],200);
    }

    public function show(Request $request)
    {

        $statement = StmtList::query()->where('company_id',$this->company_id)
            ->where('file_no',$request['input_file'])->first();

//        if($statement->import_file)



        StmtLine::query()->where('company_id',$this->company_id)
            ->where('file_no',$request['input_file'])->update(['print_val'=>DB::raw('range_val3')]);

        $data = StmtLine::query()->where('company_id',$this->company_id)
            ->where('file_no',$request['input_file'])
            ->orderBy('line_no','ASC')
            ->get();



        $view = \View::make('accounts.statement.pdf-financial-statement',compact('data','statement'));
        $html = $view->render();

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);
//                    $pdf = new TCPDF('L', PDF_UNIT, array(105,148), true, 'UTF-8', false);
//                    $pdf::setMargin(0,0,0);


        $pdf::SetMargins(15, 5, 5,10);

        $pdf::AddPage();

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('statement.pdf');

    }
}
