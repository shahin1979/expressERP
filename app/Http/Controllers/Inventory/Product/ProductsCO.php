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
use Yajra\DataTables\DataTables;

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

    public function getProductsDBData()
    {
        $products = ProductMO::query()->where('company_id',$this->company_id)->with('category','subcategory')->get();

        return DataTables::of($products)
            ->addColumn('action', function ($products) {

                return '<div class="btn-unit btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="view/'.$products->id.'"  type="button" class="btn btn-view btn-sm btn-success"><i class="fa fa-book-open">View</i></button>
                    <button data-remote="edit/' . $products->id . '" data-rowid="'. $products->id . '"
                        data-name="'. $products->name . '"
                        data-brand="'. $products->brand_id . '"
                        data-category="'. $products->category_id . '"

                        data-subcategory="'. $products->subcategory_id . '"
                        data-unit_name="'. $products->unit_name . '"
                        data-second="'. $products->second_unit . '"
                        data-category="'. $products->category_id . '"
                        data-third="'. $products->third_unit . '"
                        data-size="'. $products->size_id . '"
                        data-color="'. $products->color_id . '"
                        data-model="'. $products->model_id . '"
                        data-tax="'. $products->tax_id . '"
                        data-retail-price="'. $products->retail_price . '"
                        data-buy-price="'. $products->buy_price . '"
                        data-category="'. $products->category_id . '"
                        data-category="'. $products->category_id . '"





                        type="button" class="btn btn-sm btn-product-edit btn-primary pull-center"><i class="fa fa-edit" >Edit</i></button>
                    <button data-remote="unit/delete/'.$products->id.'"  type="button" class="btn btn-unit-delete btn-sm btn-danger"><i class="fa fa-trash">Delete</i></button>
                    </div>

                    ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {

        $request['company_id'] = $this->company_id;
        $request['user_id'] = $this->user_id;
        $request['product_code'] = str_pad($request['category_id'],4,0).int_random();


        DB::beginTransaction();

        try{

            $id = ProductMO::query()->create($request->except('product-image'));

            if($request->hasfile('product-image'))
            {
                $file = $request->file('product-image');
                $name = $id->id.'.'.$file->getClientOriginalExtension();
                $file->move(public_path().'/photo/', $name);

                ProductMO::query()->where('id',$id->id)->update(['image'=>'photo/'.$name]);
            }

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error','Not Saved '.$error);
        }

        DB::commit();

        return redirect()->action('Inventory\Product\ProductsCO@index')->with('success','Product Successfully Saved');
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
