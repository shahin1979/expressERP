<?php

use Illuminate\Database\Seeder;

class AppModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('app_modules')->insert([
            'module_name' => 'Company Module',
            'description' => 'Company Related Activities'
        ]);

        DB::table('app_modules')->insert([
            'module_name' => 'Security Module',
            'description' => 'Security Related Activities'
        ]);

        DB::table('app_modules')->insert([
            'module_name' => 'HR Module',
            'description' => 'Human Resource Related Activities'
        ]);

        DB::table('app_modules')->insert([
            'module_name' => 'Accounts Module',
            'description' => 'Accounts Related Activities'
        ]);

        DB::table('app_modules')->insert([
            'module_name' => 'Inventory Module',
            'description' => 'Inventory Related Activities'
        ]);


    }
}
