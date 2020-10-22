<?php


namespace App\Traits;


use App\Models\Accounts\Ledger\CostCenter;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Company\CompanyProperty;
use App\Models\Company\CostType;
use App\Models\Company\Relationship;
use App\Models\Human\Admin\Location;
use App\Models\Inventory\Product\Category;
use App\Models\Inventory\Product\Godown;
use App\Models\Inventory\Product\ItemBrand;
use App\Models\Inventory\Product\ItemColor;
use App\Models\Inventory\Product\ItemModel;
use App\Models\Inventory\Product\ItemSize;
use App\Models\Inventory\Product\ItemTax;
use App\Models\Inventory\Product\Rack;
use App\Models\Inventory\Product\SubCategory;
use App\Models\Projects\Project;
use Illuminate\Support\Facades\Auth;

trait CompanyTrait
{
    public function set_default_ledger_accounts($company_id)
    {
        $currency='BDT';

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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
            ]
        );

        GeneralLedger::query()->updateOrCreate(
            ['company_id' => $this->company_id, 'acc_no' => '40112112'],
            [
                'ledger_code' => '401',
                'acc_name' => 'Additional Charges & Costs',
                'acc_type' => 'E',
                'type_code' => 41,
                'acc_range' => '40112112',
                'is_group' => false,
                'currency' => $currency,
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
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
                'user_id' => Auth::id()
            ]
        );

        return true;

    }

    public function set_company_additional_costs($company_id)
    {
        CostType::query()->updateOrCreate(
            ['company_id' => $company_id, 'name' => 'Transport Charge'],
            [
                'gl_head' => $this->get_default_additional_charges_account($company_id),
                'user_id' => Auth::id()
            ]);

        CostType::query()->updateOrCreate(
            ['company_id' => $company_id, 'name' => 'Postal Charge'],
            [
                'gl_head' => $this->get_default_additional_charges_account($company_id),
                'user_id' => Auth::id()
            ]);

        CostType::query()->updateOrCreate(
            ['company_id' => $company_id, 'name' => 'Freight Charge'],
            [
                'gl_head' => $this->get_default_additional_charges_account($company_id),
                'user_id' => Auth::id()
            ]);

        CostType::query()->updateOrCreate(
            ['company_id' => $company_id, 'name' => 'Warehouse Rent'],
            [
                'gl_head' => $this->get_default_additional_charges_account($company_id),
                'user_id' => Auth::id()
            ]);

        return true;
    }

    public function set_company_default_item_properties($company_id)
    {
        $id = Category::query()->updateOrCreate(['company_id' => $company_id, 'name' => 'Default Category'],
            [
            'alias'=>'Default Category',
            'has_sub'=>1,
            'status' => 1,
            'user_id' => Auth::id()
        ]);

        SubCategory::query()->updateOrCreate(['company_id' => $company_id, 'name' => 'Default Sub Category'],
            [
            'category_id'=>$id->id,
            'alias'=>'Default Sub Category',
            'status' => 1,
            'user_id' => Auth::id()
        ]);

        ItemBrand::query()->updateOrCreate(['company_id' => $company_id, 'name' => 'Default Brand'],
            [
            'manufacturer'=>'Default Manufacturer',
            'status' => 1,
            'user_id' => Auth::id()
        ]);

        ItemModel::query()->updateOrCreate(['company_id' => $company_id, 'name' => 'Default Model'],
           [
            'status' => 1,
            'user_id' => Auth::id()
        ]);

        ItemColor::query()->updateOrCreate(['company_id' => $company_id, 'name' => 'Default Color'],
            [
            'status' => 1,
            'user_id' => Auth::id()
        ]);

        ItemSize::query()->updateOrCreate(['company_id' => $company_id, 'size' => 'Default Size'],
            [
            'status' => 1,
            'user_id' => Auth::id()
        ]);

        $store = Godown::query()->updateOrCreate(['company_id' => $company_id, 'name' => 'Default Store'],
           [
            'address'=>'Main Store',
            'status' => 1,
            'user_id' => Auth::id()
        ]);


        Rack::query()->updateOrCreate(['company_id' => $company_id, 'name' => 'Default Rack'],
            [
            'godown_id'=>$store->id,
            'status' => 1,
            'user_id' => Auth::id()
        ]);

        ItemTax::query()->updateOrCreate(['company_id' => $company_id, 'name' => 'No Tax'],
            [
            'applicable_on'=>'B',
            'rate'=>0,
            'calculating_mode'=>'F',
            'description'=>'Default Tax',
            'status' => 1,
            'user_id' => Auth::id()
        ]);

        ItemTax::query()->updateOrCreate(['company_id' => $company_id, 'name' => 'VAT'],
            [
            'applicable_on'=>'B',
            'rate'=>10,
            'calculating_mode'=>'F',
            'description'=>'Value Added Tax',
            'status' => 1,
            'user_id' => Auth::id()
        ]);


        Relationship::query()->updateOrCreate(['company_id' => $company_id, 'name' => 'Cash Account'],
            [
            'relation_type'=>'SP',
            'ledger_acc_no'=> $this->get_default_cash_account($company_id),
            'user_id' => Auth::id()
        ]);

        Location::query()->updateOrCreate(['company_id' => $company_id, 'name' => 'Office Building'],
            [
            'location_type'=>'O',
            'user_id' => Auth::id()
        ]);

        CostCenter::query()->updateOrCreate(['company_id' => $company_id, 'fiscal_year'=>'2019-2020', 'name' => 'Head Office'],
            [
            'user_id' => Auth::id()
        ]);


        Project::query()->updateOrCreate(['company_id' => $company_id, 'project_code' => 'Project-001'],
            [
            'project_type'=>'O',
            'project_desc'=>'Default Project',
            'project_name' => 'Default Project',
            'project_ref'=>'Govt',
            'start_date'=>\Carbon\Carbon::now(),
            'user_id' => Auth::id()
        ]);

        return true;
    }

    public function get_default_sales_account($company_id)
    {
        $company = CompanyProperty::query()->where('company_id',$company_id)->first();

        return $company->default_sales;
    }


    public function get_default_advance_sales_account($company_id)
    {
        $company = CompanyProperty::query()->where('company_id',$company_id)->first();

        return $company->advance_sales;
    }

    public function get_default_purchase_account($company_id)
    {
        $company = CompanyProperty::query()->where('company_id',$company_id)->first();

        return $company->default_purchase;
    }

    public function get_default_cash_account($company_id)
    {
        $company = CompanyProperty::query()->where('company_id',$company_id)->first();

        return $company->default_cash;
    }

    public function get_default_sales_tax_account($company_id)
    {
        $company = CompanyProperty::query()->where('company_id',$company_id)->first();

        return $company->default_sales_tax;
    }

    public function get_default_purchase_tax_account($company_id)
    {
        $company = CompanyProperty::query()->where('company_id',$company_id)->first();

        return $company->default_purchase_tax;
    }

    public function get_default_discount_purchase_account($company_id)
    {
        $company = CompanyProperty::query()->where('company_id',$company_id)->first();

        return $company->discount_purchase;
    }

    public function get_default_discount_sales_account($company_id)
    {
        $company = CompanyProperty::query()->where('company_id',$company_id)->first();

        return $company->discount_sales;
    }

    public function get_default_consumable_on_hand_account($company_id)
    {
        $company = CompanyProperty::query()->where('company_id',$company_id)->first();

        return $company->consumable_on_hand;
    }

    public function get_default_consumable_expense_account($company_id)
    {
        $company = CompanyProperty::query()->where('company_id',$company_id)->first();

        return $company->consumable_expense;
    }

    public function get_default_rm_in_hand_account($company_id)
    {
        $company = CompanyProperty::query()->where('company_id',$company_id)->first();

        return $company->rm_in_hand;
    }

    public function get_default_rm_category_id($company_id)
    {
        $company = CompanyProperty::query()->where('company_id',$company_id)->first();

        return $company->rm_cg_id;
    }

    public function get_default_work_in_progress_account($company_id)
    {
        $company = CompanyProperty::query()->where('company_id',$company_id)->first();

        return $company->work_in_progress;
    }

    public function get_default_finished_goods_account($company_id)
    {
        $company = CompanyProperty::query()->where('company_id',$company_id)->first();

        return $company->finished_goods;
    }

    public function get_default_cost_of_goods_sold_account($company_id)
    {
        $company = CompanyProperty::query()->where('company_id',$company_id)->first();

        return $company->cost_of_goods_sold;
    }

    public function get_default_finished_goods_category_id($company_id)
    {
        $company = CompanyProperty::query()->where('company_id',$company_id)->first();

        return $company->fg_cg_id;
    }

    public function get_default_depreciation_account($company_id)
    {
        $company = CompanyProperty::query()->where('company_id',$company_id)->first();

        return $company->depreciation_account;
    }

    public function get_default_depreciation_frequency($company_id)
    {
        $company = CompanyProperty::query()->where('company_id',$company_id)->first();

        return $company->depreciation_frequency;
    }

    public function get_default_additional_charges_account($company_id)
    {
        $company = CompanyProperty::query()->where('company_id',$company_id)->first();

        return $company->additional_charges;
    }

}
