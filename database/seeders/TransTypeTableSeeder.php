<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        DB::table('trans_types')->insert([
            'name' => 'Transfer',
            'description' => 'Transfer transaction',
        ]);

        DB::table('trans_types')->insert([
            'name' => 'Adjustment',
            'description' => 'Adjustment',
        ]);

        DB::table('trans_types')->insert([
            'name' => 'Cheque',
            'description' => 'Clearing Cheque',
        ]);

        DB::table('trans_types')->insert([
            'name' => 'RTGS',
            'description' => 'Real Time Gross Settlement',
        ]);

        DB::table('trans_types')->insert([
            'name' => 'BEFTN',
            'description' => 'Electronic Fund Transfer',
        ]);

        DB::table('trans_types')->insert([
            'name' => 'DEP',
            'description' => 'Depreciation',
        ]);

        DB::table('trans_types')->insert([
            'name' => 'Migrated',
            'description' => 'Migrated',
        ]);

        DB::table('trans_types')->insert([
            'name' => 'Sales',
            'description' => 'Sales',
        ]);

        DB::table('trans_types')->insert([
            'name' => 'Purchase',
            'description' => 'Purchase',
        ]);

        DB::table('trans_types')->insert([
            'name' => 'Others',
            'description' => 'Others',
        ]);

    }
}
