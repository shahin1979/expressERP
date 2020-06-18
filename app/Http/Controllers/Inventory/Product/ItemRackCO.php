<?php

namespace App\Http\Controllers\Inventory\Product;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Product\Godown;
use App\Models\Inventory\Product\ItemModel;
use App\Models\Inventory\Product\ProductMO;
use App\Models\Inventory\Product\Rack;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ItemRackCO extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {

        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>51045,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        $stores = Godown::query()->where('status',true)->pluck('name','id');
        return view('inventory.product.item-rack-index',compact('stores'));
    }

    public function getRackDBData()
    {
        $racks = Rack::query()->where('company_id',$this->company_id)->with('godown');

        return DataTables::of($racks)
            ->addColumn('action', function ($racks) {

                return '<div class="btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="view/'.$racks->id.'"  type="button" class="btn btn-view btn-sm btn-success"><i class="fa fa-book-open">View</i></button>
                    <button data-rowid="'. $racks->id . '"
                        data-name="'. $racks->name . '"
                        data-store="'. $racks->godown_id . '"
                        data-description="'. $racks->description . '"
                        type="button" class="btn btn-sm btn-rack-edit btn-primary pull-center"><i class="fa fa-edit" >Edit</i></button>
                    <button data-remote="itemRack/delete/'.$racks->id.'"  type="button" class="btn btn-rack-delete btn-sm btn-danger"><i class="fa fa-trash">Delete</i></button>
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

            $ids = Rack::query()->create([
                'company_id' => $this->company_id,
                'name' => $request['name'],
                'godown_id' => $request['godown_id'],
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

        return redirect()->action('Inventory\Product\ItemRackCO@index')->with('success','New Rack Added');
    }

    public function update(Request $request, $id)
    {
        $rack = Rack::query()->find($id);
        $rack->name = $request['name'];
        $rack->godown_id = $request['godown_id'];
        $rack->description = $request['description'];

        DB::begintransaction();

        try {

            $rack->save();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Rack Updated Successfully'],200);
    }

    public function destroy($id)
    {
        $rack = Rack::query()->find($id);

        $items = ProductMO::query()->where('company_id',$this->company_id)
            ->where('godown_id',$id)->count();

        if($items > 0)
        {
            return response()->json(['error' => 'Product found in this Store. Not Possible to delete'], 404);
        }

        DB::begintransaction();

        try {

            $rack->delete();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Rack Deleted Successfully'],200);
    }
}
