<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Inventory\Product\ItemSize;

class ItemSizeTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {


        ItemSize::query()->create( [
            'company_id'=>1,
            'size'=>'24MM',
            'description'=>'24MM',
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>4,
        ] );



        ItemSize::query()->create( [
            'company_id'=>1,
            'size'=>'32MM',
            'description'=>NULL,
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>4,
        ] );



        ItemSize::query()->create( [
            'company_id'=>1,
            'size'=>'38MM',
            'description'=>NULL,
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>4,
        ] );



        ItemSize::query()->create( [
            'company_id'=>1,
            'size'=>'51MM',
            'description'=>NULL,
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>4,
        ] );



        ItemSize::query()->create( [
            'company_id'=>1,
            'size'=>'62MM',
            'description'=>NULL,
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>4,
        ] );



        ItemSize::query()->create( [
            'company_id'=>1,
            'size'=>'64MM',
            'description'=>NULL,
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>4,
        ] );



        ItemSize::query()->create( [
            'company_id'=>1,
            'size'=>'72MM',
            'description'=>NULL,
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>4,
        ] );
    }
}
