<?php


namespace App\Traits;


use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Statement\StmtLine;
use App\Models\Accounts\Statement\StmtList;
use App\Models\Accounts\Trans\Transaction;
use App\Models\Company\Relationship;
use App\Models\Company\TransCode;
use App\Models\Human\Admin\Location;
use App\Models\Inventory\Movement\Purchase;
use App\Models\Inventory\Movement\Requisition;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ProductMO;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

trait MigrationTrait
{
    public function matinDB($company_id)
    {
        $connection = DB::connection('mcottondb');

        $ledgers = $connection->table('gl_accounts')
            ->where('comp_code',12)
            ->orderBy('ldgrCode','ASC')
            ->get();

        ini_set('max_execution_time', 600);

        DB::beginTransaction();

        try {

            if (Config::get('database.default') == 'mysql') {

                DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

                DB::statement('TRUNCATE TABLE general_ledgers;');
                DB::statement('TRUNCATE TABLE transactions;');
//                DB::statement('TRUNCATE TABLE categories;');
//                DB::statement('TRUNCATE TABLE sub_categories;');
//                DB::statement('TRUNCATE TABLE products;');

                DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
            }

            if (Config::get('database.default') == 'pgsql') {

                DB::statement('TRUNCATE TABLE general_ledgers RESTART IDENTITY CASCADE;');
                DB::statement('TRUNCATE TABLE transactions RESTART IDENTITY CASCADE;');
//                DB::statement('TRUNCATE TABLE categories RESTART IDENTITY CASCADE;');
//                DB::statement('TRUNCATE TABLE sub_categories RESTART IDENTITY CASCADE;');
//                DB::statement('TRUNCATE TABLE products RESTART IDENTITY CASCADE;');
            }

            $count = 0;

            // Migrate Gl_accounts Table

            foreach ($ledgers as $row)
            {

                $string = preg_replace('/[\*]+/', '', $row->accName);

                $type = $row->type_code == 1 ? 11 :
                    ($row->type_code == 3 ? 52 :
                        ($row->type_code == 5 ? 33 :
                            ($row->type_code == 6 ? 34 :
                                ($row->type_code == 7 ? 41 :
                                    ($row->type_code == 8 ? 42 :
                                        ($row->type_code == 9 ? 21 :
                                            ($row->type_code == 10 ? 22 :
                                                ($row->type_code == 11 ? 12 :
                                                    ($row->type_code == 12 ? 52 : '')))))))));

                GeneralLedger::query()->updateOrCreate(
                    ['company_id'=>$company_id,'acc_no'=>$row->accNo],
                    [
                        'ledger_code'=>$row->ldgrCode,
                        'acc_name'=>$string,
                        'acc_type'=>$row->accType,
                        'type_code'=>$type,
                        'acc_range'=>$row->accr_no,
                        'is_group'=>$row->is_group,
                        'start_dr'=>$row->start_dr,
                        'start_cr'=>$row->start_cr,
//                        'cyr_dr'=>$row->dr_00, //corrent year posted trans
//                        'cyr_cr'=>$row->cr_00, //corrent year posted trans
//                        'dr_00'=>$row->dr_00,
//                        'cr_00'=>$row->cr_00,
//                        'curr_bal' => ($row->start_dr + $row->dr_00) - ($row->start_cr + $row->cr_00),
                        'currency'=>'BDT',
                        'user_id'=>Auth::id()
                    ]
                );

                $count ++;
            }

            //Update Group ba

//            $sum = GeneralLedger::query()->where('company_id',$company_id)
//                ->where('is_group',false)
//                ->select('ledger_code',DB::raw('sum(start_dr) as start_dr, sum(start_cr) as start_cr,
//                                    sum(cyr_dr) as cyr_dr, sum(cyr_cr) as cyr_cr, sum(curr_bal) as curr_bal'))
//                ->groupBy('ledger_code')
//                ->get();
//
//            foreach ($sum as $item)
//            {
//                GeneralLedger::query()->where('company_id',$company_id)
//                    ->where('is_group',true)
//                    ->where('ledger_code',$item->ledger_code)
//                    ->update([
//                        'start_dr'=>$item->start_dr,
//                        'start_cr'=>$item->start_cr,
//                        'cyr_dr'=>$item->cyr_dr,
//                        'cyr_cr'=>$item->cyr_cr,
//                        'curr_bal'=>$item->curr_bal
//                        ]);
//            }

            // Migrate Transactions Table

            $transactions = $connection->table('transactions')
                ->where('comp_code',12)->get();


            $data = $connection->table('transactions')
                ->select(DB::Raw('distinct voucher_no, j_code'))
                ->where('comp_code',12)
//                ->where('trans_date','<','2019-07-10')
//                ->where('voucher_no',5100766)
                ->get();


            foreach ($data as $trans)
            {
                $jcode = $trans->j_code == 'CP' ? 'PM' :
                    ($trans->j_code == 'BP' ? 'PM' :
                        ($trans->j_code == 'CR' ? 'RC' :
                            ($trans->j_code == 'BR' ? 'RC' :
                                ($trans->j_code == 'JV' ? 'JV' :
                                    ($trans->j_code == 'PR' ? 'PR' :
                                        ($trans->j_code == 'SL' ? 'SL' :
                                            ($trans->j_code == 'ST' ? 'DC' :''
                    )))))));

                $tr_code =  TransCode::query()->where('company_id',$company_id)
                    ->where('trans_code',$jcode)
                    ->where('fiscal_year','2019-2020')
                    ->lockForUpdate()->first();

                $voucher_no = $tr_code->last_trans_id;

                $trans_all = $connection->table('transactions')
                    ->where('comp_code',12)
                    ->where('voucher_no',$trans->voucher_no)
                    ->get();

//                dd($trans_all);

                foreach ($trans_all as $item)
                {
                    $sl = Carbon::parse($item->trans_date)->format('m');
                    $fp_no = get_fp_from_month_sl($sl, $this->company_id);
                    $var = str_pad($fp_no,2,"0",STR_PAD_LEFT);

                    if($item->acc_cr != 'JV')
                    {
                        Transaction::query()->insert([
                            'company_id'=>$company_id,
                            'period'=>$item->period,
                            'tr_code'=>$jcode,
                            'trans_type_id'=>6,
                            'fp_no'=>$fp_no,
                            'ref_no'=>$item->ref_no,
                            'cheque_no'=>$item->cheque_no,
                            'cost_center'=>$item->cost_center,
                            'trans_id'=>$item->trans_id ,
                            'trans_group_id'=>$item->trans_grp_id ,
                            'trans_date'=>$item->trans_date,
                            'voucher_no'=>$voucher_no,
                            'acc_no'=>$item->acc_cr,
                            'dr_amt'=>0,
                            'cr_amt'=>$item->trans_amt,
                            'trans_amt'=>$item->trans_amt,
                            'contra_acc'=>$item->acc_dr == 'JV' ? '' : $item->acc_dr,
                            'currency'=>'BDT',
                            'fiscal_year'=>'2019-2020',
                            'trans_desc1'=>$item->trans_desc1,
                            'trans_desc2'=>$item->trans_desc2,
                            'post_flag'=>true,
                            'authorizer_id'=>Auth::id(),
                            'post_date'=>$item->trans_date,
                            'user_id'=>Auth::id(),
                        ]);

                        GeneralLedger::query()->where('company_id',$company_id)
                            ->where('acc_no',$item->acc_cr)
                            ->increment('cr_'.$var, $item->trans_amt);

                        GeneralLedger::query()->where('company_id',$company_id)
                            ->where('acc_no',$item->acc_cr)
                            ->increment('cyr_cr', $item->trans_amt);

                        GeneralLedger::query()->where('company_id',$company_id)
                            ->where('acc_no',$item->acc_cr)
                            ->increment('cr_00', $item->trans_amt);

                        GeneralLedger::query()->where('company_id',$company_id)
                            ->where('ledger_code',substr($item->acc_cr,0,3))
                            ->where('is_group',true)
                            ->increment('cr_'.$var, $item->trans_amt);

                        GeneralLedger::query()->where('company_id',$company_id)
                            ->where('ledger_code',substr($item->acc_cr,0,3))
                            ->where('is_group',true)
                            ->increment('cyr_cr', $item->trans_amt);

                        GeneralLedger::query()->where('company_id',$company_id)
                            ->where('ledger_code',substr($item->acc_cr,0,3))
                            ->where('is_group',true)
                            ->increment('cr_00', $item->trans_amt);
                    }

                    if($item->acc_dr != 'JV')
                    {
                        Transaction::query()->insert([
                            'company_id'=>$company_id,
                            'period'=>$item->period,
                            'tr_code'=>$jcode,
                            'trans_type_id'=>6,
                            'fp_no'=>$fp_no,
                            'ref_no'=>$item->ref_no,
                            'cheque_no'=>$item->cheque_no,
                            'cost_center'=>$item->cost_center,
                            'trans_id'=>$item->trans_id ,
                            'trans_group_id'=>$item->trans_grp_id ,
                            'trans_date'=>$item->trans_date,
                            'voucher_no'=>$voucher_no,
                            'acc_no' => $item->acc_dr,
                            'dr_amt'=>$item->trans_amt,
                            'cr_amt'=>0,
                            'trans_amt'=>$item->trans_amt,
                            'contra_acc'=>$item->acc_cr == 'JV' ? '' : $item->acc_cr,
                            'currency'=>'BDT',
                            'fiscal_year'=>'2019-2020',
                            'trans_desc1'=>$item->trans_desc1,
                            'trans_desc2'=>$item->trans_desc2,
                            'post_flag'=>true,
                            'authorizer_id'=>Auth::id(),
                            'post_date'=>$item->trans_date,
                            'user_id'=>Auth::id(),
                        ]);

//                        $var = str_pad($item->fp_no,2,"0",STR_PAD_LEFT);

                        GeneralLedger::query()->where('company_id',$company_id)
                            ->where('acc_no',$item->acc_dr)
                            ->increment('dr_'.$var, $item->trans_amt);

                        GeneralLedger::query()->where('company_id',$company_id)
                            ->where('acc_no',$item->acc_dr)
                            ->increment('dr_00', $item->trans_amt);

                        GeneralLedger::query()->where('company_id',$company_id)
                            ->where('acc_no',$item->acc_dr)
                            ->increment('cyr_dr', $item->trans_amt);


                        GeneralLedger::query()->where('company_id',$company_id)
                            ->where('ledger_code',substr($item->acc_dr,0,3))
                            ->where('is_group',true)
                            ->increment('dr_'.$var, $item->trans_amt);

                        GeneralLedger::query()->where('company_id',$company_id)
                            ->where('ledger_code',substr($item->acc_dr,0,3))
                            ->where('is_group',true)
                            ->increment('cyr_dr', $item->trans_amt);

                        GeneralLedger::query()->where('company_id',$company_id)
                            ->where('ledger_code',substr($item->acc_dr,0,3))
                            ->where('is_group',true)
                            ->increment('dr_00', $item->trans_amt);
                    }
                }

//              Update Last Voucher No

                TransCode::query()->where('company_id',$this->company_id)
                    ->where('trans_code',$jcode)
                    ->increment('last_trans_id');

            }

            // Update Current Balance Column

            $head_sum = GeneralLedger::query()->where('company_id',$company_id)->get();

            foreach ($head_sum as $sum) {
                GeneralLedger::query()->where('company_id',$company_id)
                    ->where('acc_no', $sum->acc_no)
                    ->update(['curr_bal'=>($sum->start_dr + $sum->dr_00) - ($sum->start_cr + $sum->cr_00)]);
            }



        }catch (\Exception $e)
        {
            DB::rollBack();
            dd($e->getMessage());
            $error = $e->getMessage();
            return redirect()->back()->with('error','Not Saved '.$error);
        }
        DB::commit();

        return $count;


    }


