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
//        MENUITEM::create( [
//            'ID'=>10001,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>1,
//            'NAV_LABEL'=>1,
//            'MENU_TYPE'=>'CM',
//            'MENU_PREFIX'=>'1',
//            'NAME'=>'COMPANY MODULE',
//            'SHOW'=>'1',
//            'URL'=>'home',
//            'STATUS'=>'1'
//        ] );
//
//        MENUITEM::create( [
//            'ID'=>11000,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>1,
//            'NAV_LABEL'=>1,
//            'DIV_CLASS'=>'nav-item has-sub',
//            'I_CLASS'=>'fa fa-sitemap',
//            'MENU_TYPE'=>'MM',
//            'MENU_PREFIX'=>'1A',
//            'NAME'=>'COMPANY BASIC',
//            'SHOW'=>'1',
//            'URL'=>'home',
//            'STATUS'=>'1'
//        ] );
//
//        MENUITEM::create( [
//            'ID'=>11001,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>1,
//            'NAV_LABEL'=>1,
//            'MENU_TYPE'=>'SM',
//            'MENU_PREFIX'=>'1A',
//            'NAME'=>'Company Info',
//            'SHOW'=>'1',
//            'URL'=>'company/basicIndex',
//            'STATUS'=>'1'
//        ] );

//        MENUITEM::create( [
//            'ID'=>11005,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>1,
//            'NAV_LABEL'=>1,
//            'MENU_TYPE'=>'SM',
//            'MENU_PREFIX'=>'1A',
//            'NAME'=>'Fiscal Period',
//            'SHOW'=>'1',
//            'URL'=>'company/fiscalPeriodIndex',
//            'STATUS'=>'1'
//        ] );

//
//        MENUITEM::create( [
//            'ID'=>20001,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>2,
//            'NAV_LABEL'=>2,
//            'MENU_TYPE'=>'CM',
//            'MENU_PREFIX'=>'2',
//            'MENU_ID'=>'BC0',
//            'NAME'=>'SECURITY MODULE',
//            'SHOW'=>'1',
//            'URL'=>'home',
//            'STATUS'=>'1'
//        ] );
//
//        MENUITEM::create( [
//            'ID'=>21000,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>2,
//            'NAV_LABEL'=>2,
//            'DIV_CLASS'=>'nav-item has-sub',
//            'I_CLASS'=>'fa fa-sitemap',
//            'MENU_TYPE'=>'MM',
//            'MENU_PREFIX'=>'2A',
//            'NAME'=>'USER BASIC',
//            'SHOW'=>'1',
//            'URL'=>'home',
//            'STATUS'=>'1'
//        ] );
//
//        MENUITEM::create( [
//            'ID'=>21001,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>2,
//            'NAV_LABEL'=>2,
//            'MENU_TYPE'=>'SM',
//            'MENU_PREFIX'=>'2A',
//            'NAME'=>'Add User',
//            'SHOW'=>'1',
//            'URL'=>'register',
//            'STATUS'=>'1'
//        ] );


//        MENUITEM::create( [
//            'ID'=>30001,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>3,
//            'NAV_LABEL'=>3,
//            'MENU_TYPE'=>'CM',
//            'MENU_PREFIX'=>'3',
//            'NAME'=>'HRM MODULE',
//            'SHOW'=>'1',
//            'URL'=>'home',
//            'STATUS'=>'1'
//        ] );


//        MENUITEM::create( [
//            'ID'=>31000,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>3,
//            'NAV_LABEL'=>3,
//            'DIV_CLASS'=>'nav-item has-sub',
//            'I_CLASS'=>'fa fa-sitemap',
//            'MENU_TYPE'=>'MM',
//            'MENU_PREFIX'=>'3A',
//            'NAME'=>'ADMINISTRATION',
//            'SHOW'=>'1',
//            'URL'=>'home',
//            'STATUS'=>'1'
//        ] );


//        MENUITEM::create( [
//            'ID'=>33000,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>3,
//            'NAV_LABEL'=>3,
//            'DIV_CLASS'=>'nav-item has-sub',
//            'I_CLASS'=>'fa fa-sitemap',
//            'MENU_TYPE'=>'MM',
//            'MENU_PREFIX'=>'3D',
//            'NAME'=>'HUMAN RESOURCES',
//            'SHOW'=>'1',
//            'URL'=>'home',
//            'STATUS'=>'1'
//        ] );
//
//        MENUITEM::create( [
//            'ID'=>33005,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>3,
//            'NAV_LABEL'=>3,
//            'MENU_TYPE'=>'SM',
//            'MENU_PREFIX'=>'3D',
//            'NAME'=>'Add New Resource',
//            'SHOW'=>'1',
//            'URL'=>'home',
//            'STATUS'=>'1'
//        ] );




