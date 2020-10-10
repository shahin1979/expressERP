<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Accounts\Common\AccountTypeDetail;

class AccountTypeDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {

        AccountTypeDetail::create( [
            'id'=>10,
            'account_type_id'=>1,
            'type_code'=>11,
            'description'=>'Fixed Asset'
        ] );

        AccountTypeDetail::create( [
            'id'=>13,
            'account_type_id'=>1,
            'type_code'=>13,
            'description'=>'Liquidity'
        ] );



        AccountTypeDetail::create( [
            'id'=>12,
            'account_type_id'=>1,
            'type_code'=>12,
            'description'=>'Current Asset'
        ] );



        AccountTypeDetail::create( [
            'id'=>14,
            'account_type_id'=>1,
            'type_code'=>14,
            'description'=>'Accounts Receivable'
        ] );



        AccountTypeDetail::create( [
            'id'=>40,
            'account_type_id'=>2,
            'type_code'=>21,
            'description'=>'Short Term Debt'
        ] );



        AccountTypeDetail::create( [
            'id'=>41,
            'account_type_id'=>2,
            'type_code'=>22,
            'description'=>'Long Term Debt'
        ] );



        AccountTypeDetail::create( [
            'id'=>42,
            'account_type_id'=>2,
            'type_code'=>23,
            'description'=>'Accounts Payable'
        ] );

        AccountTypeDetail::create( [
            'id'=>43,
            'account_type_id'=>2,
            'type_code'=>24,
            'description'=>'Stock Inventory'
        ] );



        AccountTypeDetail::create( [
            'id'=>81,
            'account_type_id'=>3,
            'type_code'=>31,
            'description'=>'Sales'
        ] );



        AccountTypeDetail::create( [
            'id'=>82,
            'account_type_id'=>3,
            'type_code'=>32,
            'description'=>'Services Fees'
        ] );



        AccountTypeDetail::create( [
            'id'=>84,
            'account_type_id'=>3,
            'type_code'=>33,
            'description'=>'Direct Income'
        ] );



        AccountTypeDetail::create( [
            'id'=>86,
            'account_type_id'=>3,
            'type_code'=>34,
            'description'=>'Indirect Income'
        ] );



        AccountTypeDetail::create( [
            'id'=>90,
            'account_type_id'=>4,
            'type_code'=>41,
            'description'=>'Direct Expense'
        ] );
        AccountTypeDetail::create( [
            'id'=>91,
            'account_type_id'=>4,
            'type_code'=>42,
            'description'=>'Indirect Expense'
        ] );

        AccountTypeDetail::create( [
            'id'=>92,
            'account_type_id'=>5,
            'type_code'=>51,
            'description'=>'Capital'
        ] );

        AccountTypeDetail::create( [
            'id'=>94,
            'account_type_id'=>5,
            'type_code'=>52,
            'description'=>'Retained Earnings'
        ] );
    }
}