    public function mumanuDB($company_id)
    {
        $connection = DB::connection('mumanudb');

        $ledgers = $connection->table('gl_accounts')
            ->where('comp_code',15)
            ->orderBy('ldgrCode','ASC')
            ->get();

        DB::beginTransaction();

        try {

//            if (Config::get('database.default') == 'mysql') {

//                DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

//                DB::statement('TRUNCATE TABLE general_ledgers;');
//                DB::statement('TRUNCATE TABLE categories;');
//                DB::statement('TRUNCATE TABLE sub_categories;');
//                DB::statement('TRUNCATE TABLE products;');

//                DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
//            }

            $count = 0;

            foreach ($ledgers as $row)
            {

                $string = preg_replace('/[\*]+/', '', $row->accName);

                $type = $row->type_code == 1 ? 11 :
                    ($row->type_code == 2 ? 12 :
                    ($row->type_code == 3 ? 52 :
                        ($row->type_code == 5 ? 33 :
                            ($row->type_code == 6 ? 34 :
                                ($row->type_code == 7 ? 41 :
                                    ($row->type_code == 8 ? 42 :
                                        ($row->type_code == 9 ? 21 :
                                            ($row->type_code == 10 ? 22 :
                                                ($row->type_code == 11 ? 12 :
                                                    ($row->type_code == 12 ? 52 : ''))))))))));

                GeneralLedger::query()->updateOrCreate(
                    ['company_id'=>$company_id,'acc_no'=>$row->accNo],
                    [
                        'ledger_code'=>$row->ldgrCode,
                        'acc_name'=>$string,
                        'acc_type'=>$row->accType,
                        'type_code'=>$type,
                        'acc_range'=>$row->accr_no,
                        'is_group'=>$row->is_group,
                        'curr_bal' => ($row->start_dr + $row->dr_00) - ($row->start_cr + $row->cr_00),
                        'currency'=>'BDT',
                        'user_id'=>Auth::id()
                    ]
                );

                $count ++;
            }
        }catch (\Exception $e)
        {
            DB::rollBack();
            dd($e->getMessage());
            $error = $e->getMessage();
            return redirect()->back()->with('error','Not Saved '.$error);
        }
        DB::commit();

        return $count;


    }

