<?php

namespace App\Http\Controllers\Accounts\Ledger;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use Illuminate\Http\Request;

class OpeningBalanceCO extends Controller
{
    public function index()
    {

        $ledgers = GeneralLedger::query()->where('company_id',$this->company_id)
            ->where('opn_dr','>',0)
            ->orWhere('opn_cr','>',0)->get();


        return view('accounts.ledger.opening-balance-index',compact('ledgers'));
    }

    public function store()
    {
        dd('here');
    }
}
