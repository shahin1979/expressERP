<?php

use Illuminate\Database\Seeder;
use App\Models\Inventory\Product\ItemSize;

class ItemSizeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        ItemSize::create( [
            'id'=>1,
            'company_id'=>2,
            'size'=>'24MM',
            'description'=>'24MM',
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>1,
        ] );



        ItemSize::create( [
            'id'=>2,
            'company_id'=>2,
            'size'=>'32MM',
            'description'=>NULL,
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>3,
        ] );



        ItemSize::create( [
            'id'=>3,
            'company_id'=>2,
            'size'=>'38MM',
            'description'=>NULL,
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>3,
        ] );



        ItemSize::create( [
            'id'=>4,
            'company_id'=>2,
            'size'=>'51MM',
            'description'=>NULL,
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>3,
        ] );



        ItemSize::create( [
            'id'=>5,
            'company_id'=>2,
            'size'=>'62MM',
            'description'=>NULL,
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>3,
        ] );



        ItemSize::create( [
            'id'=>6,
            'company_id'=>2,
            'size'=>'64MM',
            'description'=>NULL,
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>3,
        ] );



        ItemSize::create( [
            'id'=>7,
            'company_id'=>2,
            'size'=>'72MM',
            'description'=>NULL,
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>3,
        ] );
    }
}
