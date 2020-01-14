<?php

namespace App\Http\Controllers\Inventory\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Company\Relationship;
use App\Models\Inventory\Product\ItemTax;
use Illuminate\Http\Request;

class PurchaseProductCO extends Controller
{
    public function index()
    {
        $suppliers = Relationship::query()->where('company_id',$this->company_id)
            ->where('relation_type','LS')
            ->orderBy('name')
            ->pluck('name','id');

        $taxes = ItemTax::query()->where('company_id',$this->company_id)->pluck('id','name');

        return view('inventory.purchase.purchase-product-index',compact('suppliers','taxes'));
    }
}
