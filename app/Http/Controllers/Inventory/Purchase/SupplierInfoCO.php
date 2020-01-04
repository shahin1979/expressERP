<?php

namespace App\Http\Controllers\Inventory\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupplierInfoCO extends Controller
{
    public function index()
    {
        return view('inventory.purchase.supplier-info-index');
    }
}