    public function MTRequisition($company_id)
    {
        ini_set('max_execution_time', 600);

        $connection = DB::connection('mcottondb');

        DB::statement('TRUNCATE TABLE locations RESTART identity CASCADE;');
        DB::statement('TRUNCATE TABLE requisitions RESTART identity CASCADE;');

//        DB::statement('ALTER TABLE requisitions ADD extra_field VARCHAR(150);');


        // Test Area

        $departments = $connection->table('item_departments')->get();

        $location = Collect([]);
        $newLine = [];

        $count = 0;

        foreach ($departments as $row)
        {

            $newLine['company_id'] = $company_id;
            $newLine['location_type'] = 'F';
            $newLine['name'] = $row->deptName;
            $newLine['dept_code'] = $row->deptCode;
            $newLine['user_id'] = Auth::id();

            $new_location = Location::query()->create($newLine);

            $newLine['id'] = $new_location->id;
            $location->push($newLine);

            $count ++;
        }


        $requisitions = $connection->table('item_requisitions')->get();
        $collection = $requisitions->unique('reqRefNo');

        $req_sl = 40000001;

        foreach ($collection as $row)
        {

            if($row->reqDate < '2019-07-01')
            {
                $req_no = '2018'.$req_sl;
            }else
            {
                $tr_code =  TransCode::query()->where('company_id',$company_id)
                    ->where('trans_code','RQ')
//                    ->where('fiscal_year','2029-2020')
                    ->lockForUpdate()->first();

                $req_no = $tr_code->last_trans_id;

//                TransCode::query()->where('company_id',$company_id)
//                    ->where('trans_code','RQ')
//                    ->increment('last_trans_id');
            }

            $inserted = Requisition::query()->create([
                'company_id'=>$company_id,
                'ref_no'=>$req_no,
                'req_type'=> $row->reqType =='Purchase' ? 'P' : 'C',
                'req_date'=>$row->reqDate,
                'status'=>$row->status,
                'extra_field'=>$row->reqRefNo,
                'user_id'=>Auth::id(),
                'authorized_by'=>Auth::id(),
            ]);

            $reqs = $connection->table('item_requisitions')
                ->where('reqRefNo',$row->reqRefNo)
                ->where(DB::Raw('length(itemCode)'),9)
                ->get();

            foreach ($reqs as $req) {

                $req_for = $location->where('dept_code',$req->reqFor)->first();
                $product = ProductMO::query()
                    ->where('product_code',$req->itemCode)
                    ->where('company_id',$company_id)
                    ->first();

                if(!empty($product))
                {
                    TransProduct::query()->insert([
                        'company_id'=>$company_id,
                        'ref_no'=>$req_no,
                        'ref_id'=>$inserted->id,
                        'ref_type'=>'R',
                        'to_whom'=>isset($req_for['id']) ? $req_for['id'] : null,
                        'tr_date'=>$row->reqDate,
                        'product_id'=>$product->id,
                        'name'=>$product->name,
                        'quantity'=>$row->reqQuantity,
                        'approved'=>$row->approvedQty,
                        'purchased'=>$row->purchasedQty,
                        'received'=>$row->receivedQty,
                        'remarks'=>'Migrated',
                        'status'=>$row->status
                    ]);
                }

            }

            if($row->reqDate < '2019-07-01')
            {
                $req_sl ++;

            }else
            {
                TransCode::query()->where('company_id',$this->company_id)
                    ->where('trans_code','RQ')
//                    ->where('fiscal_year','2029-2020')
                    ->increment('last_trans_id');
            }

        }

        return $count;

        //End Test Area
    }

