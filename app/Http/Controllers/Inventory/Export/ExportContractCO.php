<?php

namespace App\Http\Controllers\Inventory\Export;

use App\Http\Controllers\Controller;
use App\Models\Common\Country;
use App\Models\Company\Relationship;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExportContractCO extends Controller
{
    use CommonTrait;

    public function index()
    {
        $this->menu_log($this->company_id,70005);

        $customers = Relationship::query()->where('company_id',$this->company_id)
            ->whereIn('relation_type',['CS','SP'])
            ->whereNotIn('ledger_acc_no',['10112102'])
            ->orderBy('name')
            ->pluck('name','id');

        $currencies = Country::query()->whereNotIn('currency_short',['USD'])
            ->orderBy('currency_short')
            ->pluck('currency_short','currency_short')
            ->prepend('USD : Us Dollar','USD');

        return view('inventory.export.index.export-contract-index',compact('currencies','customers'));
    }
}
