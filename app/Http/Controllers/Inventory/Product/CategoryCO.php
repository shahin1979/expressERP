<?php

namespace App\Http\Controllers\Inventory\Product;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Product\ProductMO;
use App\Models\Inventory\Product\SubCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Inventory\Product\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class CategoryCO extends Controller
{
    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>51005,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        return view('inventory.product.product-category-index');
    }

    public function getCategoryData()
    {
        $categories = Category::query()->where('company_id',$this->company_id);

        return DataTables::of($categories)
            ->addColumn('action', function ($categories) {

                return '<div class="btn-category btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="view/'.$categories->id.'"  type="button" class="btn btn-view btn-sm btn-success"><i class="fa fa-book-open">View</i></button>
                    <button data-remote="edit/' . $categories->id . '" data-rowid="'. $categories->id . '"
                        data-name="'. $categories->name . '"
                        data-sub="'. $categories->has_sub . '"
                        data-acc-no="'. $categories->acc_no . '"
                        type="button" class="btn btn-sm btn-category-edit btn-primary pull-center"><i class="fa fa-edit" >Edit</i></button>
                    <button data-remote="category/delete/'.$categories->id.'"  type="button" class="btn btn-category-delete btn-sm btn-danger"><i class="fa fa-trash">Delete</i></button>
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

            $ids = Category::query()->create([
                'company_id' => $this->company_id,
                'name' => Str::upper($request['name']),
                'status' => true,
                'has_sub' => $request->has('sub_category'),
//                'acc_no' =>$request['acc_no'],
                'user_id' => $this->user_id
            ]);

            if(!$request->has('sub_category'))
            {
                SubCategory::query()->create([
                    'company_id' => $this->company_id,
                    'category_id'=>$ids->id,
                    'name' => $request['name'],
                    'status' => true,
                    'acc_in_stock' =>$request->filled('acc_in_stock') ? $request['acc_in_stock'] : null,
                    'acc_out_stock' =>$request->filled('acc_out_stock') ? $request['acc_out_stock'] : null,
                    'user_id' => $this->user_id
                ]);
            }

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error',$error);
        }

        DB::commit();

        return redirect()->action('Inventory\Product\CategoryCO@index')->with('success','New Category Added ');
    }

    public function update(Request $request, $id)
    {
        $updateCategory = Category::query()->find($id);
        $updateCategory->name = Str::upper($request['name']);

        DB::begintransaction();

        try {

            $updateCategory->save();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Category Updated Successfully'],200);

    }


    public function destroy($id)
    {
//        $updateCategory = Category::query()->find($id);

        $category = Category::query()->where('id',$id)->first();
        if (ProductMO::query()->where('company_id',$this->company_id)->where('category_id',$id)->exists())
        {
            return response()->json(['error' => 'Child Record Exist. Not Possible To Delete'], 404);
        }

        DB::begintransaction();

        try {

            Category::query()->find($id)->delete();

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
