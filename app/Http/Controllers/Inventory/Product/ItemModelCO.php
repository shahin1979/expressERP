<?php

namespace App\Http\Controllers\Inventory\Product;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Product\ItemModel;
use App\Models\Inventory\Product\ProductMO;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ItemModelCO extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>51035,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        return view('inventory.product.item-model-index');
    }

    public function getModelDBData()
    {
        $models = ItemModel::query()->where('company_id',$this->company_id);

        return DataTables::of($models)
            ->addColumn('action', function ($models) {

                return '<div class="btn-unit btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="view/'.$models->id.'"  type="button" class="btn btn-view btn-sm btn-success"><i class="fa fa-book-open">View</i></button>
                    <button data-rowid="'. $models->id . '"
                        data-name="'. $models->name . '"
                        data-description="'. $models->description . '"
                        type="button" class="btn btn-sm btn-model-edit btn-primary pull-center"><i class="fa fa-edit" >Edit</i></button>
                    <button data-remote="itemModel/delete/'.$models->id.'"  type="button" class="btn btn-model-delete btn-sm btn-danger"><i class="fa fa-trash">Delete</i></button>
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

            $ids = ItemModel::query()->create([
                'company_id' => $this->company_id,
                'name' => $request['name'],
                'status' => true,
                'description' => $request['description'],
                'user_id' => $this->user_id
            ]);

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error',$error);
        }

        DB::commit();

        return redirect()->action('Inventory\Product\ItemModelCO@index')->with('success','New Model Added ');
    }

    public function update(Request $request, $id)
    {
        $model = ItemModel::query()->find($id);
        $model->name = $request['name'];
        $model->description = $request['description'];

        DB::begintransaction();

        try {

            $model->save();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Model Updated Successfully'],200);
    }

    public function destroy($id)
    {
        $model = ItemModel::query()->find($id);

        $items = ProductMO::query()->where('company_id',$this->company_id)
            ->where('model_id',$id)->count();

        if($items > 0)
        {
            return response()->json(['error' => 'Product found in this Model. Not Possible to delete'], 404);
        }

        DB::begintransaction();

        try {

            $model->delete();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Model Deleted Successfully'],200);
    }
}
