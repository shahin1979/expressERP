<?php
namespace Database\Seeders;
use App\Models\Inventory\Product\GroupCategory;
use Illuminate\Database\Seeder;
use App\Models\Inventory\Movement\LastBaleNo;

class ItemPropertiesSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {

        GroupCategory::query()->create( [
            'id'=>1,
            'name'=>'Production',
            'description'=>'Production Items But Not For Sales',
            'status'=>1,
            'current_stock_value'=>'0.00'
        ] );



        GroupCategory::query()->create( [
            'id'=>2,
            'name'=>'Production & Sales',
            'description'=>'Production & Sales Items',
            'status'=>1,
            'current_stock_value'=>'0.00'
        ] );



        GroupCategory::query()->create( [
            'id'=>3,
            'name'=>'Sales',
            'description'=>'Sales Items But Not From Production',
            'status'=>1,
            'current_stock_value'=>'0.00'
        ] );



        GroupCategory::query()->create( [
            'id'=>4,
            'name'=>'Consumables',
            'description'=>'Consumable Items',
            'status'=>1,
            'current_stock_value'=>'0.00'
        ] );



        GroupCategory::query()->create( [
            'id'=>5,
            'name'=>'Machinery',
            'description'=>'Machinery Items',
            'status'=>1,
            'current_stock_value'=>'0.00'
        ] );



        GroupCategory::query()->create( [
            'id'=>99,
            'name'=>'Others',
            'description'=>'Other Items',
            'status'=>1,
            'current_stock_value'=>'0.00'
        ] );

        LastBaleNo::query()->create( [
            'id'=>1,
            'company_id'=>1,
            'line_no'=>1,
            'product_id'=>1634,
            'tr_weight'=>1.50,
            'lot_no'=>100001,
            'bale_serial'=>1,
            'bale_no'=>'1',
            'status'=>1,
            'updated_by'=>3
        ] );



        LastBaleNo::query()->create( [
            'id'=>2,
            'company_id'=>1,
            'line_no'=>2,
            'product_id'=>2178,
            'tr_weight'=>1.50,
            'lot_no'=>600001,
            'bale_serial'=>1,
            'bale_no'=>'1',
            'status'=>1,
            'updated_by'=>3
        ] );

    }
}
