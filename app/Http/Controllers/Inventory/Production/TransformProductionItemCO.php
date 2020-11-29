<?php

namespace App\Http\Controllers\Inventory\Production;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransformProductionItemCO extends Controller
{
    public function index()
    {
        return view('inventory.production.transform-production-item-index');
    }
}
