<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReligionTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        DB::table('religions')->insert([
            'name' => 'Islam',
            'status' => 1
        ]);

        DB::table('religions')->insert([
            'name' => 'Hindu',
            'status' => 1
        ]);

        DB::table('religions')->insert([
            'name' => 'Cristian',
            'status' => 1
        ]);

        DB::table('religions')->insert([
            'name' => 'Buddhist',
            'status' => 1
        ]);

        DB::table('religions')->insert([
            'name' => 'Others',
            'status' => 1
        ]);
    }
}
