<?php

use Illuminate\Database\Seeder;

class UserRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_roles')->insert([
            'company_id' => 1,
            'name' => 'Super Admin',
            'description'=>'Developer Side login',
            'status' => true
        ]);

        DB::table('user_roles')->insert([
            'company_id' => 1,
            'name' => 'Admin',
            'description'=>'Branch Company Admin. Get Only Security Module',
            'status' => true
        ]);

        DB::table('user_roles')->insert([
            'company_id' => 1,
            'name' => 'User',
            'description'=>'Branch Company General user',
            'status' => true
        ]);

        DB::table('user_roles')->insert([
            'company_id' => 99,
            'name' => 'Any Branch login',
            'description'=>'Any Branch Login Permission',
            'status' => true
        ]);
    }
}
