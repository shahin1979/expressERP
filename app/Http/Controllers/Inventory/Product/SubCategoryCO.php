<?php

namespace App\Http\Controllers\Inventory\Product;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Product\Category;
use App\Models\Inventory\Product\SubCategory;
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
            ['company_id'=>$this->company_id,'menu_id'=>52010,'user_id'=>$this->user_id
        ]);

        return view('inventory.product.sub-category-index',compact('categories',$categories));
    }

    public function getSubCategoryData()
    {
        $sbcs = SubCategory::query()->where('company_id',$this->company_id)->with('group')->get();

        return DataTables::of($sbcs)
            ->addColumn('action', function ($sbcs) {

                return '<div class="btn-category btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="view/'.$sbcs->id.'"  type="button" class="btn btn-view btn-sm btn-success"><i class="fa fa-book-open">View</i></button>
                    <button data-remote="edit/' . $sbcs->id . '" data-rowid="'. $sbcs->id . '"
                        data-category="'. $sbcs->category_id . '"
                        data-name="'. $sbcs->name . '"
                        data-ledger="'. $sbcs->acc_no . '"
                        type="button" class="btn btn-sm btn-category-edit btn-primary pull-center"><i class="fa fa-edit" >Edit</i></button>
                    <button data-remote="subcategory/delete/'.$sbcs->id.'"  type="button" class="btn btn-category-delete btn-sm btn-danger"><i class="fa fa-trash">Delete</i></button>
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

            $ids = SubCategory::query()->create([
                'company_id' => $this->company_id,
                'category_id'=>$request['category_id'],
                'name' => Str::upper($request['name']),
                'status' => true,
                'acc_no' =>$request->filled('acc_no') ? $request['acc_no'] : null,
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
//        $updateCategory = Category::query()->find($id);
//        $updateCategory->name = $request['name'];
//        $updateCategory->has_sub = $request['HAS_SUB'] == 1 ? true : false;
//        $updateCategory->acc_no = $request['ACC_NO'];

        DB::begintransaction();

        try {

//            $updateCategory->save();

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

//        $ledger_code = Category::query()->where('id',$id)->first();
//        if (Category::query()->where('ledger_code',$ledger_code->ledger_code)->where('is_group',false)->exists())
//        {
//            return response()->json(['error' => 'Child Record Exist. Not Possible To Delete'], 404);
//        }

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
