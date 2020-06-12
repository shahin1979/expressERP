<?php


namespace App\Traits;


use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Previous\GeneralLedgerBackup;
use App\Models\Accounts\Previous\TransactionBackup;
use App\Models\Accounts\Trans\Transaction;
use App\Models\Company\TransCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

trait PreviousDataMigrationTrait
{
    public function trCode($company_id)
    {
        $yr = '2019';
        $count = $yr;
        $fyear = '2019-2020';

        TransCode::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'trans_code'=>'PM','fiscal_year'=>$fyear],
            [
                'company_id'=>$this->company_id,
                'trans_code'=>'PM',
                'trans_name'=>'Payment',
                'last_trans_id'=>$yr.'10000001'
            ]
        );

        TransCode::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'trans_code'=>'RC','fiscal_year'=>$fyear],
            [
                'company_id'=>$this->company_id,
                'trans_code'=>'RC',
                'trans_name'=>'Receive',
                'last_trans_id'=>$yr.'20000001'
            ]
        );

        TransCode::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'trans_code'=>'JV','fiscal_year'=>$fyear],
            [
                'company_id'=>$this->company_id,
                'trans_code'=>'JV',
                'trans_name'=>'Journal',
                'last_trans_id'=>$yr.'30000001'
            ]
        );

        TransCode::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'trans_code'=>'RQ','fiscal_year'=>$fyear],
            [
                'company_id'=>$this->company_id,
                'trans_code'=>'RQ',
                'trans_name'=>'Requisition',
                'last_trans_id'=>$yr.'40000001'
            ]
        );

        TransCode::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'trans_code'=>'SL','fiscal_year'=>$fyear],
            [
                'company_id'=>$this->company_id,
                'trans_code'=>'SL',
                'trans_name'=>'Sales Invoice',
                'last_trans_id'=>$yr.'50000001'
            ]
        );

        TransCode::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'trans_code'=>'DC','fiscal_year'=>$fyear],
            [
                'company_id'=>$this->company_id,
                'trans_code'=>'DC',
                'trans_name'=>'Delivery Challan',
                'last_trans_id'=>$yr.'60000001'
            ]
        );

        TransCode::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'trans_code'=>'PR','fiscal_year'=>$fyear],
            [
                'company_id'=>$this->company_id,
                'trans_code'=>'PR',
                'trans_name'=>'Purchase Invoice',
                'last_trans_id'=>$yr.'70000001'
            ]
        );

        TransCode::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'trans_code'=>'IR','fiscal_year'=>$fyear],
            [
                'company_id'=>$this->company_id,
                'trans_code'=>'IR',
                'trans_name'=>'Item Receive',
                'last_trans_id'=>$yr.'80000001'
            ]
        );
        TransCode::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'trans_code'=>'RT','fiscal_year'=>$fyear],
            [
                'company_id'=>$this->company_id,
                'trans_code'=>'IR',
                'trans_name'=>'Item Return',
                'last_trans_id'=>$yr.'90000001'
            ]
        );

        return $count;
    }

    public function previousData($company_id,$fiscal_year)
    {
        $connection = DB::connection('mcottondb');

        $ledgers = $connection->table('gl_accounts')
            ->where('comp_code',12)
            ->orderBy('ldgrCode','ASC')
            ->get();

        ini_set('max_execution_time', 600);

//        DB::beginTransaction();

        try {

            if (Config::get('database.default') == 'mysql') {

                DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

//                DB::statement('TRUNCATE TABLE general_ledger_backups;');
//                DB::statement('TRUNCATE TABLE transaction_backups;');
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

                GeneralLedgerBackup::query()->insert(
                    ['company_id'=>$company_id,
                        'acc_no'=>$row->accNo,
                        'fiscal_year'=> $fiscal_year,
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

            $sum = GeneralLedgerBackup::query()->where('company_id',$company_id)
                ->where('is_group',false)
                ->where('fiscal_year',$fiscal_year)
                ->select('ledger_code',DB::raw('sum(start_dr) as start_dr, sum(start_cr) as start_cr'))
                ->groupBy('ledger_code')
                ->get();

            foreach ($sum as $item)
            {
                GeneralLedgerBackup::query()->where('company_id',$company_id)
                    ->where('is_group',true)
                    ->where('ledger_code',$item->ledger_code)
                    ->where('fiscal_year',$fiscal_year)
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
                    ->where('fiscal_year',$fiscal_year)
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
                        TransactionBackup::query()->insert([
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
                            'fiscal_year'=>$fiscal_year,
                            'trans_desc1'=>$item->trans_desc1,
                            'trans_desc2'=>$item->trans_desc2,
                            'post_flag'=>true,
                            'old_voucher'=>$item->voucherNo,
                            'authorizer_id'=>Auth::id(),
                            'post_date'=>$item->trans_date,
                            'user_id'=>Auth::id(),
                        ]);

                        GeneralLedgerBackup::query()->where('company_id',$company_id)
                            ->where('acc_no',$item->acc_cr)
                            ->where('fiscal_year',$fiscal_year)
                            ->increment('cr_'.$var, $item->trans_amt);

                        GeneralLedgerBackup::query()->where('company_id',$company_id)
                            ->where('acc_no',$item->acc_cr)
                            ->where('fiscal_year',$fiscal_year)
                            ->increment('cyr_cr', $item->trans_amt);

                        GeneralLedgerBackup::query()->where('company_id',$company_id)
                            ->where('acc_no',$item->acc_cr)
                            ->where('fiscal_year',$fiscal_year)
                            ->increment('cr_00', $item->trans_amt);

                        GeneralLedgerBackup::query()->where('company_id',$company_id)
                            ->where('ledger_code',substr($item->acc_cr,0,3))
                            ->where('is_group',true)
                            ->where('fiscal_year',$fiscal_year)
                            ->increment('cr_'.$var, $item->trans_amt);

                        GeneralLedgerBackup::query()->where('company_id',$company_id)
                            ->where('ledger_code',substr($item->acc_cr,0,3))
                            ->where('is_group',true)
                            ->where('fiscal_year',$fiscal_year)
                            ->increment('cyr_cr', $item->trans_amt);

                        GeneralLedgerBackup::query()->where('company_id',$company_id)
                            ->where('ledger_code',substr($item->acc_cr,0,3))
                            ->where('is_group',true)
                            ->where('fiscal_year',$fiscal_year)
                            ->increment('cr_00', $item->trans_amt);
                    }

                    if($item->acc_dr != 'JV')
                    {
                        TransactionBackup::query()->insert([
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
                            'fiscal_year'=>$fiscal_year,
                            'trans_desc1'=>$item->trans_desc1,
                            'trans_desc2'=>$item->trans_desc2,
                            'post_flag'=>true,
                            'old_voucher'=>$item->voucherNo,
                            'authorizer_id'=>Auth::id(),
                            'post_date'=>$item->trans_date,
                            'user_id'=>Auth::id(),
                        ]);

                        GeneralLedgerBackup::query()->where('company_id',$company_id)
                            ->where('acc_no',$item->acc_dr)
                            ->where('fiscal_year',$fiscal_year)
                            ->increment('dr_'.$var, $item->trans_amt);

                        GeneralLedgerBackup::query()->where('company_id',$company_id)
                            ->where('acc_no',$item->acc_dr)
                            ->where('fiscal_year',$fiscal_year)
                            ->increment('dr_00', $item->trans_amt);

                        GeneralLedgerBackup::query()->where('company_id',$company_id)
                            ->where('acc_no',$item->acc_dr)
                            ->where('fiscal_year',$fiscal_year)
                            ->increment('cyr_dr', $item->trans_amt);


                        GeneralLedgerBackup::query()->where('company_id',$company_id)
                            ->where('ledger_code',substr($item->acc_dr,0,3))
                            ->where('is_group',true)
                            ->where('fiscal_year',$fiscal_year)
                            ->increment('dr_'.$var, $item->trans_amt);

                        GeneralLedgerBackup::query()->where('company_id',$company_id)
                            ->where('ledger_code',substr($item->acc_dr,0,3))
                            ->where('is_group',true)
                            ->where('fiscal_year',$fiscal_year)
                            ->increment('cyr_dr', $item->trans_amt);

                        GeneralLedgerBackup::query()->where('company_id',$company_id)
                            ->where('ledger_code',substr($item->acc_dr,0,3))
                            ->where('is_group',true)
                            ->where('fiscal_year',$fiscal_year)
                            ->increment('dr_00', $item->trans_amt);
                    }
                }

//              Update Last Voucher No

                TransCode::query()->where('company_id',$this->company_id)
                    ->where('trans_code',$jcode)
                    ->where('fiscal_year',$fiscal_year)
                    ->increment('last_trans_id');

            }

            // Update Current Balance Column

            $head_sum = GeneralLedgerBackup::query()->where('company_id',$company_id)->get();

            foreach ($head_sum as $sum) {
                GeneralLedgerBackup::query()->where('company_id',$company_id)
                    ->where('acc_no', $sum->acc_no)
                    ->where('fiscal_year',$fiscal_year)
                    ->update(['curr_bal'=>($sum->start_dr + $sum->dr_00) - ($sum->start_cr + $sum->cr_00)]);
            }



        }catch (\Exception $e)
        {
//            DB::rollBack();
            dd($e->getMessage());
            $error = $e->getMessage();
            return redirect()->back()->with('error','Not Saved '.$error);
        }
//        DB::commit();

        return $count;
    }

}
