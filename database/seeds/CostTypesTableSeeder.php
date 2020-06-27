<?php

use Illuminate\Database\Seeder;

class CostTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cost_types')->insert([
            'name' => 'Transport Charge',
            'gl_head' => '40112112',
        ]);

        DB::table('cost_types')->insert([
            'name' => 'Postal Charge',
            'gl_head' => '40112112',
        ]);

        DB::table('cost_types')->insert([
            'name' => 'Freight Charge',
            'gl_head' => '40112112',
        ]);

        DB::table('cost_types')->insert([
            'name' => 'Warehouse Rent',
            'gl_head' => '40112112',
        ]);
    }
}
