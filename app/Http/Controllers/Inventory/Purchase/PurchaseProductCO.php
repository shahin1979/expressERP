<?php

namespace App\Http\Controllers\Inventory\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PurchaseProductCO extends Controller
{
    public function index()
    {
        return view('inventory.purchase.purchase-product-index');
    }
}