//

//
//        MENUITEM::create( [
//            'ID'=>40001,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>4,
//            'NAV_LABEL'=>4,
//            'MENU_TYPE'=>'CM',
//            'MENU_PREFIX'=>'4',
//            'NAME'=>'ACCOUNTS MODULE',
//            'SHOW'=>'1',
//            'URL'=>'home',
//            'STATUS'=>'1'
//        ] );
//
//        MENUITEM::create( [
//            'ID'=>42000,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>4,
//            'NAV_LABEL'=>4,
//            'DIV_CLASS'=>'nav-item has-sub',
//            'I_CLASS'=>'fa fa-sitemap',
//            'MENU_TYPE'=>'MM',
//            'MENU_PREFIX'=>'4A',
//            'NAME'=>'LEDGER ENTRY',
//            'SHOW'=>'1',
//            'URL'=>'home',
//            'STATUS'=>'1'
//        ] );
//
//        MENUITEM::create( [
//            'ID'=>42005,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>4,
//            'NAV_LABEL'=>4,
//            'MENU_TYPE'=>'SM',
//            'MENU_PREFIX'=>'4A',
//            'NAME'=>'Group Ledger',
//            'SHOW'=>'1',
//            'URL'=>'home',
//            'STATUS'=>'1'
//        ] );
//
//        MENUITEM::create( [
//            'ID'=>4220,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>4,
//            'NAV_LABEL'=>4,
//            'MENU_TYPE'=>'SM',
//            'MENU_PREFIX'=>'4A',
//            'NAME'=>'Depreciation Setup',
//            'SHOW'=>'1',
//            'URL'=>'home',
//            'STATUS'=>'1'
//        ] );
//
//        MENUITEM::create( [
//            'ID'=>42015,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>4,
//            'NAV_LABEL'=>4,
//            'MENU_TYPE'=>'SM',
//            'MENU_PREFIX'=>'4A',
//            'NAME'=>'Opening Balance',
//            'SHOW'=>'1',
//            'URL'=>'home',
//            'STATUS'=>'1'
//        ] );
//
//        MENUITEM::create( [
//            'ID'=>42010,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>4,
//            'NAV_LABEL'=>4,
//            'MENU_TYPE'=>'SM',
//            'MENU_PREFIX'=>'4A',
//            'NAME'=>'Account Ledger',
//            'SHOW'=>'1',
//            'URL'=>'ledger/GLAccountHeadIndex',
//            'STATUS'=>'1'
//        ] );

//        MENUITEM::create( [
//            'ID'=>44000,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>4,
//            'NAV_LABEL'=>4,
//            'DIV_CLASS'=>'nav-item has-sub',
//            'I_CLASS'=>'fa fa-sitemap',
//            'MENU_TYPE'=>'MM',
//            'MENU_PREFIX'=>'4D',
//            'NAME'=>'VOUCHER ENTRY',
//            'SHOW'=>'1',
//            'URL'=>'home',
//            'STATUS'=>'1'
//        ] );

//        MENUITEM::create( [
//            'ID'=>44005,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>4,
//            'NAV_LABEL'=>4,
//            'MENU_TYPE'=>'SM',
//            'MENU_PREFIX'=>'4D',
//            'NAME'=>'Payment Voucher',
//            'SHOW'=>'1',
//            'URL'=>'transaction/transPaymentIndex',
//            'STATUS'=>'1'
//        ] );

//        MENUITEM::create( [
//            'ID'=>44010,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>4,
//            'NAV_LABEL'=>4,
//            'MENU_TYPE'=>'SM',
//            'MENU_PREFIX'=>'4D',
//            'NAME'=>'Receive Voucher',
//            'SHOW'=>'1',
//            'URL'=>'transaction/transReceiveIndex',
//            'STATUS'=>'1'
//        ] );

