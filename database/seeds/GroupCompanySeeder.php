<?php

use Illuminate\Database\Seeder;

class GroupCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('group_companies')->insert([
            'name' => 'ABC Group',
            'address' => 'BSCIC Industrial Estate',
            'city' => 'Dhaka',
            'post_code'=>'7002',
            'country'=>'Bangladesh',
            'email' => 'abc@bttb.net.bd',
            'phone_no'=>'071-61933,73244, 61600',
            'currency' =>'BDT',
            'website' => 'www.fmtechnologies.com'
        ]);
    }
}
