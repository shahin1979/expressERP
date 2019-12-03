<?php

namespace App\Http\Controllers\Inventory\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Product\Caregory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class CategoryCO extends Controller
{
    public function index()
    {
        return view('inventory.product.product-category-index');
    }

    public function getCategoryData()
    {
        $categories = Caregory::query()->where('company_id',$this->company_id);

        return DataTables::of($categories)
            ->addColumn('action', function ($categories) {

                return '<div class="btn-category btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="view/'.$categories->id.'"  type="button" class="btn btn-view btn-sm btn-success"><i class="fa fa-book-open">View</i></button>
                    <button data-remote="edit/' . $categories->id . '" data-rowid="'. $categories->id . '"
                        data-name="'. $categories->name . '"
                        data-sub="'. $categories->has_sub . '"
                        data-acc-no="'. $categories->acc_no . '"
                        type="button" class="btn btn-sm btn-category-edit btn-primary pull-center"><i class="fa fa-edit" >Edit</i></button>
                    <button data-remote="delete/'.$categories->id.'"  type="button" class="btn btn-category-delete btn-sm btn-danger"><i class="fa fa-trash">Delete</i></button>
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

            $ids = Caregory::query()->create([
                'COMPANY_ID' => $this->company_id,
                'NAME' => Str::upper($request['name']),
                'STATUS' => true,
                'HAS_SUB' => $request->has('sub_category') ? true : false,
                'ACC_NO' =>$request['acc_no'],
                'USER_ID' => $this->user_id
            ]);

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
        $updateCategory = Caregory::query()->find($id);
        $updateCategory->name = $request['NAME'];
        $updateCategory->has_sub = $request['HAS_SUB'] == 1 ? true : false;

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
}