    public function MTPurchase($company_id)
    {
        ini_set('max_execution_time', 600);

        $connection = DB::connection('mcottondb');

        DB::statement('TRUNCATE TABLE relationships RESTART identity CASCADE;');

        $data = $connection->table('customers')
            ->where('customerType','E')
            ->get();

        $collection = Collect([]);
        $newLine = [];
        $count = 0;
        foreach ($data as $row)
        {
            $newLine['company_id'] = $company_id;
            $newLine['relation_type'] = 'LS';
            $newLine['name'] = $row->custName;
            $newLine['fax_number'] = $row->custID;
            $newLine['address'] = $row->custAddress;
            $newLine['phone_number'] = $row->phoneNo;
            $newLine['ledger_acc_no'] = $row->glhead;
            $newLine['email'] = $row->email;
            $newLine['user_id'] = Auth::id();

            Relationship::query()->create($newLine);

        }

        $purchase = $connection->table('purchase_header')->get();
        $items = $connection->table('purchase_items')->get();


        $newLine = [];
        $pr_sl = 70000001;

//        foreach ($purchase as $invoice)
//        {
//            if($row->purchaseOrderNo < '2019-07-01')
//            {
//                $pi_no = '2018'.$pr_sl;
//            }else
//            {
//                $tr_code =  TransCode::query()->where('company_id',$company_id)
//                    ->where('trans_code','PR')
//                    ->lockForUpdate()->first();
//
//                $pi_no = $tr_code->last_trans_id;
//            }
//
//            $inserted = Purchase::query()->create([
//                'company_id'=>$company_id,
//                'ref_no'=>$pi_no,
//                'req_type'=> $row->reqType =='Purchase' ? 'P' : 'C',
//                'req_date'=>$row->reqDate,
//                'status'=>$row->status,
//                'extra_field'=>$row->reqRefNo,
//                'user_id'=>Auth::id(),
//                'authorized_by'=>Auth::id(),
//            ]);
//
//            $newLine['company_id'] = $company_id;
//            $newLine['user_id'] = Auth::id();
//
//            Purchase::query()->create($newLine);
//        }

        return $count;
    }

