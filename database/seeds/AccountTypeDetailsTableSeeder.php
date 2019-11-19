<?php

use Illuminate\Database\Seeder;
use App\Models\Accounts\Common\AccountTypeDetail;

class AccountTypeDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        AccountTypeDetail::create( [
            'ID'=>1,
            'ACCOUNT_TYPE_ID'=>1,
            'TYPE_CODE'=>11,
            'DESCRIPTION'=>'Fixed Asset'
        ] );



        AccountTypeDetail::create( [
            'ID'=>2,
            'ACCOUNT_TYPE_ID'=>1,
            'TYPE_CODE'=>12,
            'DESCRIPTION'=>'Current Asset'
        ] );



        AccountTypeDetail::create( [
            'ID'=>3,
            'ACCOUNT_TYPE_ID'=>1,
            'TYPE_CODE'=>13,
            'DESCRIPTION'=>'Accounts Receivable'
        ] );



        AccountTypeDetail::create( [
            'ID'=>4,
            'ACCOUNT_TYPE_ID'=>2,
            'TYPE_CODE'=>21,
            'DESCRIPTION'=>'Short Term Debt'
        ] );



        AccountTypeDetail::create( [
            'ID'=>5,
            'ACCOUNT_TYPE_ID'=>2,
            'TYPE_CODE'=>22,
            'DESCRIPTION'=>'Long Term Debt'
        ] );



        AccountTypeDetail::create( [
            'ID'=>6,
            'ACCOUNT_TYPE_ID'=>2,
            'TYPE_CODE'=>23,
            'DESCRIPTION'=>'Accounts Payable'
        ] );



        AccountTypeDetail::create( [
            'ID'=>7,
            'ACCOUNT_TYPE_ID'=>3,
            'TYPE_CODE'=>31,
            'DESCRIPTION'=>'Sales'
        ] );



        AccountTypeDetail::create( [
            'ID'=>8,
            'ACCOUNT_TYPE_ID'=>3,
            'TYPE_CODE'=>32,
            'DESCRIPTION'=>'Services Fees'
        ] );



        AccountTypeDetail::create( [
            'ID'=>9,
            'ACCOUNT_TYPE_ID'=>3,
            'TYPE_CODE'=>33,
            'DESCRIPTION'=>'Direct Income'
        ] );



        AccountTypeDetail::create( [
            'ID'=>10,
            'ACCOUNT_TYPE_ID'=>3,
            'TYPE_CODE'=>34,
            'DESCRIPTION'=>'Indirect Income'
        ] );



        AccountTypeDetail::create( [
            'ID'=>11,
            'ACCOUNT_TYPE_ID'=>4,
            'TYPE_CODE'=>41,
            'DESCRIPTION'=>'Direct Expense'
        ] );
        AccountTypeDetail::create( [
            'ID'=>12,
            'ACCOUNT_TYPE_ID'=>4,
            'TYPE_CODE'=>42,
            'DESCRIPTION'=>'Indirect Expense'
        ] );

        AccountTypeDetail::create( [
            'ID'=>13,
            'ACCOUNT_TYPE_ID'=>5,
            'TYPE_CODE'=>51,
            'DESCRIPTION'=>'Capital'
        ] );

        AccountTypeDetail::create( [
            'ID'=>14,
            'ACCOUNT_TYPE_ID'=>5,
            'TYPE_CODE'=>52,
            'DESCRIPTION'=>'Retained Earnings'
        ] );
    }
}
