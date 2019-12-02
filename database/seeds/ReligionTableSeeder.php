<?php

use Illuminate\Database\Seeder;

class ReligionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
