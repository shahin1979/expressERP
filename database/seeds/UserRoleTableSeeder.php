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
            'status' => true
        ]);

        DB::table('user_roles')->insert([
            'company_id' => 1,
            'name' => 'Admin',
            'status' => true
        ]);

        DB::table('user_roles')->insert([
            'company_id' => 1,
            'name' => 'User',
            'status' => true
        ]);
    }
}
