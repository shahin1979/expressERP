<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Common\AppModule;
use App\Models\Company\CompanyModule;
use App\Models\Company\CostType;
use App\Models\Company\FiscalPeriod;
use App\Models\Company\TransCode;
use App\Models\Common\Country;
use App\Models\Company\Company;
use App\Models\Company\CompanyProperty;
use App\Traits\CompanyTrait;
use App\Traits\PreviousDataMigrationTrait;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyPropertiesCO extends Controller
{
    use CompanyTrait, TransactionsTrait, PreviousDataMigrationTrait;

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
                    'additional_charges' => $request['additional_charges'],
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

                $year = '2020'; // Carbon::now()->format('Y');
                $this->trCode($this->company_id, $year);
                $this->create_fiscal_period($this->company_id,'01-07-2020');
                //ADD INITIAL ACCOUNTS OF THE GENERAL LEDGER
                $accounts = $this->set_default_ledger_accounts($this->company_id);
                // ADD COST TYPES
                $costs = $this->set_company_additional_costs($this->company_id);

                $items = $this->set_company_default_item_properties($this->company_id);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error', 'Not Saved ' . $error);
        }

        DB::commit();

        return redirect()->action('Company\CompanyPropertiesCO@index')->with('success', 'Company data updated');
    }
}
