<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupCompanySeeder extends Seeder
{
    /**
     * Run the database seeders.
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
            'website' => 'www.abcgroup.com'
        ]);
    }
}
