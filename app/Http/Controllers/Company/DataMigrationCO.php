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
use App\Traits\MumanuMigrationTrait;
use App\Traits\PreviousDataMigrationTrait;
use App\Traits\TransactionsTrait;
use App\Traits\TtwentyFourTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DataMigrationCO extends Controller
{
    use MigrationTrait, PreviousDataMigrationTrait, TtwentyFourTrait, MumanuMigrationTrait;

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


//        $output = $this->create_fiscal_period($this->company_id,'01-07-2019');
//        $output = $this->trCode($this->company_id,'2019');


//        $connection = DB::connection('mcottondb');
//        $output = $this->UserTable($this->company_id);
//        $output = $this->mumanuDB($this->company_id);
//        $output = $this->matinDB($this->company_id);
//        $output = $this->MTRequisition($this->company_id);
//        $output = $this->MTPurchase($this->company_id);
//        $output = $this->MTInvoice($this->company_id);
//        $output = $this->MTHistories($this->company_id);
//        $output = $this->MTStatement($this->company_id);
//        $output = $this->depreciation($this->company_id);

//        $output = $this->hpsmTwo();


        // Previous data Migration
        $output = $this->previousData($this->company_id,'2019-2020');


        // MUMANU DAtabase
//        $output = $this->UserTable($this->company_id);
//        $output = $this->MumanuAccountsDB($this->company_id);
//        $output = $this->MumanuItems($this->company_id);
//        $output = $this->MumanuRequisition($this->company_id);
//        $output = $this->MumanuPurchase($this->company_id);
//        $output = $this->MumanuProduction($this->company_id);
//        $output = $this->MumanuPurchaseReceive($this->company_id); // backup 4
//        $output = $this->MumanuConsumption($this->company_id); //backup 5
//        $output = $this->MumanuBank($this->company_id); // backup 6







        return redirect()->action('Company\DataMigrationCO@index')->with('success','Successfully Migrated : '.$output);


        $connection = DB::connection('mcottondb');



        //End Test Area


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










            // END  CATEGORIES SUB CATEGORIES & PRODUCTS TABLE MIGRATION

            //MIGRATION DEPARTMENTS TABLE

//            $departments = $connection->table('item_departments')->get();
//
//            $location = Collect([]);
//            $newLine = [];
//
//
//            foreach ($departments as $row)
//            {
//
//                $newLine['company_id'] = $this->company_id;
//                $newLine['location_type'] = 'F';
//                $newLine['name'] = $row->deptName;
//                $newLine['dept_code'] = $row->deptCode;
//                $newLine['user_id'] = $this->user_id;
//
//                $inserted = Location::query()->create($newLine);
//
//                $newLine['id'] = $inserted->id;
//                $location->push($newLine);
//
//            }


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
