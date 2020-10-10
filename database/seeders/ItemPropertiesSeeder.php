<?php
namespace Database\Seeders;
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
