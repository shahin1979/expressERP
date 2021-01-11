<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DatabaseBackupCO extends Controller
{
    public function index()
    {
        return view('company.database-backup-index');
    }
}
