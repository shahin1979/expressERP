<?php

namespace App\Http\Controllers\Inventory\Product;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Product\Category;
use App\Models\Inventory\Product\Godown;
use App\Models\Inventory\Product\ItemBrand;
use App\Models\Inventory\Product\ItemColor;
use App\Models\Inventory\Product\ItemModel;
use App\Models\Inventory\Product\ItemSize;
use App\Models\Inventory\Product\ItemTax;
use App\Models\Inventory\Product\ItemUnit;
use App\Models\Inventory\Product\ProductMO;
use App\Models\Inventory\Product\Rack;
use App\Models\Inventory\Product\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsCO extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>52055,'user_id'=>$this->user_id
            ]);

        $categories = Category::query()->where('company_id',$this->company_id)->pluck('name','id');

        $subcategories = SubCategory::query()->select('name', 'id')
            ->where('company_id',$this->company_id)->pluck('name','id');

        $brands = ItemBrand::query()->where('company_id',$this->company_id)->pluck('name','id');

        $units = ItemUnit::query()->where('company_id',$this->company_id)->pluck('formal_name','name');

        $sizes = ItemSize::query()->select('size', 'id')
            ->where('company_id',$this->company_id)->pluck('size','id');

        $models = ItemModel::query()->where('company_id',$this->company_id)->pluck('name','id');

        $taxes = ItemTax::query()->where('company_id',$this->company_id)->pluck('name','id');

        $colors = ItemColor::query()->where('company_id',$this->company_id)->pluck('name','id');

        $stores = Godown::query()->where('company_id',$this->company_id)->pluck('name','id');

        $racks = Rack::query()->where('company_id',$this->company_id)->pluck('name','id');

        return view('inventory.product.product-index',compact('categories','subcategories',
        'brands','units','sizes','models','taxes','colors','stores','racks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getSKU(Request $request)
    {
        $prefix = substr($request['category'],0,3);
        $sku = $this->generateProductSKU($prefix);

        return response()->json($sku);
    }

    /**
     * Generate a sample product SKU
     *
     * @return string
     */
    public function generateProductSKU($skuString)
    {
        return $skuString . int_random();
    }
}
