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
            'login_name' => 'SuperAdmin',
            'name' => 'SuperAdmin',
            'email' => 'superadmin@abcmail.com',
            'password' => bcrypt('pass123'),
            'pass_exp_date'=>'2020-11-24'
        ]);

        DB::table('users')->insert([
            'company_id'=>1,
            'role_id'=>2,
            'login_name' => 'Admin',
            'name' => 'Admin',
            'email' => 'admin@abcmail.com',
            'password' => bcrypt('pass123'),
            'pass_exp_date'=>'2020-11-24'
        ]);
    }
}
