<?php

namespace App\Http\Controllers\Inventory\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryCO extends Controller
{
    public function index()
    {
        return view('inventory.product.product-category-index');
    }
}
