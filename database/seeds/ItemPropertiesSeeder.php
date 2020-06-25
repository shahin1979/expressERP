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

        Category::query()->insert([
            'company_id'=>2,
            'name' => 'Default Category',
            'alias'=>'Default Category',
            'has_sub'=>1,
            'status' => 1,
            'user_id' => 1
        ]);

        SubCategory::query()->insert([
            'company_id'=>2,
            'category_id'=>1,
            'name' => 'Default Sub Category',
            'alias'=>'Default Sub Category',
            'status' => 1,
            'user_id' => 1
        ]);

        ItemBrand::query()->insert([
            'company_id'=>2,
            'name' => 'Default Brand',
            'manufacturer'=>'Default Manufacturer',
            'status' => 1,
            'user_id' => 1
        ]);

        ItemModel::query()->insert([
            'company_id'=>2,
            'name' => 'Default Model',
            'status' => 1,
            'user_id' => 1
        ]);

        ItemColor::query()->insert([
            'company_id'=>2,
            'name' => 'Default Color',
            'status' => 1,
            'user_id' => 1
        ]);

        ItemSize::query()->insert([
            'company_id'=>2,
            'size' => 'Default Size',
            'status' => 1,
            'user_id' => 1
        ]);

        Godown::query()->insert([
            'company_id'=>2,
            'name' => 'Default Store',
            'address'=>'Main Store',
            'status' => 1,
            'user_id' => 1
        ]);


        Rack::query()->insert([
            'company_id'=>2,
            'name' => 'Default Rack',
            'godown_id'=>1,
            'status' => 1,
            'user_id' => 1
        ]);

        ItemTax::query()->insert([
            'company_id'=>2,
            'name' => 'No Tax',
            'applicable_on'=>'B',
            'rate'=>0,
            'calculating_mode'=>'F',
            'description'=>'Default Tax',
            'status' => 1,
            'user_id' => 1
        ]);

        ItemTax::query()->insert([
            'company_id'=>2,
            'name' => 'VAT',
            'applicable_on'=>'B',
            'rate'=>10,
            'calculating_mode'=>'F',
            'description'=>'Value Added Tax',
            'status' => 1,
            'user_id' => 1
        ]);


        Relationship::query()->insert([
            'company_id'=>2,
            'name' => 'Cash Account',
            'relation_type'=>'SP',
            'ledger_acc_no'=> '10112102',
            'user_id' => 1
        ]);

        Location::query()->insert([
            'company_id'=>2,
            'name' => 'Office Building',
            'location_type'=>'O',
            'user_id' => 1
        ]);

        CostCenter::query()->insert([
            'company_id'=>2,
            'fiscal_year','2019-2020',
            'name' => 'Factory',
            'user_id' => 1
        ]);

        CostCenter::query()->insert([
            'company_id'=>2,
            'fiscal_year','2019-2020',
            'name' => 'Head Office',
            'user_id' => 1
        ]);

        Project::query()->insert([
            'company_id'=>2,
            'project_code','Project-001',
            'project_type'=>'O',
            'project_desc'=>'Default Project',
            'project_name' => 'Default Project',
            'project_ref'=>'Govt',
            'start_date'=>\Carbon\Carbon::now(),
            'user_id' => 1
        ]);
    }
}
