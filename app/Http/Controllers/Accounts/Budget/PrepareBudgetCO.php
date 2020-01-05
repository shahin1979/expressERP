<?php

namespace App\Http\Controllers\Accounts\Budget;

use App\Http\Controllers\Controller;
use App\Models\Company\FiscalPeriod;
use Illuminate\Http\Request;

class PrepareBudgetCO extends Controller
{
    public function index()
    {
        $months = FiscalPeriod::query()->where('company_id',$this->company_id)->get();
        return view('accounts.budget.prepare-budget-index')->with('months',$months);
    }
}
