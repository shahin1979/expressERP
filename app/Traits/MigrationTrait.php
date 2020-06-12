<?php


namespace App\Traits;


use App\Models\Accounts\Ledger\DepreciationMO;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Statement\StmtLine;
use App\Models\Accounts\Statement\StmtList;
use App\Models\Accounts\Trans\Transaction;
use App\Models\Company\Relationship;
use App\Models\Company\TransCode;
use App\Models\Human\Admin\Location;
use App\Models\Inventory\Movement\Purchase;
use App\Models\Inventory\Movement\Requisition;
use App\Models\Inventory\Movement\Sale;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ProductMO;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

trait MigrationTrait
{
    use TransactionsTrait;

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

            $sum = GeneralLedger::query()->where('company_id',$company_id)
                ->where('is_group',false)
                ->select('ledger_code',DB::raw('sum(start_dr) as start_dr, sum(start_cr) as start_cr'))
                ->groupBy('ledger_code')
                ->get();

            foreach ($sum as $item)
            {
                GeneralLedger::query()->where('company_id',$company_id)
                    ->where('is_group',true)
                    ->where('ledger_code',$item->ledger_code)
                    ->update([
                        'start_dr'=>$item->start_dr,
                        'start_cr'=>$item->start_cr,
                        ]);
            }

            // Migrate Transactions Table

            $transactions = $connection->table('transactions')
                ->where('comp_code',12)
//                ->where('trans_date','>','2019-11-30')
                ->get();


            $data = $connection->table('transactions')
                ->select(DB::Raw('distinct voucher_no, j_code'))
                ->where('comp_code',12)
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
                            'trans_type_id'=>8,
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
                            'old_voucher'=>$item->voucher_no,
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
                            'trans_type_id'=>8,
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
                            'old_voucher'=>$item->voucher_no,
                            'authorizer_id'=>Auth::id(),
                            'post_date'=>$item->trans_date,
                            'user_id'=>Auth::id(),
                        ]);

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
                    ->where('fiscal_year','2019-2020')
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

        DB::statement('TRUNCATE TABLE locations;');
        DB::statement('TRUNCATE TABLE requisitions;');
        DB::statement('TRUNCATE TABLE trans_products;');

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

//        $req_sl = 40000001;

        foreach ($collection as $row)
        {
            $fiscal = $this->get_fiscal_year_db_date($this->company_id,$row->reqDate);

            $tr_code =  TransCode::query()->where('company_id',$company_id)
                ->where('trans_code','RQ')
                ->where('fiscal_year',$fiscal)
                ->lockForUpdate()->first();

            $req_no = $tr_code->last_trans_id;


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
                        'relationship_id'=>isset($req_for['id']) ? $req_for['id'] : null,
                        'tr_date'=>$row->reqDate,
                        'product_id'=>$product->id,
                        'name'=>$product->name,
                        'quantity'=>$req->reqQuantity,
                        'approved'=>$req->approvedQty,
                        'purchased'=>$req->purchasedQty,
                        'received'=>$req->receivedQty,
                        'remarks'=>'Migrated',
                        'status'=>$req->status
                    ]);
                }

            }

            TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','RQ')
                    ->where('fiscal_year',$fiscal)
                ->increment('last_trans_id');

//            if($row->reqDate < '2019-07-01')
//            {
//                $req_sl ++;
//
//            }else
//            {
//                TransCode::query()->where('company_id',$this->company_id)
//                    ->where('trans_code','RQ')
////                    ->where('fiscal_year','2029-2020')
//                    ->increment('last_trans_id');
//            }

        }

        return $count;

        //End Test Area
    }

    public function MTPurchase($company_id)
    {
        ini_set('max_execution_time', 600);

        $connection = DB::connection('mcottondb');

//        DB::statement('TRUNCATE TABLE relationships RESTART identity CASCADE;');
        DB::statement('TRUNCATE TABLE relationships;');
        DB::statement('TRUNCATE TABLE purchases;');

        $data = $connection->table('customers')
            ->where('customerType','E')
            ->get();

//        $collection = Collect([]);
        $newLine = [];
        $count = 0;
        $suppliers = Collect([]);

// Insert Relationship Table for suppliers

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
            $suppliers->push($newLine);
//            $count ++;
        }
