<?php

namespace App\Http\Controllers\Inventory\Product\Report;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Product\ProductMO;
use Illuminate\Http\Request;

class ProductLedgerCO extends Controller
{
    public function index()
    {
        $products = ProductMO::query()->where('company_id',$this->company_id)->OrderBy('name')->pluck('name','id');

        return view('inventory.product.report.product-ledger-index',compact('products'));
    }

    public function autocomplete(Request $request)
    {
        $term = $request['term'];

        $items = ProductMO::query()->select('id as item_id', 'name','tax_id','unit_price','unit_name')
            ->where('company_id',$this->company_id)
            ->where('name', 'LIKE', '%'.$term.'%')->get();

        return response()->json($items);
    }
}
