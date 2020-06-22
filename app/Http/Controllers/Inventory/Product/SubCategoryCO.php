<?php

namespace App\Http\Controllers\Inventory\Product;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Product\Category;
use App\Models\Inventory\Product\SubCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class SubCategoryCO extends Controller
{
    public function index()
    {
        $categories = Category::query()->where('company_id',$this->company_id)
            ->where('status',true)->pluck('name','id');

        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>51010,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        return view('inventory.product.sub-category-index',compact('categories',$categories));
    }

    public function getSubCategoryData()
    {
        $sbcs = SubCategory::query()->where('company_id',$this->company_id)->with('group')->get();

        return DataTables::of($sbcs)

            ->addColumn('acc_in',function ($sbcs) {
                return isset($sbcs->acc_in_stock) ? $sbcs->acc_in_stock.'<br/>'.$sbcs->acc_in->acc_name:null;
            })

            ->addColumn('acc_out',function ($sbcs) {
                return isset($sbcs->acc_out_stock) ? $sbcs->acc_out_stock.'<br/>'.$sbcs->acc_out->acc_name:null;
            })


            ->addColumn('action', function ($sbcs) {

                return '<div class="btn-category btn-group-sm" role="group" aria-label="Action Button">
                    <button data-rowid="'. $sbcs->id . '"
                        data-category="'. $sbcs->category_id . '"
                        data-name="'. $sbcs->name . '"
                        data-receive="'. $sbcs->acc_in_stock . '"
                        data-delivery="'. $sbcs->acc_out_stock . '"
                        type="button" class="btn btn-sm btn-sub-category-edit btn-primary"><i>Edit</i></button>
                    <button data-remote="subcategory/delete/'.$sbcs->id.'"  type="button" class="btn btn-sub-category-delete btn-sm btn-danger"><i>Delete</i></button>
                    </div>

                    ';
            })
            ->rawColumns(['action','acc_in','acc_out'])
            ->make(true);
    }

    public function store(Request $request)
    {

        DB::begintransaction();

        try {

            $ids = SubCategory::query()->create([
                'company_id' => $this->company_id,
                'category_id'=>$request['category_id'],
                'name' => Str::ucfirst($request['name']),
                'status' => true,
                'acc_in_stock' =>$request->filled('acc_in_stock') ? $request['acc_in_stock'] : null,
                'acc_out_stock' =>$request->filled('acc_out_stock') ? $request['acc_out_stock'] : null,
                'user_id' => $this->user_id
            ]);

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error',$error);
        }

        DB::commit();

        return redirect()->action('Inventory\Product\SubCategoryCO@index')->with('success','New Sub Category Added');
    }

    public function update(Request $request, $id)
    {
        $category = SubCategory::query()->find($id);

        $category->name = Str::ucfirst($request['name']);
        $category->acc_in_stock = $request['acc_in_stock'];
        $category->acc_out_stock = $request['acc_out_stock'];

//        dd($request->all());

        DB::begintransaction();

        try {

            $category->save();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Sub Category Updated Successfully'],200);

    }


    public function destroy($id)
    {
//
        DB::begintransaction();

        try {

            SubCategory::query()->find($id)->delete();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Category Deleted Successfully'],200);

    }
}
