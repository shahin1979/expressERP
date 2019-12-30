<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'company_id'=>1,
            'role_id'=>1,
            'full_name' => 'SuperAdmin',
            'name' => 'SuperAdmin',
            'email' => 'superadmin@abcmail.com',
            'password' => bcrypt('pass123'),
            'pass_exp_date'=>'2020-11-24'
        ]);

        DB::table('users')->insert([
            'company_id'=>2,
            'role_id'=>2,
            'full_name' => 'Matin Admin',
            'name' => 'Matin',
            'email' => 'matin@matincottonmills.com',
            'password' => bcrypt('pass123'),
            'pass_exp_date'=>'2020-11-24'
        ]);

        DB::table('users')->insert([
            'company_id'=>2,
            'role_id'=>3,
            'full_name' => 'Atiqur Rahman Khadem',
            'name' => 'Khadem',
            'email' => 'khadem@matincottonmills.com',
            'password' => bcrypt('pass123'),
            'pass_exp_date'=>'2020-11-24'
        ]);


        DB::table('users')->insert([
            'company_id'=>3,
            'role_id'=>2,
            'full_name' => 'Mumanu Admin',
            'name' => 'Mumanu',
            'email' => 'mumanu@mumanupolyester.com',
            'password' => bcrypt('pass123'),
            'pass_exp_date'=>'2020-11-24'
        ]);

        DB::table('users')->insert([
            'company_id'=>3,
            'role_id'=>3,
            'full_name' => 'Sharif Muhammed Shahin',
            'name' => 'Shahin',
            'email' => 'shahin@mumanupolyester.com',
            'password' => bcrypt('pass123'),
            'pass_exp_date'=>'2020-11-24'
        ]);
//        factory('App\User', 10)->create();
    }
}
