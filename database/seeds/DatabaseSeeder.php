<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(GroupCompanySeeder::class);
        $this->call(CompanyTableSeeder::class);
        $this->call(UserRoleTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
