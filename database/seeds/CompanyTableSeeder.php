<?php

use Illuminate\Database\Seeder;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        DB::table('companies')->insert([
//            'group_id'=>1,
//            'name' => 'ABC Company Limited',
//            'address' => '77/A, East Rajabazar,West Panthapath, Dhaka-1215',
//            'city' => 'Dhaka',
//            'country'=>'Bangladesh',
//            'email' => 'info@abcmail.com',
//            'phone_no'=>'01709635863',
//            'post_code'=>'1215',
//            'currency' =>'BDT',
//            'website' => 'www.abccompany.com'
//        ]);

        DB::table('companies')->insert([
            'group_id'=>1,
            'name' => 'FM Technologies Limited',
            'address' => 'New Elephant Road, Dhaka-1215',
            'city' => 'Dhaka',
            'country'=>'Bangladesh',
            'email' => 'admin@fmtechbd.com',
            'phone_no'=>'01709635863',
            'post_code'=>'1215',
            'currency' =>'BDT',
            'website' => 'www.fmtechbd.com'
        ]);
    }
}