    public function MTStatement($company_id)
    {
        $connection = DB::connection('mcottondb');

//        DB::statement('TRUNCATE TABLE stmt_lists RESTART identity CASCADE;');
        DB::statement('TRUNCATE TABLE stmt_lists;');
        DB::statement('TRUNCATE TABLE stmt_lines;');


        $data = $connection->table('stmt_lists')->get();

        $newLine = [];
        foreach ($data as $row)
        {
            $newLine['company_id'] = $company_id;
            $newLine['file_no'] = $row->fileNo;
            $newLine['file_desc'] = $row->fileDesc;
            $newLine['import_file'] = $row->importFile;
            $newLine['import_line'] = $row->importLine;
            $newLine['into_line'] = $row->intoLine;
            $newLine['user_id'] = Auth::id();

            StmtList::query()->create($newLine);

        }

        $data = $connection->table('stmt_data')->get();

        $newRow = [];
        $count = 0;
        foreach ($data as $row)
        {
            $newRow['company_id'] = $company_id;
            $newRow['file_no'] = $row->fileNo;
            $newRow['line_no'] = $row->lineNo;
            $newRow['text_position'] = $row->textPosition;
            $newRow['font_size'] = $row->font;
            $newRow['texts'] = $row->texts;
            $newRow['acc_type'] = $row->accType;
            $newRow['bal_type'] = $row->balType;
            $newRow['note'] = $row->note;
            $newRow['ac11'] = $row->ac11;
            $newRow['ac12'] = $row->ac12;
            $newRow['ac21'] = $row->ac21;
            $newRow['ac22'] = $row->ac22;
            $newRow['figure_position'] = $row->figrPosition;
            $newRow['sub_total'] = $row->subTotal;
            $newRow['formula'] = $row->pFormula;
            $newRow['user_id'] = Auth::id();

            StmtLine::query()->create($newRow);
            $count++;
        }

        return $count;

    }

}
