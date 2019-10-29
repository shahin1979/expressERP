<?php

use Illuminate\Database\Seeder;

class MenuItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MenuItem::create( [
            'id'=>1,
            'company_id'=>1,
            'nav_label'=>1,
            'div_class'=>NULL,
            'i_class'=>NULL,
            'menu_type'=>'CM',
            'menu_prefix'=>'A',
            'menu_id'=>'AC0',
            'name'=>'MAIN',
            'show'=>1,
            'url'=>'home',
            'status'=>1,
            'created_at'=>'2019-04-09 13:04:23',
            'updated_at'=>'2019-04-16 11:37:03'
        ] );
    }
}