// End Relationship

 // Insert Purchase Table Data
        //
        $purchase = $connection->table('purchase_header')->get();
        $product = ProductMO::query()
            ->where('company_id',$company_id)->get();

        $requisitions = Requisition::query()->where('company_id',$company_id)
            ->where('req_type','P')->get();

        foreach ($purchase as $row)
        {
            $fiscal = $this->get_fiscal_year_db_date($this->company_id,$row->poDate );

            $tr_code =  TransCode::query()->where('company_id',$company_id)
                ->where('trans_code','PR')
                ->where('fiscal_year',$fiscal)->lockForUpdate()->first();

            $pi_no = $tr_code->last_trans_id;

            $inserted = Purchase::query()->create([
                'company_id'=>$company_id,
                'ref_no'=>$pi_no,
                'contra_ref'=> $requisitions->where('extra_field',$row->RefNo)->first()->ref_no,
                'po_date'=>$row->poDate,
                'old_number'=>$row->purchaseOrderNo,
                'purchase_type'=>'LP',
                'invoice_amt'=>$row->totalAmount,
                'invoice_date'=>$row->poDate,
                'status'=>$row->status =='P' ? 'PR' : ($row->status =='R' ? 'RJ' : 'RC'),
                'user_id'=>Auth::id(),
                'authorized_by'=>Auth::id(),
            ]);

            // Insert Purchase Items Table

            $items = $connection->table('purchase_items')
                ->where('purchaseOrderNo',$row->purchaseOrderNo)
                ->where(DB::Raw('length(itemCode)'),9)
                ->get();

            foreach ($items as $item) {

                $prod = $product->where('product_code',$item->itemCode)->first();
                $supplier = Relationship::query()->where('fax_number',$item->suplierId)->first();

                if(!empty($prod))
                {
                    TransProduct::query()->insert([
                        'company_id'=>$company_id,
                        'ref_no'=>$pi_no,
                        'ref_id'=>$inserted->id,
                        'ref_type'=>'P',
                        'relationship_id'=>isset($supplier->id) ? $supplier->id : null,
                        'tr_date'=>$row->poDate,
                        'product_id'=>$prod->id,
                        'name'=>$prod->name,
                        'quantity'=>$item->itemQty,
                        'unit_price'=>$item->unitPrice,
                        'total_price' =>$item->itemQty*$item->unitPrice,
                        'approved'=>$item->itemQty,
                        'purchased'=>$item->itemQty,
                        'received'=>$item->receivedQty,
                        'returned'=>$item->returnQty,
                        'remarks'=>'Migrated',
                    ]);
                }

            }

            TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','PR')
                ->where('fiscal_year',$fiscal)
                ->increment('last_trans_id');
            $count ++ ;
        }

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
            $newRow['import_line'] = $row->fixedValue;
            $newRow['negative_value'] = $row->negVal;
            $newRow['user_id'] = Auth::id();

            StmtLine::query()->create($newRow);
            $count++;
        }

        return $count;

    }

    public function depreciation($company_id, $connection)
    {
        DB::statement('TRUNCATE TABLE depreciation;');


        $data = $connection->table('fixed_asset_sch')->get();

        $newLine = [];
        $count = 0;
        foreach ($data as $row)
        {
            $newLine['company_id'] = $company_id;
            $newLine['acc_no'] = $row->accNo;
            $newLine['fp_no'] = $row->fpNo;
            $newLine['fiscal_year'] = $this->get_fiscal_year_db_date($company_id,$row->opnDate);
            $newLine['start_date'] = $row->opnDate;
            $newLine['end_date'] = $row-> endDate ;
            $newLine['open_bal'] = $row->openBall;
            $newLine['additional_bal'] = $row->Addition;
            $newLine['total_bal'] = $row->totalVal;
            $newLine['dep_rate'] = $row->depRate;
            $newLine['dep_amt'] = $row->deprAmt;
            $newLine['closing_bal'] = $row->finalval;
            $newLine['contra_acc'] = $row->contraAcc;
            $newLine['approve_date'] = $row->postDate;
            $newLine['approve_status'] = $row->postingStatus;
            $newLine['user_id'] = Auth::id();

            DepreciationMO::query()->create($newLine);
            $count++;

        }
        return $count;
    }

    public function MTInvoice($company_id)
    {
        ini_set('max_execution_time', 600);

        $connection = DB::connection('mcottondb');

//        DB::statement('TRUNCATE TABLE relationships RESTART identity CASCADE;');
//        DB::statement('TRUNCATE TABLE relationships;');
        DB::statement('TRUNCATE TABLE sales;');

        $data = $connection->table('customers')
            ->where('customerType','B')
            ->get();

//        $collection = Collect([]);
        $newLine = [];
        $count = 0;
//        $suppliers = Collect([]);

// Insert Relationship Table for suppliers

        foreach ($data as $row)
        {
            $newLine['company_id'] = $company_id;
            $newLine['relation_type'] = 'CS';
            $newLine['name'] = $row->custName;
            $newLine['fax_number'] = $row->custID;
            $newLine['address'] = $row->custAddress;
            $newLine['phone_number'] = $row->phoneNo;
            $newLine['ledger_acc_no'] = $row->glhead;
            $newLine['email'] = $row->email;
            $newLine['user_id'] = Auth::id();

//            Relationship::query()->create($newLine);
//            $suppliers->push($newLine);
//            $count ++;
        }
// End Relationship

        // Insert Purchase Table Data
        //
        $sales = $connection->table('invoice_sales')->get();
        $product = ProductMO::query()
            ->where('company_id',$company_id)->get();

//        $requisitions = Requisition::query()->where('company_id',$company_id)
//            ->where('req_type','P')->get();

        foreach ($sales as $row)
        {
            $fiscal = $this->get_fiscal_year_db_date($this->company_id,$row->invoiceDate );

            $tr_code =  TransCode::query()->where('company_id',$company_id)
                ->where('trans_code','SL')
                ->where('fiscal_year',$fiscal)->lockForUpdate()->first();

            $invoice_no = $tr_code->last_trans_id;
            $customer = Relationship::query()->where('fax_number',$row->customerID)->first();

            $inserted = Sale::query()->create([
                'company_id'=>$company_id,
                'invoice_no'=>$invoice_no,
                'customer_id'=> isset($customer->id) ? $customer->id : '',
                'invoice_type'=>'CI',
                'invoice_date'=>$row->invoiceDate,
                'old_invoice_no'=>$row->invoiceNo,
                'invoice_amt'=>$row->invoiceAmt,
                'due_amt'=>$row->dueAmt,
                'status'=>$row->approvalStatus =='A' ? 'AP' : ($row->approvalStatus =='R' ? 'RJ': 'CR'),
                'delivery_status'=>$row->deliveryStatus == 'D' ? 1 : 0,
                'user_id'=>Auth::id(),
                'authorized_by'=>Auth::id(),
            ]);

            // Insert Invoice Items Table

            $items = $connection->table('invoice_items')
                ->where('invoiceNo',$row->invoiceNo)
                ->where(DB::Raw('length(itemCode)'),9)
                ->get();

            foreach ($items as $item) {

                $prod = $product->where('product_code',$item->itemCode)->first();


                if(!empty($prod))
                {
                    TransProduct::query()->insert([
                        'company_id'=>$company_id,
                        'ref_no'=>$invoice_no,
                        'ref_id'=>$inserted->id,
                        'ref_type'=>'S',
                        'relationship_id'=>isset($customer->id) ? $customer->id : null,
                        'tr_date'=>$row->invoiceDate,
                        'product_id'=>$prod->id,
                        'name'=>$prod->name,
                        'quantity'=>$item->quantity,
                        'unit_price'=>$item->rate,
                        'total_price' =>$item->quantity*$item->rate,
                        'approved'=>$item->quantity,
                        'sold'=>$item->quantity,
                        'delivered'=>$item->deliveredQty,
                        'remarks'=>'Migrated',
                    ]);
                }

            }

            TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','SL')
                ->where('fiscal_year',$fiscal)
                ->increment('last_trans_id');

            $count ++ ;
        }

        return $count;
    }

    public function MTHistories($company_id)
    {
        ini_set('max_execution_time', 600);

        $connection = DB::connection('mcottondb');

//        DB::statement('TRUNCATE TABLE relationships RESTART identity CASCADE;');
//        DB::statement('TRUNCATE TABLE relationships;');
        DB::statement('TRUNCATE TABLE stock_item_histories;');

        $data = $connection->table('stock_item_histories')->get();

//        $collection = Collect([]);
        $newLine = [];
        $count = 0;
//        $suppliers = Collect([]);

// Insert Relationship Table for suppliers

        foreach ($data as $row)
        {
            switch($row->typeCode)
            {
                case 'Opning Stock':

            }
            $newLine['company_id'] = $company_id;
            $newLine['relation_type'] = 'CS';
            $newLine['name'] = $row->custName;
            $newLine['fax_number'] = $row->custID;
            $newLine['address'] = $row->custAddress;
            $newLine['phone_number'] = $row->phoneNo;
            $newLine['ledger_acc_no'] = $row->glhead;
            $newLine['email'] = $row->email;
            $newLine['user_id'] = Auth::id();

//            Relationship::query()->create($newLine);
//            $suppliers->push($newLine);
//            $count ++;
        }
// End Relationship

        // Insert Purchase Table Data
        //
        $sales = $connection->table('invoice_sales')->get();
        $product = ProductMO::query()
            ->where('company_id',$company_id)->get();

//        $requisitions = Requisition::query()->where('company_id',$company_id)
//            ->where('req_type','P')->get();

        foreach ($sales as $row)
        {
            $fiscal = $this->get_fiscal_year_db_date($this->company_id,$row->invoiceDate );

            $tr_code =  TransCode::query()->where('company_id',$company_id)
                ->where('trans_code','SL')
                ->where('fiscal_year',$fiscal)->lockForUpdate()->first();

            $invoice_no = $tr_code->last_trans_id;
            $customer = Relationship::query()->where('fax_number',$row->customerID)->first();

            $inserted = Sale::query()->create([
                'company_id'=>$company_id,
                'invoice_no'=>$invoice_no,
                'customer_id'=> isset($customer->id) ? $customer->id : '',
                'invoice_type'=>'CI',
                'invoice_date'=>$row->invoiceDate,
                'old_invoice_no'=>$row->invoiceNo,
                'invoice_amt'=>$row->invoiceAmt,
                'due_amt'=>$row->dueAmt,
                'status'=>$row->approvalStatus =='A' ? 'AP' : ($row->approvalStatus =='R' ? 'RJ': 'CR'),
                'delivery_status'=>$row->deliveryStatus == 'D' ? 1 : 0,
                'user_id'=>Auth::id(),
                'authorized_by'=>Auth::id(),
            ]);

            // Insert Invoice Items Table

            $items = $connection->table('invoice_items')
                ->where('invoiceNo',$row->invoiceNo)
                ->where(DB::Raw('length(itemCode)'),9)
                ->get();

            foreach ($items as $item) {

                $prod = $product->where('product_code',$item->itemCode)->first();


                if(!empty($prod))
                {
                    TransProduct::query()->insert([
                        'company_id'=>$company_id,
                        'ref_no'=>$invoice_no,
                        'ref_id'=>$inserted->id,
                        'ref_type'=>'S',
                        'relationship_id'=>isset($customer->id) ? $customer->id : null,
                        'tr_date'=>$row->invoiceDate,
                        'product_id'=>$prod->id,
                        'name'=>$prod->name,
                        'quantity'=>$item->quantity,
                        'unit_price'=>$item->rate,
                        'total_price' =>$item->quantity*$item->rate,
                        'approved'=>$item->quantity,
                        'sold'=>$item->quantity,
                        'delivered'=>$item->deliveredQty,
                        'remarks'=>'Migrated',
                    ]);
                }

            }

            TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','SL')
                ->where('fiscal_year',$fiscal)
                ->increment('last_trans_id');

            $count ++ ;
        }

        return $count;
    }

}
