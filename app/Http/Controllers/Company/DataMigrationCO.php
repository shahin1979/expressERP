<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Product\Category;
use App\Models\Inventory\Product\ItemUnit;
use App\Models\Inventory\Product\ProductMO;
use App\Models\Inventory\Product\SubCategory;
use App\Traits\MigrationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DataMigrationCO extends Controller
{
    use MigrationTrait;

    public function index()
    {
//        dd(Config::get('database.default'));


        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>11030,'user_id'=>$this->user_id
            ]);

        return view('company.data-migration-index');
    }

    public function migrate()
    {

//        $output = $this->matinDB($this->company_id);
        $output = $this->matinDB($this->company_id);

        dd($output);

        $connection = DB::connection('mcottondb');

        $units = $connection->table('item_units')->get();
        $data = $connection->table('item_groups')->get();
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

                DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
            }

            if(Config::get('database.default') == 'pgsql')
            {
                DB::statement('TRUNCATE TABLE item_units RESTART identity CASCADE;');
                DB::statement('TRUNCATE TABLE categories RESTART identity CASCADE;');
                DB::statement('TRUNCATE TABLE sub_categories RESTART identity CASCADE;');
                DB::statement('TRUNCATE TABLE products RESTART identity CASCADE;');
            }


            foreach ($units as $unit) {
                ItemUnit::query()->insert([
                    'company_id' => $this->company_id,
                    'name' => Str::upper($unit->unitName),
                    'formal_name' => ucfirst($unit->description),
                    'no_of_decimal_places' =>0,
                    'user_id' => $this->user_id
                ]);
            }

            foreach ($data as $row)
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

                    $sbcs = $data->where('groupCode',$row->groupCode)
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
