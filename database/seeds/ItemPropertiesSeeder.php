<?php

use Illuminate\Database\Seeder;
use App\Models\Inventory\Product\ItemModel;
use App\Models\Inventory\Product\ItemBrand;
use App\Models\Inventory\Product\ItemColor;
use App\Models\Inventory\Product\ItemSize;
use App\Models\Inventory\Product\Godown;
use App\Models\Inventory\Product\Rack;
use App\Models\Inventory\Product\ItemTax;
use App\Models\Inventory\Product\Category;
use App\Models\Inventory\Product\SubCategory;
use App\Models\Company\Relationship;
use App\Models\Human\Admin\Location;
use App\Models\Accounts\Ledger\CostCenter;
use App\Models\Projects\Project;

class ItemPropertiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Lastbaleno::create( [
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



        Lastbaleno::create( [
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
