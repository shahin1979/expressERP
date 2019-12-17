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
        DB::table('companies')->insert([
            'group_id'=>1,
            'name' => 'Spider IT Limited',
            'address' => '77/A, East Rajabazar,West Panthapath, Dhaka-1215',
            'city' => 'Dhaka',
            'country'=>'Bangladesh',
            'email' => 'spider@abcmail.com',
            'phone_no'=>'01709635863',
            'post_code'=>'1215',
            'currency' =>'BDT',
            'website' => 'www.spider.com'
        ]);

        DB::table('companies')->insert([
            'group_id'=>1,
            'name' => 'FM Technologies Limited',
            'address' => 'New Elephant Road, Dhaka-1215',
            'city' => 'Dhaka',
            'country'=>'Bangladesh',
            'email' => 'fmtech@fmtechbd.com',
            'phone_no'=>'01709635863',
            'post_code'=>'1215',
            'currency' =>'BDT',
            'website' => 'www.fmtechbd.com'
        ]);
    }
}
