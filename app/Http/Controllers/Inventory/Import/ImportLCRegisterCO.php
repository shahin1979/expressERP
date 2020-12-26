<?php

namespace App\Http\Controllers\Inventory\Import;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Setup\Bank;
use App\Models\Common\Country;
use App\Models\Company\Relationship;
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
        $banks = Bank::query()->pluck('bank_name','id');

        return view('inventory.import.import-register-index',compact('suppliers','currencies','banks'));
    }
}
