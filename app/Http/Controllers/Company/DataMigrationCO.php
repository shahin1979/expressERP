<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Human\Admin\Location;
use App\Models\Inventory\Product\Category;
use App\Models\Inventory\Product\ItemUnit;
use App\Models\Inventory\Product\ProductMO;
use App\Models\Inventory\Product\SubCategory;
use App\Traits\MigrationTrait;
use App\Traits\PreviousDataMigrationTrait;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DataMigrationCO extends Controller
{
    use MigrationTrait, PreviousDataMigrationTrait;

    public function index()
    {
//        dd(Config::get('database.default'));


        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>11030,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        return view('company.data-migration-index');
    }

    public function migrate()
    {


//        $connection = DB::connection('mcottondb');

//        $output = $this->mumanuDB($this->company_id);
//        $output = $this->matinDB($this->company_id);
//        $output = $this->MTRequisition($this->company_id);
//        $output = $this->MTPurchase($this->company_id);
//        $output = $this->MTInvoice($this->company_id);
//        $output = $this->MTHistories($this->company_id);
//        $output = $this->MTStatement($this->company_id);
//        $output = $this->depreciation($this->company_id,$connection);
//        $output = $this->create_fiscal_period($this->company_id,'01-07-2018');

        // Previous data Migration

//        $output = $this->trCode($this->company_id);
//        $output = $this->previousData($this->company_id,'2018-2019');

        // End

//        dd($output);

//        dd('here');


        $start_date = '2021-05-13';
        $end_date = '2026-05-12';
        $g_date = '2021-05-12';
        $principal = 2500000;
        $principal_x = $principal;
        $first_day_count = 0;
        $err = 9;
        $installment = 55646;
        $profit_size = 3750;
        $last_day_month = date("Y-m-t", strtotime($g_date));
        $first_day_month = date("Y-m-1", strtotime($g_date));
        $second_day_count = dateDifference($last_day_month,$g_date);
        $factor = 36500;
        $outstanding = $principal + 225000;


//        dd(dateDifference($last_day_month,$g_date));

//        $report=[];

//        $report['tr_date'] = date("Y-m-t", strtotime($g_date));

//        dd($report);

        DB::statement('TRUNCATE TABLE installment;');

        for ($i=0; $i<60; $i++)
        {
            $tr_date = date("Y-m-t", strtotime($g_date));

            $rent = floor((($principal_x*$first_day_count*$err)/$factor) + (($principal*$second_day_count*$err)/$factor));

            DB::Table('installment')->insert([
                'principal_x' => $principal_x,
                'principal' => $principal,
                'description'=>'Rent Charge',
                'first_date'=>$first_day_month,
                'tr_date' => $tr_date,
                'first_range'=>$first_day_count,
                'second_range'=>$second_day_count,
                'first_amount'=>($principal_x*$first_day_count*$err)/$factor,
                'second_amt'=>($principal*$second_day_count*$err)/$factor,
                'installment_amt'=> $rent
            ]);

            $final = getNextDay(addMonths($g_date,1));
            $outstanding = $outstanding - $installment + $rent;

                DB::Table('installment')->insert([
                'description'=>'Recovery Installment',
                'tr_date' => $final,
                'formula'=>'=F11',
                'installment_amt'=> $installment,
                'balance'=>$outstanding
            ]);

            $g_date = $final;

            $principal_x = $principal;
            $principal = $principal - $installment + $rent + $profit_size;
            $first_day_count = dateDifference($final, date("Y-m-1", strtotime($final)));
            $second_day_count = dateDifference(date("Y-m-t", strtotime($g_date)),$final) + 1;
        }

        dd($g_date);



        $connection = DB::connection('mcottondb');



        //End Test Area


        $units = $connection->table('item_units')->get();
        $groups = $connection->table('item_groups')->get();
        $products = $connection->table('item_lists')
            ->where('deleted',false)->get();

        DB::beginTransaction();

        try {

            if(Config::get('database.default') == 'mysql')
            {

                DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

                DB::statement('TRUNCATE TABLE item_units;');
                DB::statement('TRUNCATE TABLE categories;');
                DB::statement('TRUNCATE TABLE sub_categories;');
                DB::statement('TRUNCATE TABLE products;');
                DB::statement('TRUNCATE TABLE locations;');
                DB::statement('TRUNCATE TABLE requisitions;');
                DB::statement('TRUNCATE TABLE trans_products;');

                DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
            }

            if(Config::get('database.default') == 'pgsql')
            {
                DB::statement('TRUNCATE TABLE item_units RESTART identity CASCADE;');
                DB::statement('TRUNCATE TABLE categories RESTART identity CASCADE;');
                DB::statement('TRUNCATE TABLE sub_categories RESTART identity CASCADE;');
                DB::statement('TRUNCATE TABLE products RESTART identity CASCADE;');
                DB::statement('TRUNCATE TABLE locations RESTART identity CASCADE;');
                DB::statement('TRUNCATE TABLE requisitions RESTART identity CASCADE;');
                DB::statement('TRUNCATE TABLE trans_products RESTART identity CASCADE;');
            }

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
                                'name' => $line->itemName,
                                'unit_name'=> $line->itemUnit == trim('Squire Feet') ? 'SFT' : strtoupper($line->itemUnit),
                                'product_code'=> $line->itemCode,
                                'sku' => $sku,
                                'buy_price'=> $line->avgPrice,
                                'on_hand'=> $line->currentBalance,
                                'on_hand_unit_two'=> $line->currBalQty1,
                                'on_hand_unit_three'=> $line->currBalQty2,
                                'opening_value' => $line->openStockValue,
                                'user_id' => $this->user_id
                            ]);
                        }

                    }
                }

            }

            // END  CATEGORIES SUB CATEGORIES & PRODUCTS TABLE MIGRATION

            //MIGRATION DEPARTMENTS TABLE

            $departments = $connection->table('item_departments')->get();

            $location = Collect([]);
            $newLine = [];


            foreach ($departments as $row)
            {

                $newLine['company_id'] = $this->company_id;
                $newLine['location_type'] = 'F';
                $newLine['name'] = $row->deptName;
                $newLine['dept_code'] = $row->deptCode;
                $newLine['user_id'] = $this->user_id;

                $inserted = Location::query()->create($newLine);

                $newLine['id'] = $inserted->id;
                $location->push($newLine);

            }


            //REQUISITION TABLE MIGRATION







        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error','Not Saved '.$error);
        }

        DB::commit();


        return redirect()->action('Company\DataMigrationCO@index')->with('success','Successfully Migrated');
    }
}
