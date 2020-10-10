<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_roles')->insert([
            'name' => 'Super Admin',
            'description'=>'Developer Side login',
            'status' => true
        ]);

        DB::table('user_roles')->insert([
            'name' => 'Admin',
            'description'=>'Branch Company Admin. Get Only Security Module',
            'status' => true
        ]);

        DB::table('user_roles')->insert([
            'name' => 'User',
            'description'=>'Branch Company General user',
            'status' => true
        ]);

        DB::table('user_roles')->insert([
            'id' => 99,
            'name' => 'Any Company login',
            'description'=>'Any Branch Login Permission',
            'status' => true
        ]);
    }
}
