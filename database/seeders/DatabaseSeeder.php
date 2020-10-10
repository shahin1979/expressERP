<?php
namespace Database\Seeders;
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
        $this->call(AppModuleTableSeeder::class);
        $this->call(GroupCompanySeeder::class);
        $this->call(CompanyTableSeeder::class);
        $this->call(ReligionTableSeeder::class);
        $this->call(UserRoleTableSeeder::class);
        $this->call(UsersTableSeeder::class);
       $this->call(MenuItemsTableSeeder::class);
       $this->call(AccountTypesTableSeeder::class);
       $this->call(AccountTypeDetailsTableSeeder::class);
        $this->call(TransTypeTableSeeder::class);
        $this->call(GenericCodeTableSeeder::class);

//        $this->call(ItemSizeTableSeeder::class);
//        $this->call(ItemModelSeeder::class);

    }
}
