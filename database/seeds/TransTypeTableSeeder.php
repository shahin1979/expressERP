<?php

use Illuminate\Database\Seeder;

class TransTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
            'name' => 'Others',
            'description' => 'Others',
        ]);

    }
}
