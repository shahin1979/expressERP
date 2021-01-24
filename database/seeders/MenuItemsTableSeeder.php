<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Common\MenuItem;
//
class MenuItemsTableSeeder extends Seeder
{
//    /**
//     * Run the database seeders.
//     *
//     * @return void
//     */
    public function run()
    {
//        MenuItem::query()->create( [
//            'id'=>10001,
//            'company_id'=>1,
//            'module_id'=>1,
//            'nav_label'=>1,
//            'menu_type'=>'CM',
//            'menu_prefix'=>'1',
//            'name'=>'COMPANY MODULE',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>11000,
//            'company_id'=>1,
//            'module_id'=>1,
//            'nav_label'=>1,
//            'div_class'=>'nav-item has-sub',
//            'i_class'=>'fa fa-sitemap',
//            'menu_type'=>'MM',
//            'menu_prefix'=>'1A',
//            'name'=>'COMPANY BASIC',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>11001,
//            'company_id'=>1,
//            'module_id'=>1,
//            'nav_label'=>1,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'1A',
//            'name'=>'Company Info',
//            'show'=>'1',
//            'url'=>'company/basicIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>11005,
//            'company_id'=>1,
//            'module_id'=>1,
//            'nav_label'=>1,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'1A',
//            'name'=>'Fiscal Period',
//            'show'=>'1',
//            'url'=>'company/fiscalPeriodIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>11015,
//            'company_id'=>1,
//            'module_id'=>1,
//            'nav_label'=>1,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'1A',
//            'name'=>'Activity Logs',
//            'show'=>'1',
//            'url'=>'company/activityLogsIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );


//        MenuItem::query()->create( [
//            'id'=>11020,
//            'company_id'=>1,
//            'module_id'=>1,
//            'nav_label'=>1,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'1A',
//            'name'=>'Database Backup',
//            'show'=>'1',
//            'url'=>'company/databaseBackupIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );

//
//            MenuItem::query()->create( [
//            'id'=>11030,
//            'company_id'=>1,
//            'module_id'=>1,
//            'nav_label'=>1,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'1A',
//            'name'=>'Data Migration',
//            'show'=>'1',
//            'url'=>'company/dataMigrationIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>20001,
//            'company_id'=>1,
//            'module_id'=>2,
//            'nav_label'=>2,
//            'menu_type'=>'CM',
//            'menu_prefix'=>'2',
//            'name'=>'SECURITY MODULE',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>21000,
//            'company_id'=>1,
//            'module_id'=>2,
//            'nav_label'=>2,
//            'div_class'=>'nav-item has-sub',
//            'i_class'=>'fa fa-sitemap',
//            'menu_type'=>'MM',
//            'menu_prefix'=>'2B',
//            'name'=>'USER BASIC',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>21001,
//            'company_id'=>1,
//            'module_id'=>2,
//            'nav_label'=>2,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'2B',
//            'name'=>'Register New User',
//            'show'=>'1',
//            'url'=>'register',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>21005,
//            'company_id'=>1,
//            'module_id'=>2,
//            'nav_label'=>2,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'2B',
//            'name'=>'Update User',
//            'show'=>'1',
//            'url'=>'security/updateUserIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>21010,
//            'company_id'=>1,
//            'module_id'=>2,
//            'nav_label'=>2,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'2B',
//            'name'=>'Change User Password',
//            'show'=>'1',
//            'url'=>'security/changePasswordIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>21015,
//            'company_id'=>1,
//            'module_id'=>2,
//            'nav_label'=>2,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'2B',
//            'name'=>'Reset User Password',
//            'show'=>'1',
//            'url'=>'security/resetPasswordIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>21020,
//            'company_id'=>1,
//            'module_id'=>2,
//            'nav_label'=>2,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'2B',
//            'name'=>'Manage User Permissions',
//            'show'=>'1',
//            'url'=>'security/managePermissionIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>30001,
//            'company_id'=>1,
//            'module_id'=>3,
//            'nav_label'=>3,
//            'menu_type'=>'CM',
//            'menu_prefix'=>'3',
//            'name'=>'HRM MODULE',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>31000,
//            'company_id'=>1,
//            'module_id'=>3,
//            'nav_label'=>3,
//            'div_class'=>'nav-item has-sub',
//            'i_class'=>'fa fa-sitemap',
//            'menu_type'=>'MM',
//            'menu_prefix'=>'3A',
//            'name'=>'ADMINISTRATION',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>31075,
//            'company_id'=>1,
//            'module_id'=>3,
//            'nav_label'=>3,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'3A',
//            'name'=>'Add New Location',
//            'show'=>'1',
//            'url'=>'location/locationIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//
//        MenuItem::query()->create( [
//            'id'=>33000,
//            'company_id'=>1,
//            'module_id'=>3,
//            'nav_label'=>3,
//            'div_class'=>'nav-item has-sub',
//            'i_class'=>'fa fa-sitemap',
//            'menu_type'=>'MM',
//            'menu_prefix'=>'3D',
//            'name'=>'HUMAN RESOURCES',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>33005,
//            'company_id'=>1,
//            'module_id'=>3,
//            'nav_label'=>3,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'3D',
//            'name'=>'Add New Resource',
//            'show'=>'1',
//            'url'=>'employee/empPersonalIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//
//
//
//
//
//        MenuItem::query()->create( [
//            'id'=>40001,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'CM',
//            'menu_prefix'=>'4',
//            'name'=>'ACCOUNTS MODULE',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>42000,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'div_class'=>'nav-item has-sub',
//            'i_class'=>'fa fa-sitemap',
//            'menu_type'=>'MM',
//            'menu_prefix'=>'4A',
//            'name'=>'BASIC DATA SETTINGS',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>42002,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4A',
//            'name'=>'Cost Center',
//            'show'=>'1',
//            'url'=>'costcenter/costCenterIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//
//        MenuItem::query()->create( [
//            'id'=>42005,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4A',
//            'name'=>'Group Ledger',
//            'show'=>'1',
//            'url'=>'ledger/GLGroupIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>42010,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4A',
//            'name'=>'Account Ledger',
//            'show'=>'1',
//            'url'=>'ledger/GLAccountHeadIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>42015,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4A',
//            'name'=>'Bank Information',
//            'show'=>'1',
//            'url'=>'setup/bankInfoIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//
//
//
//        MenuItem::query()->create( [
//            'id'=>42020,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4A',
//            'name'=>'Depreciation Setup',
//            'show'=>'1',
//            'url'=>'ledger/depreciationSetupIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>42025,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4A',
//            'name'=>'Opening Balance',
//            'show'=>'1',
//            'url'=>'ledger/openingBalanceIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//        'id'=>43000,
//        'company_id'=>1,
//        'module_id'=>4,
//        'nav_label'=>4,
//        'div_class'=>'nav-item has-sub',
//        'i_class'=>'fa fa-sitemap',
//        'menu_type'=>'MM',
//        'menu_prefix'=>'4B',
//        'name'=>'ANNUAL BUDGET',
//        'show'=>'1',
//        'url'=>'home',
//        'status'=>'1',
//         'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>43005,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4B',
//            'name'=>'Prepare Budget',
//            'show'=>'1',
//            'url'=>'budget/prepareBudgetIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>44000,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'div_class'=>'nav-item has-sub',
//            'i_class'=>'fa fa-sitemap',
//            'menu_type'=>'MM',
//            'menu_prefix'=>'4D',
//            'name'=>'VOUCHER ENTRY',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>44005,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4D',
//            'name'=>'Payment Voucher',
//            'show'=>'1',
//            'url'=>'transaction/transPaymentIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>44010,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4D',
//            'name'=>'Receive Voucher',
//            'show'=>'1',
//            'url'=>'transaction/transReceiveIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//
//
//
//
//        MenuItem::query()->create( [
//            'id'=>44015,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4D',
//            'name'=>'Journal Voucher',
//            'show'=>'1',
//            'url'=>'transaction/transJournalIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//            MenuItem::query()->create( [
//            'id'=>44020,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4D',
//            'name'=>'Memorandum Voucher',
//            'show'=>'1',
//            'url'=>'transaction/transMemorandumIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//            MenuItem::query()->create( [
//            'id'=>44025,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4D',
//            'name'=>'Edit Un Auth Voucher',
//            'show'=>'1',
//            'url'=>'transaction/editUnAuthVoucherIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>44035,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4D',
//            'name'=>'Authorise Transactions',
//            'show'=>'1',
//            'url'=>'transaction/authoriseTransIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>45000,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'div_class'=>'nav-item has-sub',
//            'i_class'=>'fa fa-sitemap',
//            'menu_type'=>'MM',
//            'menu_prefix'=>'4G',
//            'name'=>'RTGS PAYMENT',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>45005,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4G',
//            'name'=>'RTGS Payment',
//            'show'=>'1',
//            'url'=>'rtgs/rtgsPaymentIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>45010,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4G',
//            'name'=>'Approve RTGS Payment',
//            'show'=>'1',
//            'url'=>'rtgs/rtgsPaymentApproveIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>45015,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4G',
//            'name'=>'Print RTGS Payment Form',
//            'show'=>'1',
//            'url'=>'rtgs/printPaymentFormIndex',
//            'status'=>'1',
//            'content'=>'R'
//        ] );
//
//
//            MenuItem::query()->create( [
//            'id'=>47000,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'div_class'=>'nav-item has-sub',
//            'i_class'=>'fa fa-sitemap',
//            'menu_type'=>'MM',
//            'menu_prefix'=>'4M',
//            'name'=>'ACCOUNTS REPORT',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'R'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>47005,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4M',
//            'name'=>'Daily Transaction List',
//            'show'=>'1',
//            'url'=>'accounts/report/dailyTransactionIndex',
//            'status'=>'1',
//            'content'=>'R'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>47015,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4M',
//            'name'=>'Print Voucher',
//            'show'=>'1',
//            'url'=>'accounts/report/printVoucherIndex',
//            'status'=>'1',
//            'content'=>'R'
//        ] );
//
//
//            MenuItem::query()->create( [
//            'id'=>47020,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4M',
//            'name'=>'Trial Balance',
//            'show'=>'1',
//            'url'=>'accounts/report/rptTrialBalanceIndex',
//            'status'=>'1',
//            'content'=>'R'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>47025,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4M',
//            'name'=>'General Ledger',
//            'show'=>'1',
//            'url'=>'accounts/report/rptGeneralLedgerIndex',
//            'status'=>'1',
//            'content'=>'R'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>47030,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4M',
//            'name'=>'Cost Center Summary',
//            'show'=>'1',
//            'url'=>'accounts/report/rptCostCenterSummary',
//            'status'=>'1',
//            'content'=>'R'
//        ] );
//
//
//
//
//            MenuItem::query()->create( [
//            'id'=>49000,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'div_class'=>'nav-item has-sub',
//            'i_class'=>'fa fa-sitemap',
//            'menu_type'=>'MM',
//            'menu_prefix'=>'4X',
//            'name'=>'FINANCIAL STATEMENT',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'R'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>49002,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4X',
//            'name'=>'Income & Expenditure',
//            'show'=>'1',
//            'url'=>'statement/incomeExpIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//
//        MenuItem::query()->create( [
//            'id'=>49005,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4X',
//            'name'=>'Statement File',
//            'show'=>'1',
//            'url'=>'statement/createFileIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>49015,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4X',
//            'name'=>'Prepare Statement',
//            'show'=>'1',
//            'url'=>'statement/lineStatementIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>49020,
//            'company_id'=>1,
//            'module_id'=>4,
//            'nav_label'=>4,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'4X',
//            'name'=>'Print Statement',
//            'show'=>'1',
//            'url'=>'statement/printStatementIndex',
//            'status'=>'1',
//            'content'=>'R'
//        ] );
//
//
//
//
//        MenuItem::query()->create( [
//            'id'=>50001,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'CM',
//            'menu_prefix'=>'5',
//            'name'=>'INVENTORY MODULE',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>51000,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'div_class'=>'nav-item has-sub',
//            'i_class'=>'fa fa-sitemap',
//            'menu_type'=>'MM',
//            'menu_prefix'=>'5A',
//            'name'=>'PRODUCTS BASIC',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>51005,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5A',
//            'name'=>'Add Product Category',
//            'show'=>'1',
//            'url'=>'product/categoryIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//            MenuItem::query()->create( [
//            'id'=>51010,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5A',
//            'name'=>'Add Sub Category',
//            'show'=>'1',
//            'url'=>'product/subCategoryIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>51015,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5A',
//            'name'=>'Item Units',
//            'show'=>'1',
//            'url'=>'product/itemUnitIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>51020,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5A',
//            'name'=>'Item Brands',
//            'show'=>'1',
//            'url'=>'product/itemBrandIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>51025,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5A',
//            'name'=>'Item Sizes/Length',
//            'show'=>'1',
//            'url'=>'product/itemSizeIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>51030,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5A',
//            'name'=>'Item Colors',
//            'show'=>'1',
//            'url'=>'product/itemColorIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>51035,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5A',
//            'name'=>'Item Models',
//            'show'=>'1',
//            'url'=>'product/itemModelIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>51040,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5A',
//            'name'=>'Manage Stores',
//            'show'=>'1',
//            'url'=>'product/itemGodownIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>51045,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5A',
//            'name'=>'Manage Racks',
//            'show'=>'1',
//            'url'=>'product/itemRackIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>51050,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5A',
//            'name'=>'Add Edit Taxes',
//            'show'=>'1',
//            'url'=>'product/itemTaxIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>51055,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5A',
//            'name'=>'Add Edit Products',
//            'show'=>'1',
//            'url'=>'product/productIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//            MenuItem::query()->create( [
//            'id'=>51060,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5A',
//            'name'=>'Product Ledger Report',
//            'show'=>'1',
//            'url'=>'product/rptProductLedgerIndex',
//            'status'=>'1',
//            'content'=>'R'
//        ] );
//
//
//
//
//
//
//
//
//
//        MenuItem::query()->create( [
//            'id'=>52000,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'div_class'=>'nav-item has-sub',
//            'i_class'=>'fa fa-sitemap',
//            'menu_type'=>'MM',
//            'menu_prefix'=>'5B',
//            'name'=>'REQUISITION',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>52005,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5B',
//            'name'=>'Create Requisition',
//            'show'=>'1',
//            'url'=>'requisition/createReqIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//            MenuItem::query()->create( [
//            'id'=>52015,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5B',
//            'name'=>'Update Requisition',
//            'show'=>'1',
//            'url'=>'requisition/editReqIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>52025,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5B',
//            'name'=>'Approve Requisition',
//            'show'=>'1',
//            'url'=>'requisition/approveReqIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//            MenuItem::query()->create( [
//            'id'=>52050,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5B',
//            'name'=>'Print Requisition',
//            'show'=>'1',
//            'url'=>'requisition/printReqIndex',
//            'status'=>'1',
//            'content'=>'R'
//        ] );
//
//
//
//            MenuItem::query()->create( [
//            'id'=>53000,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'div_class'=>'nav-item has-sub',
//            'i_class'=>'fa fa-sitemap',
//            'menu_type'=>'MM',
//            'menu_prefix'=>'5D',
//            'name'=>'PURCHASE',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>53002,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5D',
//            'name'=>'Supplier Information',
//            'show'=>'1',
//            'url'=>'purchase/supplierInfoIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>53005,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5D',
//            'name'=>'Direct Purchase Product',
//            'show'=>'1',
//            'url'=>'purchase/purchaseProductIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//            MenuItem::query()->create( [
//            'id'=>53015,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5D',
//            'name'=>'Purchase Against Requisition',
//            'show'=>'1',
//            'url'=>'purchase/purchaseRequisitionIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//
//            MenuItem::query()->create( [
//            'id'=>53020,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5D',
//            'name'=>'Edit Purchase',
//            'show'=>'1',
//            'url'=>'purchase/editPurchaseIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>53025,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5D',
//            'name'=>'Approve Purchase',
//            'show'=>'1',
//            'url'=>'purchase/approvePurchaseIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//
//
//
//            MenuItem::query()->create( [
//            'id'=>54000,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'div_class'=>'nav-item has-sub',
//            'i_class'=>'fa fa-sitemap',
//            'menu_type'=>'MM',
//            'menu_prefix'=>'5E',
//            'name'=>'PRODUCTION',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>54002,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5E',
//            'name'=>'Set Current Production',
//            'show'=>'1',
//            'url'=>'production/setCurrentProductionIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>54005,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5E',
//            'name'=>'Production Line One',
//            'show'=>'1',
//            'url'=>'production/productionFromLineOneIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>54008,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5E',
//            'name'=>'Production Line Two',
//            'show'=>'1',
//            'url'=>'production/productionFromLineTwoIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>54010,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5E',
//            'name'=>'Production From factory',
//            'show'=>'1',
//            'url'=>'production/productionFromFactoryIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>54014,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5E',
//            'name'=>'Edit Production',
//            'show'=>'1',
//            'url'=>'production/editProductionFromFactoryIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>54018,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5E',
//            'name'=>'Transform Production Item',
//            'show'=>'1',
//            'url'=>'production/transformProductionItemIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//
//
//        MenuItem::query()->create( [
//            'id'=>55000,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'div_class'=>'nav-item has-sub',
//            'i_class'=>'fa fa-sitemap',
//            'menu_type'=>'MM',
//            'menu_prefix'=>'5F',
//            'name'=>'RECEIVES',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>55002,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5F',
//            'name'=>'Receive Against Purchase',
//            'show'=>'1',
//            'url'=>'receive/receivePurchaseIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>55010,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5F',
//            'name'=>'Approve Receive',
//            'show'=>'1',
//            'url'=>'receive/receiveApproveIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>55015,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5F',
//            'name'=>'Return After Receive',
//            'show'=>'1',
//            'url'=>'receive/returnAfterReceiveIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//
//
//        MenuItem::query()->create( [
//            'id'=>56000,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'div_class'=>'nav-item has-sub',
//            'i_class'=>'fa fa-sitemap',
//            'menu_type'=>'MM',
//            'menu_prefix'=>'5G',
//            'name'=>'SALES',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>56002,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5G',
//            'name'=>'Customer Information',
//            'show'=>'1',
//            'url'=>'sales/customerInfoIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>56004,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5G',
//            'name'=>'Sale Items Rate',
//            'show'=>'1',
//            'url'=>'sales/salesRateIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>56006,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5G',
//            'name'=>'Approve Sales Rate',
//            'show'=>'1',
//            'url'=>'sales/approveSalesRateIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>56008,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5G',
//            'name'=>'Create Sales Invoice',
//            'show'=>'1',
//            'url'=>'sales/createSalesInvoiceIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>56010,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5G',
//            'name'=>'Edit Sales Invoice',
//            'show'=>'1',
//            'url'=>'sales/EditSalesInvoiceIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>56012,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5G',
//            'name'=>'Approve Sales Invoice',
//            'show'=>'1',
//            'url'=>'sales/ApproveSalesInvoiceIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>56030,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5G',
//            'name'=>'Print Sales Invoice',
//            'show'=>'1',
//            'url'=>'sales/printSalesInvoiceIndex',
//            'status'=>'1',
//            'content'=>'R'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>57000,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'div_class'=>'nav-item has-sub',
//            'i_class'=>'fa fa-sitemap',
//            'menu_type'=>'MM',
//            'menu_prefix'=>'5H',
//            'name'=>'DELIVERY ITEMS',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>57002,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5H',
//            'name'=>'Delivery By Sales Invoice',
//            'show'=>'1',
//            'url'=>'delivery/salesInvoiceDeliveryIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>57004,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5H',
//            'name'=>'Delivery By Customer Invoice',
//            'show'=>'1',
//            'url'=>'delivery/customerInvoiceDeliveryIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>57008,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5H',
//            'name'=>'Delivery Requisition Items',
//            'show'=>'1',
//            'url'=>'delivery/requisitionItemDeliveryIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>57010,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5H',
//            'name'=>'Approve Delivery Items',
//            'show'=>'1',
//            'url'=>'delivery/approveDeliveryIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//                // EXPORT IMPORT MODULE
//
//
////
//        MenuItem::query()->create( [
//            'id'=>58000,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'div_class'=>'nav-item has-sub',
//            'i_class'=>'fa fa-sitemap',
//            'menu_type'=>'MM',
//            'menu_prefix'=>'5J',
//            'name'=>'EXPORT',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
////
//        MenuItem::query()->create( [
//            'id'=>58005,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5J',
//            'name'=>'New Export Contract',
//            'show'=>'1',
//            'url'=>'export/exportContractIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//
//        MenuItem::query()->create( [
//            'id'=>58007,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5J',
//            'name'=>'Edit Export Contract',
//            'show'=>'1',
//            'url'=>'export/editExportContractIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>58010,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5J',
//            'name'=>'Approved Export Contract',
//            'show'=>'1',
//            'url'=>'export/ApproveExportContractIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//
//        MenuItem::query()->create( [
//            'id'=>58015,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5J',
//            'name'=>'Delivery Export Products',
//            'show'=>'1',
//            'url'=>'export/deliveryExportProductIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//
//
//        MenuItem::query()->create( [
//            'id'=>58020,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5J',
//            'name'=>'Create Export Invoice',
//            'show'=>'1',
//            'url'=>'export/createExportInvoiceIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//
//
//        MenuItem::query()->create( [
//            'id'=>58025,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5J',
//            'name'=>'Approve Export Invoice',
//            'show'=>'1',
//            'url'=>'export/approveExportInvoiceIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>58030,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5J',
//            'name'=>'Approve Export Delivery',
//            'show'=>'1',
//            'url'=>'export/approveExportDeliveryIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//
//        MenuItem::query()->create( [
//            'id'=>58035,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5J',
//            'name'=>'Update Truck Number For Delivery',
//            'show'=>'1',
//            'url'=>'export/updateVehicleNoIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>58040,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5J',
//            'name'=>'Upload Shipment File',
//            'show'=>'1',
//            'url'=>'export/uploadShipmentFileIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>58045,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5J',
//            'name'=>'Update Shipment Data',
//            'show'=>'1',
//            'url'=>'export/updateShipmentDataIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>58050,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5J',
//            'name'=>'Shipment Container Data',
//            'show'=>'1',
//            'url'=>'export/shipmentContainerDataIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>58075,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5J',
//            'name'=>'Export Reports',
//            'show'=>'1',
//            'url'=>'export/report/exportReportsIndex',
//            'status'=>'1',
//            'content'=>'R'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>58077,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5J',
//            'name'=>'Print Commercial Invoice',
//            'show'=>'0',
//            'url'=>'export/report/exportCommercialInvoiceReportIndex',
//            'status'=>'1',
//            'content'=>'R'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>58079,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5J',
//            'name'=>'Print Packing List',
//            'show'=>'0',
//            'url'=>'export/report/exportPackingListIndex',
//            'status'=>'1',
//            'content'=>'R'
//        ] );
//
//
//
//
//


//        MenuItem::query()->create( [
//            'id'=>51100,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'div_class'=>'nav-item has-sub',
//            'i_class'=>'fa fa-sitemap',
//            'menu_type'=>'MM',
//            'menu_prefix'=>'5M',
//            'name'=>'IMPORT',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>51105,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5M',
//            'name'=>'Open Import LC',
//            'show'=>'1',
//            'url'=>'inventory/import/OpenImportLCIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//


//        MenuItem::query()->create( [
//            'id'=>51110,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5M',
//            'name'=>'Approve Import LC',
//            'show'=>'1',
//            'url'=>'inventory/import/ApproveImportLCIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );


//        MenuItem::query()->create( [
//            'id'=>59000,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'div_class'=>'nav-item has-sub',
//            'i_class'=>'fa fa-sitemap',
//            'menu_type'=>'MM',
//            'menu_prefix'=>'5K',
//            'name'=>'INVENTORY REPORTS',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'R'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>59010,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5K',
//            'name'=>'Product Movement Register',
//            'show'=>'1',
//            'url'=>'inventory/report/productMovementRegister',
//            'status'=>'1',
//            'content'=>'R'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>59015,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5K',
//            'name'=>'Stock Position',
//            'show'=>'1',
//            'url'=>'inventory/report/rptStockPositionIndex',
//            'status'=>'1',
//            'content'=>'R'
//        ] );
//
//
//        MenuItem::query()->create( [
//            'id'=>59020,
//            'company_id'=>1,
//            'module_id'=>5,
//            'nav_label'=>5,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'5K',
//            'name'=>'Sales Register Report',
//            'show'=>'1',
//            'url'=>'inventory/report/rptSalesRegisterIndex',
//            'status'=>'1',
//            'content'=>'R'
//        ] );
////
//
//
//
//
//
//
//        MenuItem::query()->create( [
//            'id'=>90001,
//            'company_id'=>1,
//            'module_id'=>9,
//            'nav_label'=>9,
//            'menu_type'=>'CM',
//            'menu_prefix'=>'9',
//            'name'=>'PROJECTS MODULE',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//
//
//        MenuItem::query()->create( [
//            'id'=>92000,
//            'company_id'=>1,
//            'module_id'=>9,
//            'nav_label'=>9,
//            'div_class'=>'nav-item has-sub',
//            'i_class'=>'fa fa-sitemap',
//            'menu_type'=>'MM',
//            'menu_prefix'=>'9A',
//            'name'=>'PROJECT DATA',
//            'show'=>'1',
//            'url'=>'home',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
//
//        MenuItem::query()->create( [
//            'id'=>92005,
//            'company_id'=>1,
//            'module_id'=>9,
//            'nav_label'=>9,
//            'menu_type'=>'SM',
//            'menu_prefix'=>'9A',
//            'name'=>'New Project',
//            'show'=>'1',
//            'url'=>'projects/basic/newProjectIndex',
//            'status'=>'1',
//            'content'=>'F'
//        ] );
    }
}
