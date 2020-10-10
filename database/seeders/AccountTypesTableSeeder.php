<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
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
