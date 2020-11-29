<?php

namespace App\Http\Controllers\Inventory\Production;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EditProductionCO extends Controller
{
    public function index()
    {
        return view('inventory.production.edit-production-index');
    }
}
