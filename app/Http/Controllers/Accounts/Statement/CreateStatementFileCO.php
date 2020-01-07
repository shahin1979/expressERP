<?php

namespace App\Http\Controllers\Accounts\Statement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateStatementFileCO extends Controller
{
    public function index()
    {
        return view('accounts.statement.statement-file-index');
    }
}
