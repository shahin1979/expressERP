<?php

use Illuminate\Database\Seeder;
use App\Models\Inventory\Product\ItemModel;

class ItemModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Itemmodel::create( [
            'company_id'=>3,
            'name'=>'0.9D',
            'description'=>'0.9D',
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>1,
        ] );

        Itemmodel::create( [
            'company_id'=>3,
            'name'=>'1D',
            'description'=>'1D',
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>3,
        ] );



        Itemmodel::create( [
            'company_id'=>3,
            'name'=>'1.2D',
            'description'=>'1.2D',
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>3,
        ] );



        Itemmodel::create( [
            'company_id'=>3,
            'name'=>'1.4D',
            'description'=>'1.4D',
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>3,
        ] );



        Itemmodel::create( [
            'company_id'=>3,
            'name'=>'2D',
            'description'=>'2D',
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>3,
        ] );



        Itemmodel::create( [
            'company_id'=>3,
            'name'=>'2.5D',
            'description'=>'2.5D',
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>3,
        ] );



        Itemmodel::create( [
            'company_id'=>3,
            'name'=>'3D',
            'description'=>'3D',
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>3,
        ] );



        Itemmodel::create( [
            'company_id'=>3,
            'name'=>'6D',
            'description'=>'6D',
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>3,
        ] );



        Itemmodel::create( [
            'company_id'=>3,
            'name'=>'7D',
            'description'=>'7D',
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>3,
        ] );



        Itemmodel::create( [
            'company_id'=>3,
            'name'=>'15D',
            'description'=>'15D',
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>3,
        ] );
    }
}
