<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UpdateUserCO extends Controller
{
    public function index(Request $request)
    {
        return view('security.update-user-index');
    }
}
