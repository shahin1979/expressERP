<?php

use Illuminate\Database\Seeder;

class AccountTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('account_types')->insert([
            'description' => 'Asset',
            'status' => 1
        ]);

        DB::table('account_types')->insert([
            'description' => 'Liability',
            'status' => 1
        ]);

        DB::table('account_types')->insert([
            'description' => 'Income',
            'status' => 1
        ]);

        DB::table('account_types')->insert([
            'description' => 'Expenditure',
            'status' => 1
        ]);

        DB::table('account_types')->insert([
            'description' => 'Capital',
            'status' => 1
        ]);
    }
}
