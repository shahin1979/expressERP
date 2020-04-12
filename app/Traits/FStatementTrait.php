<?php


namespace App\Traits;


use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Statement\StmtLine;
use App\Models\Accounts\Statement\StmtList;
use App\Models\Accounts\Trans\Transaction;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt;

trait FStatementTrait
{
    public function range_value($company_id, $file_no, $report_date)
    {
        $trans = Transaction::query()->where('company_id',$company_id)
            ->where('tr_state',false)
            ->where('trans_date','<=',$report_date)
            ->select(DB::Raw('acc_no, sum(dr_amt - cr_amt) as trans_bal'))
            ->groupBy('acc_no')->get();

        $ledgers = GeneralLedger::query()->where('company_id',$company_id)
            ->where('is_group',false)->get();

        $lines = StmtLine::query()->where('company_id',$company_id)
            ->where('sub_total','<>','')
            ->where('file_no',$file_no)->get();

        $nextFile = 99;

//        if($lines[0]->file_no == 'MA01')
//        {
//            dd('here');
//        }


//        DB::beginTransaction();
//
//        try {

//            StmtLine::query()->where('company_id',$company_id)
//                ->where('file_no',$file_no)
//                ->update(['range_val1'=>0,'range_val2'=>0,'range_val3'=>0, 'print_val'=>0]);

            StmtList::query()->where('company_id',$company_id)
                ->where('file_no',$file_no)
                ->update(['value_date'=>$report_date]);


            foreach ($lines as $row)
            {
                $acc11 = $ledgers->whereBetween('acc_no',[$row->ac11, $row->ac12]);

                foreach ($acc11 as $item)
                {
                    $acc_bal = $trans->where('acc_no',$item->acc_no)->first();

                    if(!empty($acc_bal))
                    {
                        StmtLine::query()->where('id',$row->id)
                            ->increment('range_val1',$acc_bal->trans_bal + $item->start_dr - $item->start_cr);
                    }else{
                        StmtLine::query()->where('id',$row->id)
                            ->increment('range_val1',$item->start_dr - $item->start_cr);
                    }

                }

                $acc22 = $ledgers->whereBetween('acc_no',[$row->ac21, $row->ac22]);

                foreach ($acc22 as $item)
                {
                    $acc_bal = $trans->where('acc_no',$item->acc_no)->first();

                    if(!empty($acc_bal))
                    {
                        StmtLine::query()->where('id',$row->id)
                            ->increment('range_val2',$acc_bal->trans_bal + $item->start_dr - $item->start_cr);
                    }else{
                        StmtLine::query()->where('id',$row->id)
                            ->increment('range_val2',$item->start_dr - $item->start_cr);
                    }
                }

                // Import value update to related line
                if($row->import_line == true)
                {
                    $imp_value = StmtList::query()->where('company_id',$company_id)
                        ->where('file_no',$file_no)->first();

                    StmtLine::query()->where('id',$row->id)->update(['range_val1'=>$imp_value->import_value]);
                }

                StmtLine::query()->where('id',$row->id)->update(['range_val3'=>DB::raw('range_val1 + range_val2')]);
            }

            // Make Negetive Value Line Negetive

            StmtLine::query()->where('negative_value',true)->update(['range_val3'=>DB::raw('range_val3*(-1)')]);

          //  updating sub totals

            $collection = StmtLine::query()->where('company_id',$company_id)
                ->where('file_no',$file_no)
                ->get(); //get stmt data

            $formula = StmtLine::query()->where('company_id',$company_id)
                ->where('file_no',$file_no)
                ->where(DB::Raw('length(formula)'),'>',0)->get();

            foreach ($formula as $sbt)
            {
                $amt = 0;

                $result = str_split(str_replace("+","", $sbt->formula));  ;
                foreach ($result as $value)
                {
                    $subtotal = $collection->where('sub_total',$value)
                        ->where('file_no',$sbt->file_no)
                        ->sum('range_val3');

                    $amt += $subtotal;
                }

                StmtLine::query()->where('company_id',$company_id)
                    ->where('id',$sbt->id)->update(['range_val3'=>$amt]);
            }

            // Update Import File line Value
            $import = StmtList::query()->where('company_id',$company_id)
                ->where('import_file',$file_no)->first();

//                ->update(['import_value'=>100]);

            if(!empty($import))
            {
                $import_value = StmtLine::query()->where('company_id',$company_id)
                    ->where('file_no',$file_no)->where('line_no',$import->import_line)->value('range_val3');

                StmtList::query()->where('company_id',$company_id)
                    ->where('import_file',$file_no)
                    ->update(['import_value'=>$import_value]);

                $nextFile = $import->file_no;

//                dd($import_value);
            }

            // Flag true for calculation done for the file for the date

            StmtList::query()->where('company_id',$company_id)
                ->where('file_no',$file_no)->update(['posted'=>true]);

//        } catch (\Exception $e) {
//            DB::rollBack();
//            $error = $e->getMessage();
//            return $error;
//        }
//
//        DB::commit();

        return $nextFile;
    }

}
