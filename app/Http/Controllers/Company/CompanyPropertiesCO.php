<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Common\AppModule;
use App\Models\Company\CompanyModule;
use App\Models\Company\FiscalPeriod;
use App\Models\Company\TransCode;
use App\Models\Common\Country;
use App\Models\Company\Company;
use App\Models\Company\CompanyProperty;
use App\Traits\PreviousDataMigrationTrait;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyPropertiesCO extends Controller
{
    use TransactionsTrait, PreviousDataMigrationTrait, TransactionsTrait;

    public function index()
    {
        $basic = CompanyProperty::query()->where('company_id', $this->company_id)->first();
        $comp = Company::query()->where('id', $this->company_id)->first();
        $currencies = Country::query()->pluck('country_code', 'country_name');
        $modules = AppModule::query()->get();
        $comp_modules = CompanyModule::query()->where('company_id', $this->company_id)->get();


//        $country = geoip_country_name_by_name('www.matincottonmills.com');
        return view('company.company-basic-data-index', compact('comp', 'basic', 'currencies', 'modules', 'comp_modules'));
    }

    public function store(Request $request)
    {

        $fiscal_period = $this->create_fiscal_year($request['fp_start']);

//        dd($request->all());

        DB::beginTransaction();

        $currency = 'BDT';

        try {

            CompanyProperty::query()->updateOrCreate(
                ['company_id' => $this->company_id],
                [
                    'default_cash' => $request['default_cash'],
                    'default_purchase' => $request['default_purchase'],
                    'default_sales' => $request['default_sales'],
                    'advance_sales' => $request['advance_sales'],
                    'default_sales_tax' => $request['default_sales_tax'],
                    'default_purchase_tax' => $request['default_purchase_tax'],
                    'discount_sales' => $request['discount_sales'],
                    'discount_purchase' => $request['discount_purchase'],
                    'consumable_on_hand' => $request['consumable_on_hand'],
                    'consumable_expense' => $request['consumable_expense'],
                    'rm_in_hand' => $request['rm_in_hand'],
                    'work_in_progress' => $request['work_in_progress'],
                    'finished_goods' => $request['finished_goods'],
                    'cost_of_goods_sold' => $request['cost_of_goods_sold'],
                    'depreciation_account' => $request['depreciation_account'],
                    'depreciation_frequency' => $request['depreciation_frequency'],
                    'inventory' => $request->has('hInventory') ? 1 : 0,
                    'project' => $request->has('hProject') ? 1 : 0,
                    'auto_ledger' => $request->has('hAuto_ledger') ? 1 : 0,
                    'auto_delivery' => $request->has('auto_delivery') ? 1 : 0,
                    'cost_center' => $request->has('cost_center') ? 1 : 0,
                    'fp_start' => Carbon::createFromFormat('d-m-Y', $request['fp_start'])->format('Y-m-d'),
                    'trans_min_date' => Carbon::createFromFormat('d-m-Y', $request['fp_start'])->format('Y-m-d'),
                    'currency' => $currency,
                    'posted' => 1
                ]
            );

            // delete existing company modules data
            CompanyModule::query()->where('company_id', $this->company_id)->delete();

            //insert new modules for the company
            foreach ($request['module_id'] as $row) {
                CompanyModule::query()->updateOrCreate(['company_id' => $this->company_id, 'module_id' => $row],
                    [
                        'status' => true
                    ]);
            }


            if ($request->hasfile('company_logo')) {
                $file = $request->file('company_logo');
                $name = $this->company_id . '_logo.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/company/', $name);

                CompanyProperty::query()->where('company_id', $this->company_id)->update(['company_logo' => 'company/' . $name]);
            }


            // ADD TRANSACTION TYPES AND RELATED VOUCHER NO

            if ($request['posted'] != 1) {

                $year = '2019'; // Carbon::now()->format('Y');
                $this->trCode($this->company_id, $year);
                $this->create_fiscal_period($this->company_id,'01-07-2019');
            }

            //ADD INITIAL ACCOUNTS OF THE GENERAL LEDGER

            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '10112100'],
                [
                    'ledger_code' => '101',
                    'acc_name' => 'CASH ON HAND',
                    'acc_type' => 'A',
                    'type_code' => 12,
                    'acc_range' => '10112999',
                    'is_group' => true,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );

            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '10112102'],
                [
                    'ledger_code' => '101',
                    'acc_name' => 'Cash In Hand',
                    'acc_type' => 'A',
                    'type_code' => 12,
                    'acc_range' => '10112102',
                    'is_group' => false,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );

            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '10212100'],
                [
                    'ledger_code' => '102',
                    'acc_name' => 'CASH AT BANK',
                    'acc_type' => 'A',
                    'type_code' => 12,
                    'acc_range' => '10212999',
                    'is_group' => true,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );


            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '10312100'],
                [
                    'ledger_code' => '103',
                    'acc_name' => 'ACCOUNT RECEIVABLE',
                    'acc_type' => 'A',
                    'type_code' => 12,
                    'acc_range' => '10312999',
                    'is_group' => true,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );


            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '10412100'],
                [
                    'ledger_code' => '104',
                    'acc_name' => 'STOCK IN HAND',
                    'acc_type' => 'A',
                    'type_code' => 12,
                    'acc_range' => '10412999',
                    'is_group' => true,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );


            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '10412102'],
                [
                    'ledger_code' => '104',
                    'acc_name' => 'Consumable On Hand',
                    'acc_type' => 'A',
                    'type_code' => 12,
                    'acc_range' => '10412102',
                    'is_group' => false,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );


            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '10412104'],
                [
                    'ledger_code' => '104',
                    'acc_name' => 'RM In Hand',
                    'acc_type' => 'A',
                    'type_code' => 12,
                    'acc_range' => '10412104',
                    'is_group' => false,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );


            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '10412106'],
                [
                    'ledger_code' => '104',
                    'acc_name' => 'Work In Progress',
                    'acc_type' => 'A',
                    'type_code' => 12,
                    'acc_range' => '10412106',
                    'is_group' => false,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );


            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '10412110'],
                [
                    'ledger_code' => '104',
                    'acc_name' => 'Finished Goods Inventory',
                    'acc_type' => 'A',
                    'type_code' => 12,
                    'acc_range' => '10412110',
                    'is_group' => false,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );

            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '20112100'],
                [
                    'ledger_code' => '201',
                    'acc_name' => 'PURCHASE ACCOUNT',
                    'acc_type' => 'L',
                    'type_code' => 24,
                    'acc_range' => '20112999',
                    'is_group' => true,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );

            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '20112002'],
                [
                    'ledger_code' => '201',
                    'acc_no' => '20112002',
                    'acc_name' => 'Local Purchase Account',
                    'acc_type' => 'L',
                    'type_code' => 24,
                    'acc_range' => '20112002',
                    'is_group' => false,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );

            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '20212100'],
                [
                    'ledger_code' => '202',
                    'acc_name' => 'ACCOUNTS PAYABLE',
                    'acc_type' => 'L',
                    'type_code' => 24,
                    'acc_range' => '20212999',
                    'is_group' => true,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );


            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '20212102'],
                [
                    'ledger_code' => '202',
                    'acc_no' => '20212102',
                    'acc_name' => 'Sales Tax Account',
                    'acc_type' => 'L',
                    'type_code' => 24,
                    'acc_range' => '20212102',
                    'is_group' => false,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );

            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '20312100'],
                [
                    'ledger_code' => '203',
                    'acc_name' => 'OTHER LIABILITIES',
                    'acc_type' => 'L',
                    'type_code' => 24,
                    'acc_range' => '20312999',
                    'is_group' => true,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );


            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '20312102'],
                [
                    'ledger_code' => '203',
                    'acc_name' => 'Sales In Advance',
                    'acc_type' => 'L',
                    'type_code' => 24,
                    'acc_range' => '20312102',
                    'is_group' => false,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );

            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '20312104'],
                [
                    'ledger_code' => '203',
                    'acc_name' => 'Stock Received But Not Billed',
                    'acc_type' => 'L',
                    'type_code' => 24,
                    'acc_range' => '20312104',
                    'is_group' => false,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );

            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '20312106'],
                [
                    'ledger_code' => '203',
                    'acc_name' => 'Asset Received But Not Billed',
                    'acc_type' => 'L',
                    'type_code' => 24,
                    'acc_range' => '20312106',
                    'is_group' => false,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );


            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '30112100'],
                [
                    'ledger_code' => '301',
                    'acc_name' => 'SALES ACCOUNT',
                    'acc_type' => 'I',
                    'type_code' => 31,
                    'acc_range' => '30112999',
                    'is_group' => true,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );


            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '30112102'],
                [
                    'ledger_code' => '301',
                    'acc_name' => 'Sales of Goods',
                    'acc_type' => 'I',
                    'type_code' => 31,
                    'acc_range' => '30112102',
                    'is_group' => false,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );


            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '30212100'],
                [
                    'ledger_code' => '302',
                    'acc_name' => 'OTHER INCOME',
                    'acc_type' => 'I',
                    'type_code' => 31,
                    'acc_range' => '30212100',
                    'is_group' => true,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );


            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '30212102'],
                [
                    'ledger_code' => '302',
                    'acc_name' => 'Discount on Purchase',
                    'acc_type' => 'I',
                    'type_code' => 31,
                    'acc_range' => '30212102',
                    'is_group' => false,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );

            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '40112100'],
                [
                    'ledger_code' => '401',
                    'acc_name' => 'INVENTORY ADJUSTMENT',
                    'acc_type' => 'E',
                    'type_code' => 41,
                    'acc_range' => '40112100',
                    'is_group' => true,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );


            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '40112102'],
                [
                    'ledger_code' => '401',
                    'acc_name' => 'Consumable Store Expense',
                    'acc_type' => 'E',
                    'type_code' => 41,
                    'acc_range' => '40112102',
                    'is_group' => false,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );


            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '40112104'],
                [
                    'ledger_code' => '401',
                    'acc_name' => 'Cost of Goods Sold',
                    'acc_type' => 'E',
                    'type_code' => 41,
                    'acc_range' => '40112104',
                    'is_group' => false,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );


            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '40112110'],
                [
                    'ledger_code' => '401',
                    'acc_name' => 'Discount on Sales',
                    'acc_type' => 'E',
                    'type_code' => 41,
                    'acc_range' => '40112110',
                    'is_group' => false,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );

            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '40112112'],
                [
                    'ledger_code' => '401',
                    'acc_name' => 'Additional Costs',
                    'acc_type' => 'E',
                    'type_code' => 41,
                    'acc_range' => '40112112',
                    'is_group' => false,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );


            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '40212100'],
                [
                    'ledger_code' => '402',
                    'acc_name' => 'TAX PAYMENT',
                    'acc_type' => 'E',
                    'type_code' => 41,
                    'acc_range' => '40212100',
                    'is_group' => true,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );


            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '40212102'],
                [
                    'ledger_code' => '402',
                    'acc_name' => 'Tax Paid for Purchase',
                    'acc_type' => 'E',
                    'type_code' => 41,
                    'acc_range' => '40212102',
                    'is_group' => false,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );


            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '40312100'],
                [
                    'ledger_code' => '403',
                    'acc_name' => 'DEPRECIATION',
                    'acc_type' => 'E',
                    'type_code' => 41,
                    'acc_range' => '40312100',
                    'is_group' => true,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );

            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '40312102'],
                [
                    'ledger_code' => '403',
                    'acc_name' => 'Depreciation Account',
                    'acc_type' => 'E',
                    'type_code' => 41,
                    'acc_range' => '40312102',
                    'is_group' => false,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );


            GeneralLedger::query()->updateOrCreate(
                ['company_id' => $this->company_id, 'acc_no' => '50112100'],
                [
                    'ledger_code' => '501',
                    'acc_name' => 'RETAINED EARNINGS A/C',
                    'acc_type' => 'C',
                    'type_code' => 51,
                    'acc_range' => '50112999',
                    'is_group' => true,
                    'currency' => $currency,
                    'user_id' => $this->user_id
                ]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error', 'Not Saved ' . $error);
        }

        DB::commit();

        return redirect()->action('Company\CompanyPropertiesCO@index')->with('success', 'Company data updated');
    }
}
