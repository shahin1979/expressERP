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
            'company_id'=>3,
            'size'=>'24MM',
            'description'=>'24MM',
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>1,
        ] );



        ItemSize::create( [
            'company_id'=>3,
            'size'=>'32MM',
            'description'=>NULL,
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>3,
        ] );



        ItemSize::create( [
            'company_id'=>3,
            'size'=>'38MM',
            'description'=>NULL,
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>3,
        ] );



        ItemSize::create( [
            'company_id'=>3,
            'size'=>'51MM',
            'description'=>NULL,
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>3,
        ] );



        ItemSize::create( [
            'company_id'=>3,
            'size'=>'62MM',
            'description'=>NULL,
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>3,
        ] );



        ItemSize::create( [
            'company_id'=>3,
            'size'=>'64MM',
            'description'=>NULL,
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>3,
        ] );



        ItemSize::create( [
            'company_id'=>3,
            'size'=>'72MM',
            'description'=>NULL,
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>3,
        ] );
    }
}
