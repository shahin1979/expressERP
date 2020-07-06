<?php


namespace App\Traits;


use App\Models\Accounts\Ledger\CostCenter;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Trans\Transaction;
use App\Models\Company\TransCode;
use App\Models\Human\Admin\Location;
use App\Models\Inventory\Movement\Requisition;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\Category;
use App\Models\Inventory\Product\ItemUnit;
use App\Models\Inventory\Product\ProductMO;
use App\Models\Inventory\Product\SubCategory;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait MumanuMigrationTrait
{
    public function UserTable($company_id)
    {
        $connection = DB::connection('mumanudb');


        $users = $connection->table('users')
            ->whereNotIn('id',[1,2])
            ->get();

        $count = 0;

        foreach ($users as $user)
        {
            User::query()->updateOrCreate(
                ['company_id'=>$company_id,'name'=>$user->name],
                [
                    'email'=>$user->email,
                    'password'=>$user->password,
                    'full_name'=>$user->name,
                    'user_created'=>2,
                    'old_id'=>$user->id,
                    'role_id'=>3
                ]
            );
            $count ++;
        }

        return $count;
    }

    public function MumanuAccountsDB($company_id)
    {
        $connection = DB::connection('mumanudb');



        $ledgers = $connection->table('gl_accounts')
            ->where('comp_code',15)
            ->orderBy('ldgrCode','ASC')
            ->get();

        ini_set('max_execution_time', 800);

        DB::beginTransaction();

        try {

            $count = 0;

            // Migrate Gl_accounts Table

            foreach ($ledgers as $row)
            {

                $user = User::query()->where('company_id',$company_id)->where('name',$row->user_created)->first();
                $string = preg_replace('/[\*]+/', '', $row->accName);

                $type = $row->type_code == 1 ? 11 :
                    ($row->type_code == 2 ? 13 :
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
                        'start_dr'=>$row->start_dr,
                        'start_cr'=>$row->start_cr,
//                        'cyr_dr'=>$row->dr_00, //corrent year posted trans
//                        'cyr_cr'=>$row->cr_00, //corrent year posted trans
//                        'dr_00'=>$row->dr_00,
//                        'cr_00'=>$row->cr_00,
//                        'curr_bal' => ($row->start_dr + $row->dr_00) - ($row->start_cr + $row->cr_00),
                        'currency'=>'BDT',
                        'user_id'=>isset($user->id) ? $user->id  : 4,
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

            $data = $connection->table('transactions')
                ->select(DB::Raw('distinct voucher_no, j_code'))
                ->where('comp_code',15)
////                ->where('id','>',16434)
//                ->whereIn('voucher_no',[5106924,800039814])
//                    ->where('trans_date','=','2019-07-02')
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


                $inserted_voucher_no = null;
                $inserted_acc_cr = null;
                $inserted_acc_dr = null;

                $tr_code =  TransCode::query()->where('company_id',$company_id)
                    ->where('trans_code',$jcode)
                    ->where('fiscal_year','2019-2020')
                    ->lockForUpdate()->first();


                $voucher_no = $tr_code->last_trans_id;

                $trans_all = $connection->table('transactions')
                    ->where('comp_code',15)
                    ->where('voucher_no',$trans->voucher_no)
                    ->orderBy('acc_cr')
                    ->get();


//                dd($trans_all);

                foreach ($trans_all as $item)
                {
                    $sl = Carbon::parse($item->trans_date)->format('m');
                    $fp_no = get_fp_from_month_sl($sl, $this->company_id);
                    $var = str_pad($fp_no,2,"0",STR_PAD_LEFT);

                    if($item->acc_cr != 'JV')
                    {
                        if($item->acc_cr == $inserted_acc_cr and $item->voucher_no == $inserted_voucher_no)
                        {
                            Transaction::query()->where('company_id',$company_id)
                                ->where('voucher_no',$voucher_no)
                                ->where('acc_no',$inserted_acc_cr)
                                ->increment('cr_amt',$item->trans_amt);

                            Transaction::query()->where('company_id',$company_id)
                                ->where('voucher_no',$voucher_no)
                                ->where('acc_no',$inserted_acc_cr)
                                ->increment('trans_amt',$item->trans_amt);

                            $desc = Transaction::query()->where('company_id',$company_id)
                                ->where('voucher_no',$voucher_no)
                                ->where('acc_no',$inserted_acc_cr)->first();

                            $new_desc = isset($desc->trans_desc1) ? $desc->trans_desc1 : (' , '.isset($item->trans_desc1) ? $item->trans_desc1 : '') ;

                            Transaction::query()->where('company_id',$company_id)
                                ->where('voucher_no',$voucher_no)
                                ->where('acc_no',$inserted_acc_cr)
                                ->update(['trans_desc1'=>$new_desc]);


//                            DB::update("update transactions set trans_desc1 = CONCAT(trans_desc1,':','$item->trans_desc1') where voucher_no = '$voucher_no'and company_id='$company_id' and acc_no = '$inserted_acc_cr'");

                        }else{
                            $user = User::query()->where('company_id',$company_id)->where('name',$item->user_id)->first();

                            Transaction::query()->insert([
                                'company_id'=>$company_id,
                                'period'=>$item->period,
                                'tr_code'=>$jcode,
                                'trans_type_id'=>8,
                                'fp_no'=>$fp_no,
                                'ref_no'=>$item->ref_no,
                                'cheque_no'=>$item->cheque_no,
//                                'cost_center'=>$item->cost_center,
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
                                'authorizer_id'=>isset($user->id) ? $user->id : 4,
                                'post_date'=>$item->trans_date,
                                'user_id'=>isset($user->id)  ? $user->id : 42,
                            ]);

                            $inserted_voucher_no = $item->voucher_no;
                            $inserted_acc_cr = $item->acc_cr;
                        }



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
                        if($item->acc_dr == $inserted_acc_dr and $item->voucher_no == $inserted_voucher_no)
                        {
                            Transaction::query()->where('company_id',$company_id)
                                ->where('voucher_no',$voucher_no)
                                ->where('acc_no',$item->acc_dr)
                                ->increment('dr_amt',$item->trans_amt);

                            Transaction::query()->where('company_id',$company_id)
                                ->where('voucher_no',$voucher_no)
                                ->where('acc_no',$item->acc_dr)
                                ->increment('trans_amt',$item->trans_amt);

                            $desc = Transaction::query()->where('company_id',$company_id)
                                ->where('voucher_no',$voucher_no)
                                ->where('acc_no',$item->acc_dr)->first();

                            $new_desc = isset($desc->trans_desc1) ? $desc->trans_desc1 : (' , '.isset($item->trans_desc1) ? $item->trans_desc1 : ' ') ;

                            Transaction::query()->where('company_id',$company_id)
                                ->where('voucher_no',$voucher_no)
                                ->where('acc_no',$item->acc_dr)
                                ->update(['trans_desc1'=>$new_desc]);


//                            DB::update("update transactions set trans_desc1 = CONCAT(trans_desc1,'$item->trans_desc1')
//                                where voucher_no = '$voucher_no'and company_id='$company_id'
//                                and acc_no = '$item->acc_dr'");

                        }else{
                            $user = User::query()->where('company_id',$company_id)->where('name',$item->user_id)->first();

                            $goes = Transaction::query()->insert([
                                'company_id'=>$company_id,
                                'period'=>$item->period,
                                'tr_code'=>$jcode,
                                'trans_type_id'=>8,
                                'fp_no'=>$fp_no,
                                'ref_no'=>$item->ref_no,
                                'cheque_no'=>$item->cheque_no,
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
                                'authorizer_id'=>isset($user->id) ? $user->id : 4,
                                'post_date'=>$item->trans_date,
                                'user_id'=>isset($user->id) ? $user->id : 4,
                            ]);
                            $inserted_voucher_no = $item->voucher_no;
                            $inserted_acc_dr = $item->acc_dr;
                        }


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
            $error = $e->getMessage();
//            dd($error);
            return redirect()->back()->with('error','Not Saved '.$error);
        }

        DB::commit();

        return $count;
    }

    public function MumanuItems($company_id)
    {
        $connection = DB::connection('mumanudb');

        $units = $connection->table('item_units')->get();
        $groups = $connection->table('item_groups')->get();
        $products = $connection->table('item_lists')
            ->where('deleted',false)->get();

        DB::beginTransaction();

        try {

//            if(Config::get('database.default') == 'mysql')
//            {
//
//                DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
//
//                DB::statement('TRUNCATE TABLE item_units;');
//                DB::statement('TRUNCATE TABLE categories;');
//                DB::statement('TRUNCATE TABLE sub_categories;');
//                DB::statement('TRUNCATE TABLE products;');
//                DB::statement('TRUNCATE TABLE locations;');
////                DB::statement('TRUNCATE TABLE requisitions;');
////                DB::statement('TRUNCATE TABLE trans_products;');
//
//                DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
//            }
//
//            if(Config::get('database.default') == 'pgsql')
//            {
//                DB::statement('TRUNCATE TABLE item_units RESTART identity CASCADE;');
//                DB::statement('TRUNCATE TABLE categories RESTART identity CASCADE;');
//                DB::statement('TRUNCATE TABLE sub_categories RESTART identity CASCADE;');
//                DB::statement('TRUNCATE TABLE products RESTART identity CASCADE;');
//                DB::statement('TRUNCATE TABLE locations RESTART identity CASCADE;');
//                DB::statement('TRUNCATE TABLE requisitions RESTART identity CASCADE;');
//                DB::statement('TRUNCATE TABLE trans_products RESTART identity CASCADE;');
//            }

            // MIGRATE ITEM UNITS TABLE

            foreach ($units as $unit) {
                ItemUnit::query()->insert([
                    'company_id' => $this->company_id,
                    'name' => Str::upper($unit->unitName),
                    'formal_name' => ucfirst($unit->description),
                    'no_of_decimal_places' =>0,
                    'user_id' => $this->user_id
                ]);
            }

            // CATEGORIES SUB CATEGORIES & PRODUCTS TABLE MIGRATION

            foreach ($groups as $row)
            {
                if($row->isGroup == true)
                {
                    $inserted = Category::query()->create([
                        'company_id' => $this->company_id,
                        'name' => $row->groupName,
                        'alias'=> $row->groupName,
                        'has_sub' => true,
                        'user_id' => $this->user_id
                    ]);

                    $sbcs = $groups->where('groupCode',$row->groupCode)
                        ->where('isGroup',false);

                    foreach ($sbcs as $sbc)
                    {
                        $sid = SubCategory::query()->create([
                            'company_id' => $this->company_id,
                            'category_id' => $inserted->id,
                            'name' => $sbc->groupName,
                            'alias'=> $sbc->groupName,
                            'user_id' => $this->user_id
                        ]);

                        //Insert Product

                        $grp_products = $products->where('groupCode',$row->groupCode)
                            ->where('subGroupCode',$sbc->subGroupCode);

                        foreach ($grp_products as $line)
                        {

                            $prefix = substr($inserted->name,0,3);
                            $sku = $prefix . int_random();

                            ProductMO::query()->insert([
                                'company_id' => $this->company_id,
                                'category_id' => $inserted->id,
                                'subcategory_id' => $sid->id,
                                'brand_id'=>1,
                                'size_id'=>1,
                                'color_id'=>1,
                                'model_id'=>1,
                                'tax_id'=>1,
                                'godown_id'=>1,
                                'rack_id'=>1,
                                'name' => $line->itemName,
                                'unit_name'=> $line->itemUnit == trim('Squire Feet') ? 'SFT' : strtoupper($line->itemUnit),
                                'product_code'=> $line->itemCode,
                                'sku' => $sku,
                                'buy_price'=> $line->avgPrice,
                                'on_hand'=> $line->currentBalance,
                                'on_hand_unit_two'=> $line->currBalQty1,
                                'on_hand_unit_three'=> $line->currBalQty2,
                                'opening_qty'=>$line->openingStock,
                                'opening_value' => $line->openStockValue,
                                'unit_price'=> $line->openingStock> 0 ? $line->openStockValue/$line->openingStock : 0,
                                'user_id' => $this->user_id
                            ]);
                        }

                    }
                }
            }

            // Update GL Head subcategory Stock In

//            SubCategory::query()->where('company_id',$this->company_id)->where('category_id',1)
//                ->update(['acc_in_stock'=>'10712104']);
//
//            SubCategory::query()->where('company_id',$this->company_id)->where('category_id',2)
//                ->update(['acc_in_stock'=>'10712102']);
//
//            SubCategory::query()->where('company_id',$this->company_id)->where('category_id',3)
//                ->update(['acc_in_stock'=>'10612106']);
//
////            SubCategory::query()->where('company_id',$this->company_id)->where('category_id',4)
////                ->update(['acc_in_stock'=>'10612106']);
//
//            SubCategory::query()->where('company_id',$this->company_id)->where('category_id',5)
//                ->update(['acc_in_stock'=>'10712108']);
//
//            SubCategory::query()->where('company_id',$this->company_id)->where('category_id',6)
//                ->update(['acc_in_stock'=>'10712106']);
//
//            SubCategory::query()->where('company_id',$this->company_id)->where('category_id',7)
//                ->update(['acc_in_stock'=>'10712106']);
//
////            SubCategory::query()->where('company_id',$this->company_id)->where('category_id',8)
////                ->update(['acc_in_stock'=>'10712106']);
//
//            SubCategory::query()->where('company_id',$this->company_id)->where('category_id',9)
//                ->update(['acc_in_stock'=>'10712112']);
//
//            SubCategory::query()->where('company_id',$this->company_id)->where('category_id',10)
//                ->update(['acc_in_stock'=>'10712114']);

//
//            /// STOCK OUT
//            ///
//
//            // Update GL Head subcategory Stock In
//
//            SubCategory::query()->where('company_id',$this->company_id)->where('category_id',1)
//                ->update(['acc_out_stock'=>'40912116']);
//
//            SubCategory::query()->where('company_id',$this->company_id)->where('category_id',2)
//                ->update(['acc_out_stock'=>'40912104']);
//
//            SubCategory::query()->where('company_id',$this->company_id)->where('category_id',3)
//                ->update(['acc_out_stock'=>'10612110']);
//
////            SubCategory::query()->where('company_id',$this->company_id)->where('category_id',4)
////                ->update(['acc_out_stock'=>'10612106']);
//
//            SubCategory::query()->where('company_id',$this->company_id)->where('category_id',5)
//                ->update(['acc_out_stock'=>'40712102']);
//
//            SubCategory::query()->where('company_id',$this->company_id)->where('category_id',6)
//                ->update(['acc_out_stock'=>'40912122']);
//
//            SubCategory::query()->where('company_id',$this->company_id)->where('category_id',7)
//                ->update(['acc_out_stock'=>'40912126']);
//
////            SubCategory::query()->where('company_id',$this->company_id)->where('category_id',8)
////                ->update(['acc_out_stock'=>'10712106']);
//
////            SubCategory::query()->where('company_id',$this->company_id)->where('category_id',9)
////                ->update(['acc_out_stock'=>'10712112']);
//
////            SubCategory::query()->where('company_id',$this->company_id)->where('category_id',10)
////                ->update(['acc_out_stock'=>'10712114']);
//
//            SubCategory::query()->where('company_id',$this->company_id)->where('id',20)
//                ->update(['acc_out_stock'=>'40912118']);
//
//            SubCategory::query()->where('company_id',$this->company_id)->whereIn('id',[12,13,21,32])
//                ->update(['acc_out_stock'=>'40912106']);


        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error','Not Saved '.$error);
        }

        DB::commit();
    }


    public function MumanuRequisition($company_id)
    {
        ini_set('max_execution_time', 1800);

        $connection = DB::connection('mumanudb');

        DB::statement('TRUNCATE TABLE requisitions;');
        DB::statement('TRUNCATE TABLE trans_products;');

        $departments = $connection->table('item_departments')->get();
        $newLine = [];

        foreach ($departments as $row)
        {
            $newLine['company_id'] = $company_id;
            $newLine['fiscal_year'] = '2019-2020';
            $newLine['name'] = $row->deptName;
            $newLine['old_id'] = $row->deptCode;
            $newLine['user_id'] = Auth::id();

            CostCenter::query()->create($newLine);
        }

        $count = 0;
        $requisitions = $connection->table('item_requisitions')->get();
        $collection = $requisitions->unique('reqRefNo');

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

                $req_for = CostCenter::query()->where('company_id',$company_id)->where('old_id',$req->reqFor)->first();
                $product = ProductMO::query()->where('product_code',$req->itemCode)
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
            $count++;
        }

        return $count;

    }

}