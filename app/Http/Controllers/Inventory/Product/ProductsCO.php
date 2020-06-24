<?php

namespace App\Http\Controllers\Inventory\Product;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
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
use Carbon\Carbon;
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
            ['company_id'=>$this->company_id,'menu_id'=>51055,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        $categories = Category::query()->where('company_id',$this->company_id)->pluck('name','id');

        $subcategories = SubCategory::query()->select('name', 'id')
            ->where('category_id',1)
            ->where('company_id',$this->company_id)
            ->pluck('name','id');

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
        $products = ProductMO::query()->where('company_id',$this->company_id)->with('category','subcategory');

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
                        data-store="'. $products->godown_id . '"
                        data-rack="'. $products->rack_id . '"
                        data-reorder="'. $products->reorder_point . '"
                        data-expiry="'. $products->expiry_date . '"
                        data-description_short="'. $products->description_short . '"
                        data-description_long="'. $products->description_long . '"
                        data-opening_qty="'. $products->opening_qty . '"
                        data-opening_value="'. $products->opening_value . '"
                        data-wholesale_price="'. $products->wholesale_price . '"
                        data-retail_price="'. $products->retail_price . '"


                        type="button" class="btn btn-sm btn-product-edit btn-primary pull-center"><i class="fa fa-edit" >Edit</i></button>
                    <button data-remote="unit/delete/'.$products->id.'"  type="button" class="btn btn-unit-delete btn-sm btn-danger"><i class="fa fa-trash">Delete</i></button>
                    </div>
                    ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getSub(Request $request)
    {
        $input = $request['option'];

        $sub = SubCategory::query()->where('company_id',$this->company_id)
            ->where('category_id',$input)
            ->orderBy('name')
            ->pluck('name','id');

        return response()->json($sub);
    }


    public function store(Request $request)
    {

        $request['company_id'] = $this->company_id;
        $request['user_id'] = $this->user_id;
        $request['product_code'] = str_pad($request['category_id'],4,0).int_random();
        $request['expiry_date'] = Carbon::createFromFormat('d-m-Y',$request['expiry_date'])->format('Y-m-d');


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


    public function update(Request $request, $id)
    {
        $product = ProductMO::query()->find($id);
        $product->name = $request['name'];
        $product->reorder_point = $request['reorder_point'];
        $product->brand_id = $request['brand_id'];
        $product->unit_name = $request['unit_name'];
        $product->size_id = $request['size_id'];
        $product->color_id = $request['color_id'];
        $product->model_id = $request['model_id'];
        $product->tax_id = $request['tax_id'];
        $product->rack_id = $request['rack_id'];
        $product->wholesale_price = $request['wholesale_price'];
        $product->retail_price = $request['retail_price'];
        $product->expiry_date = Carbon::createFromFormat('d-m-Y',$request['expiry_date'])->format('Y-m-d') ;

        if($request->has('opening_qty'))
        {
            $product->opening_qty = $request['opening_qty'];
            $product->opening_value = $request['opening_value'];
            $product->unit_price = $request['opening_value']/$request['opening_qty'];
        }

        DB::begintransaction();

        try {

            $product->save();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Product Updated Successfully'],200);
    }


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
