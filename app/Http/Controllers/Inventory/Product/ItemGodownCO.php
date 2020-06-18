<?php

namespace App\Http\Controllers\Inventory\Product;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Product\Godown;
use App\Models\Inventory\Product\ItemModel;
use App\Models\Inventory\Product\ProductMO;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ItemGodownCO extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>51040,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        return view('inventory.product.item-godown-index');
    }

    public function getGodownDBData()
    {
        $godowns = Godown::query()->where('company_id',$this->company_id);

        return DataTables::of($godowns)
            ->addColumn('action', function ($godowns) {

                return '<div class="btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="view/'.$godowns->id.'"  type="button" class="btn btn-view btn-sm btn-success"><i class="fa fa-book-open">View</i></button>
                    <button data-rowid="'. $godowns->id . '"
                        data-name="'. $godowns->name . '"
                        data-address="'. $godowns->address . '"
                        data-description="'. $godowns->description . '"
                        type="button" class="btn btn-sm btn-godown-edit btn-primary pull-center"><i class="fa fa-edit" >Edit</i></button>
                    <button data-remote="itemGodown/delete/'.$godowns->id.'"  type="button" class="btn btn-godown-delete btn-sm btn-danger"><i class="fa fa-trash">Delete</i></button>
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

            $ids = Godown::query()->create([
                'company_id' => $this->company_id,
                'name' => $request['name'],
                'address' => $request['address'],
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

        return redirect()->action('Inventory\Product\ItemGodownCO@index')->with('success','New Godown Added ');
    }

    public function update(Request $request, $id)
    {
        $godown = Godown::query()->find($id);
        $godown->name = $request['name'];
        $godown->address = $request['address'];
        $godown->description = $request['description'];

        DB::begintransaction();

        try {

            $godown->save();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Store Updated Successfully'],200);
    }

    public function destroy($id)
    {
        $godown = Godown::query()->find($id);

        $items = ProductMO::query()->where('company_id',$this->company_id)
            ->where('godown_id',$id)->count();

        if($items > 0)
        {
            return response()->json(['error' => 'Product found in this Store. Not Possible to delete'], 404);
        }

        DB::begintransaction();

        try {

            $godown->delete();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Store Deleted Successfully'],200);
    }
}
