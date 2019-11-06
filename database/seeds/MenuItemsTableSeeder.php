<?php

use Illuminate\Database\Seeder;
use App\Models\Common\MenuItem;

class MenuItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        MenuItem::create( [
//            'id'=>10001,
//            'company_id'=>1,
//            'module_id'=>1,
//            'nav_label'=>1,
//            'div_class'=>NULL,
//            'i_class'=>NULL,
//            'menu_type'=>'CM',
//            'menu_prefix'=>'A',
//            'menu_id'=>'AC0',
//            'name'=>'COMPANY MODULE',
//            'show'=>1,
//            'url'=>'home',
//            'status'=>1
//        ] );
//
//        MenuItem::create( [
//            'id'=>11000,
//            'company_id'=>1,
//            'module_id'=>1,
//            'nav_label'=>1,
//            'div_class'=>'nav-item has-sub',
//            'i_class'=>'fa fa-sitemap',
//            'menu_type'=>'MM',
//            'menu_prefix'=>'A',
//            'menu_id'=>'A00',
//            'name'=>'COMPANY BASIC',
//            'show'=>1,
//            'url'=>'#',
//            'status'=>1
//        ] );
//
//
//
//        MenuItem::create( [
//            'id'=>11001,
//            'company_id'=>1,
//            'module_id'=>1,
//            'nav_label'=>1,
//            'div_class'=>NULL,
//            'i_class'=>NULL,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'A',
//            'menu_id'=>'A01',
//            'name'=>'Company Info',
//            'show'=>1,
//            'url'=>'company/basicIndex',
//            'status'=>1
//        ] );


            MenuItem::create( [
            'id'=>11002,
            'company_id'=>1,
            'module_id'=>1,
            'nav_label'=>1,
            'div_class'=>NULL,
            'i_class'=>NULL,
            'menu_type'=>'SM',
            'menu_prefix'=>'A',
            'menu_id'=>'A02',
            'name'=>'Fiscal Period',
            'show'=>1,
            'url'=>'company/fiscalPeriodIndex',
            'status'=>1
        ] );





//        MenuItem::create( [
//            'id'=>20001,
//            'company_id'=>1,
//            'module_id'=>2,
//            'nav_label'=>2,
//            'div_class'=>NULL,
//            'i_class'=>NULL,
//            'menu_type'=>'CM',
//            'menu_prefix'=>'B',
//            'menu_id'=>'BC0',
//            'name'=>'SECURITY MODULE',
//            'show'=>1,
//            'url'=>'home',
//            'status'=>1
//        ] );
//
//        MenuItem::create( [
//            'id'=>21000,
//            'company_id'=>1,
//            'module_id'=>2,
//            'nav_label'=>2,
//            'div_class'=>'nav-item has-sub',
//            'i_class'=>'fa fa-sitemap',
//            'menu_type'=>'MM',
//            'menu_prefix'=>'B',
//            'menu_id'=>'B00',
//            'name'=>'USER BASIC',
//            'show'=>1,
//            'url'=>'home',
//            'status'=>1
//        ] );
//
//
//
//        MenuItem::create( [
//            'id'=>21001,
//            'company_id'=>1,
//            'module_id'=>2,
//            'nav_label'=>2,
//            'div_class'=>NULL,
//            'i_class'=>NULL,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'B',
//            'menu_id'=>'B01',
//            'name'=>'Add User',
//            'show'=>1,
//            'url'=>'register',
//            'status'=>1
//        ] );

    }
}