//        MENUITEM::create( [
//            'ID'=>44015,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>4,
//            'NAV_LABEL'=>4,
//            'MENU_TYPE'=>'SM',
//            'MENU_PREFIX'=>'4D',
//            'NAME'=>'Journal Voucher',
//            'SHOW'=>'1',
//            'URL'=>'transaction/transJournalIndex',
//            'STATUS'=>'1'
//        ] );

//            MENUITEM::create( [
//            'ID'=>44025,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>4,
//            'NAV_LABEL'=>4,
//            'MENU_TYPE'=>'SM',
//            'MENU_PREFIX'=>'4D',
//            'NAME'=>'Edit Un Auth Voucher',
//            'SHOW'=>'1',
//            'URL'=>'transaction/editUnAuthVoucherIndex',
//            'STATUS'=>'1'
//        ] );




            MENUITEM::create( [
            'ID'=>47000,
            'COMPANY_ID'=>1,
            'MODULE_ID'=>4,
            'NAV_LABEL'=>4,
            'DIV_CLASS'=>'nav-item has-sub',
            'I_CLASS'=>'fa fa-sitemap',
            'MENU_TYPE'=>'MM',
            'MENU_PREFIX'=>'4M',
            'NAME'=>'TRANSACTION REPORT',
            'SHOW'=>'1',
            'URL'=>'home',
            'STATUS'=>'1'
        ] );

        MENUITEM::create( [
            'ID'=>47005,
            'COMPANY_ID'=>1,
            'MODULE_ID'=>4,
            'NAV_LABEL'=>4,
            'MENU_TYPE'=>'SM',
            'MENU_PREFIX'=>'4M',
            'NAME'=>'Daily Transaction List',
            'SHOW'=>'1',
            'URL'=>'home',
            'STATUS'=>'1'
        ] );



//        MENUITEM::create( [
//            'ID'=>50001,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>5,
//            'NAV_LABEL'=>5,
//            'MENU_TYPE'=>'CM',
//            'MENU_PREFIX'=>'5',
//            'NAME'=>'INVENTORY MODULE',
//            'SHOW'=>'1',
//            'URL'=>'home',
//            'STATUS'=>'1'
//        ] );
//
//        MENUITEM::create( [
//            'ID'=>52000,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>5,
//            'NAV_LABEL'=>5,
//            'DIV_CLASS'=>'nav-item has-sub',
//            'I_CLASS'=>'fa fa-sitemap',
//            'MENU_TYPE'=>'MM',
//            'MENU_PREFIX'=>'5A',
//            'NAME'=>'BASIC DATA',
//            'SHOW'=>'1',
//            'URL'=>'home',
//            'STATUS'=>'1'
//        ] );
//
//        MENUITEM::create( [
//            'ID'=>52005,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>5,
//            'NAV_LABEL'=>5,
//            'MENU_TYPE'=>'SM',
//            'MENU_PREFIX'=>'5A',
//            'NAME'=>'Add Product Category',
//            'SHOW'=>'1',
//            'URL'=>'home',
//            'STATUS'=>'1'
//        ] );




//        MENUITEM::create( [
//            'ID'=>90001,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>9,
//            'NAV_LABEL'=>9,
//            'MENU_TYPE'=>'CM',
//            'MENU_PREFIX'=>'9',
//            'NAME'=>'PROJECTS MODULE',
//            'SHOW'=>'1',
//            'URL'=>'home',
//            'STATUS'=>'1'
//        ] );
//
//        MENUITEM::create( [
//            'ID'=>92000,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>9,
//            'NAV_LABEL'=>9,
//            'DIV_CLASS'=>'nav-item has-sub',
//            'I_CLASS'=>'fa fa-sitemap',
//            'MENU_TYPE'=>'MM',
//            'MENU_PREFIX'=>'9A',
//            'NAME'=>'PROJECT DATA',
//            'SHOW'=>'1',
//            'URL'=>'home',
//            'STATUS'=>'1'
//        ] );
//
//        MENUITEM::create( [
//            'ID'=>92005,
//            'COMPANY_ID'=>1,
//            'MODULE_ID'=>9,
//            'NAV_LABEL'=>9,
//            'MENU_TYPE'=>'SM',
//            'MENU_PREFIX'=>'9A',
//            'NAME'=>'New Project',
//            'SHOW'=>'1',
//            'URL'=>'home',
//            'STATUS'=>'1'
//        ] );
    }
}
