<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
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

        DB::table('app_modules')->insert([
            'module_name' => 'MIS Module',
            'description' => 'Management Information System'
        ]);

//        DB::table('app_modules')->insert([
//            'module_name' => 'ExIm Module',
//            'description' => 'Export Import Related Activities'
//        ]);

        DB::table('app_modules')->insert([
            'module_name' => 'Hospital Module',
            'description' => 'Hospital Related Activities'
        ]);

        DB::table('app_modules')->insert([
            'id'=>9,
            'module_name' => 'Project Module',
            'description' => 'Project Related Activities'
        ]);


    }
}
