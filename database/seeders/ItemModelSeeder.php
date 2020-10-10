<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Inventory\Product\ItemModel;
use Illuminate\Support\Facades\DB;

class ItemModelSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Itemmodel::create( [
            'company_id'=>1,
            'name'=>'0.9D',
            'description'=>'0.9D',
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>4,
        ] );

        Itemmodel::create( [
            'company_id'=>1,
            'name'=>'1.0D',
            'description'=>'1D',
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>4,
        ] );



        Itemmodel::create( [
            'company_id'=>1,
            'name'=>'1.2D',
            'description'=>'1.2D',
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>4,
        ] );



        Itemmodel::create( [
            'company_id'=>1,
            'name'=>'1.4D',
            'description'=>'1.4D',
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>4,
        ] );



        Itemmodel::create( [
            'company_id'=>1,
            'name'=>'2.0D',
            'description'=>'2.0D',
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>4,
        ] );



        Itemmodel::create( [
            'company_id'=>1,
            'name'=>'2.5D',
            'description'=>'2.5D',
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>4,
        ] );



        Itemmodel::create( [
            'company_id'=>1,
            'name'=>'3.0D',
            'description'=>'3.0D',
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>4,
        ] );



        Itemmodel::create( [
            'company_id'=>1,
            'name'=>'6.0D',
            'description'=>'6.0D',
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>4,
        ] );



        Itemmodel::create( [
            'company_id'=>1,
            'name'=>'7.0D',
            'description'=>'7.0D',
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>4,
        ] );



        Itemmodel::create( [
            'company_id'=>1,
            'name'=>'15.0D',
            'description'=>'15.0D',
            'status'=>1,
            'locale'=>'en-US',
            'user_id'=>4,
        ] );
    }
}
