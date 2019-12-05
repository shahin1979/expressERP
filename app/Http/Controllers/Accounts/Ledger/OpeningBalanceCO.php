<?php

namespace App\Http\Controllers\Accounts\Ledger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OpeningBalanceCO extends Controller
{
    public function index()
    {
        return view('accounts.ledger.opening-balance-index');
    }
}
