<?php

namespace App\Http\Controllers\Inventory\Import;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Setup\Bank;
use App\Models\Common\Country;
use App\Models\Company\CompanyProperty;
use App\Models\Company\Relationship;
use App\Models\Inventory\Product\ProductMO;
use Illuminate\Http\Request;

class ImportLCRegisterCO extends Controller
{
    public function index()
    {
        $suppliers = Relationship::query()->pluck('name','id');
        $currencies = Country::query()->whereNotIn('currency_short',['USD'])
            ->orderBy('currency_short')
            ->pluck('currency_short','currency_short')
            ->prepend('USD : Us Dollar','USD');

        $banks = Bank::query()->where('bank_type','C')->pluck('bank_name','id');

        return view('inventory.import.import-register-index',compact('suppliers','currencies','banks'));
    }

    public function autocomplete(Request $request)
    {
        $term = $request['term'];
        $company = CompanyProperty::query()->where('company_id',$this->company_id)->first();

        $items = ProductMO::query()->select('id as item_id', 'name','unit_price','unit_name','tax_id')
            ->where('company_id',$this->company_id)
            ->where('category_id',$company->rm_cg_id)
            ->where('name', 'LIKE', '%'.$term.'%')->get();

        return response()->json($items);
    }
}
