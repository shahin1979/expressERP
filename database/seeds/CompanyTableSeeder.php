<?php

use Illuminate\Database\Seeder;
use App\Models\Company\CompanyModule;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            'group_id'=>1,
            'name' => 'Mother Company',
            'address' => '7, Mujib Sarak, Sirajganj, Bangladesh',
            'city' => 'Sirajganj',
            'country'=>'Bangladesh',
            'email' => 'group@gmail.com',
            'phone_no'=>'+88-0751-63314, +88-0751-62902 ',
            'post_code'=>'1215',
            'currency' =>'BDT',
            'website' => 'http://matincottonmills.com/'
        ]);

        DB::table('companies')->insert([
            'group_id'=>1,
            'name' => 'NRB Bank 1',
            'address' => '7, Mujib Sarak, Sirajganj, Bangladesh',
            'city' => 'Sirajganj',
            'country'=>'Bangladesh',
            'email' => 'taj.matincotton@gmail.com',
            'phone_no'=>'+88-0751-63314, +88-0751-62902 ',
            'post_code'=>'1215',
            'currency' =>'BDT',
            'website' => 'http://matincottonmills.com/'
        ]);

        DB::table('companies')->insert([
            'group_id'=>1,
            'name' => 'Mumanu',
            'address' => '3/8 A, Block: D, Dhaka',
            'city' => 'Dhaka',
            'country'=>'Bangladesh',
            'email' => 'mumanupolyester@gmail.com',
            'phone_no'=>'01928-281818',
            'post_code'=>'1215',
            'currency' =>'BDT',
            'website' => 'https://mumanupsf.com/'
        ]);



        CompanyModule::create( [
            'company_id'=>2,
            'module_id'=>1
        ] );



        CompanyModule::create( [
            'company_id'=>2,
            'module_id'=>2
        ] );



        CompanyModule::create( [
            'company_id'=>2,
            'module_id'=>3
        ] );

        CompanyModule::create( [
            'company_id'=>2,
            'module_id'=>4
        ] );

        CompanyModule::create( [
            'company_id'=>2,
            'module_id'=>5
        ] );
    }
}
