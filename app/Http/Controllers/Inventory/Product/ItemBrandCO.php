<?php

namespace App\Http\Controllers\Inventory\Product;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Product\ItemBrand;
use App\Models\Inventory\Product\ItemUnit;
use App\Models\Inventory\Product\ProductMO;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ItemBrandCO extends Controller
{
    public function index()
    {

        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>51020,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        return view('inventory.product.item-brand-index');
    }

    public function getBrandDBData()
    {
        $brands = ItemBrand::query()->where('company_id',$this->company_id);

        return DataTables::of($brands)
            ->addColumn('action', function ($brands) {

                return '<div class="btn-unit btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="view/'.$brands->id.'"  type="button" class="btn btn-view btn-sm btn-success"><i class="fa fa-book-open">View</i></button>
                    <button data-remote="edit/' . $brands->id . '" data-rowid="'. $brands->id . '"
                        data-name="'. $brands->name . '"
                        data-manufacturer="'. $brands->manufacturer . '"
                        data-origin="'. $brands->origin . '"
                        type="button" class="btn btn-sm btn-brand-edit btn-primary pull-center"><i class="fa fa-edit" >Edit</i></button>
                    <button data-remote="brand/delete/'.$brands->id.'"  type="button" class="btn btn-brand-delete btn-sm btn-danger"><i class="fa fa-trash">Delete</i></button>
                    </div>

                    ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        DB::begintransaction();

        try {

            $ids = ItemBrand::query()->create([
                'company_id' => $this->company_id,
                'name' => $request['name'],
                'status' => true,
                'manufacturer' => $request['manufacturer'],
                'origin' => $request['origin'],
                'user_id' => $this->user_id
            ]);

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error',$error);
        }

        DB::commit();

        return redirect()->action('Inventory\Product\ItemBrandCO@index')->with('success','New Brand Added ');
    }

    public function update(Request $request, $id)
    {
        $brand = ItemBrand::query()->find($id);
        $brand->name = $request['name'];
        $brand->manufacturer = $request['manufacturer'];
        $brand->origin = $request['origin'];

        DB::begintransaction();

        try {

            $brand->save();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Brand Updated Successfully'],200);
    }

    public function destroy($id)
    {
        $brand = ItemBrand::query()->find($id);

        $items = ProductMO::query()->where('company_id',$this->company_id)
            ->where('brand_id',$id)->count();

        if($items > 0)
        {
            return response()->json(['error' => 'Product found in this Brand. Not Possible to delete'], 404);
        }

        DB::begintransaction();

        try {

            $brand->delete();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Brand Deleted Successfully'],200);
    }
}
