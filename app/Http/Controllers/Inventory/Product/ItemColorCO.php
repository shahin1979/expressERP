<?php

namespace App\Http\Controllers\Inventory\Product;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Product\ItemColor;
use App\Models\Inventory\Product\ItemSize;
use App\Models\Inventory\Product\ProductMO;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ItemColorCO extends Controller
{
    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>51030,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        return view('inventory.product.item-color-index');
    }

    public function getColorDBData()
    {
        $colors = ItemColor::query()->where('company_id',$this->company_id);

        return DataTables::of($colors)
            ->addColumn('action', function ($colors) {

                return '<div class="btn-unit btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="view/'.$colors->id.'"  type="button" class="btn btn-view btn-sm btn-success"><i class="fa fa-book-open">View</i></button>
                    <button data-remote="edit/' . $colors->id . '" data-rowid="'. $colors->id . '"
                        data-name="'. $colors->name . '"
                        data-description="'. $colors->description . '"
                        type="button" class="btn btn-sm btn-color-edit btn-primary pull-center"><i class="fa fa-edit" >Edit</i></button>
                    <button data-remote="itemColor/delete/'.$colors->id.'"  type="button" class="btn btn-color-delete btn-sm btn-danger"><i class="fa fa-trash">Delete</i></button>
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

            $ids = ItemColor::query()->create([
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

        return redirect()->action('Inventory\Product\ItemColorCO@index')->with('success','New Color Added ');
    }

    public function update(Request $request, $id)
    {
        $color = ItemColor::query()->find($id);
        $color->name = $request['name'];
        $color->description = $request['description'];

        DB::begintransaction();

        try {

            $color->save();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Color Updated Successfully'],200);
    }

    public function destroy($id)
    {
        $color = ItemColor::query()->find($id);

        $items = ProductMO::query()->where('company_id',$this->company_id)
            ->where('color_id',$id)->count();

        if($items > 0)
        {
            return response()->json(['error' => 'Product found in this Color. Not Possible to delete'], 404);
        }

        DB::begintransaction();

        try {

            $color->delete();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Color Deleted Successfully'],200);
    }


}
