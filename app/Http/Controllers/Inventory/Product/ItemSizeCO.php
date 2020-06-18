<?php

namespace App\Http\Controllers\Inventory\Product;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Product\ItemSize;
use App\Models\Inventory\Product\ProductMO;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ItemSizeCO extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>51025,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        return view('inventory.product.item-size-index');
    }

    public function getSizeDBData()
    {
        $sizes = ItemSize::query()->where('company_id',$this->company_id);

        return DataTables::of($sizes)
            ->addColumn('action', function ($sizes) {

                return '<div class="btn-unit btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="view/'.$sizes->id.'"  type="button" class="btn btn-view btn-sm btn-success"><i class="fa fa-book-open">View</i></button>
                    <button data-remote="edit/' . $sizes->id . '" data-rowid="'. $sizes->id . '"
                        data-size="'. $sizes->size . '"
                        data-description="'. $sizes->description . '"
                        type="button" class="btn btn-sm btn-size-edit btn-primary pull-center"><i class="fa fa-edit" >Edit</i></button>
                    <button data-remote="itemSize/delete/'.$sizes->id.'"  type="button" class="btn btn-size-delete btn-sm btn-danger"><i class="fa fa-trash">Delete</i></button>
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

            $ids = ItemSize::query()->create([
                'company_id' => $this->company_id,
                'size' => $request['size'],
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

        return redirect()->action('Inventory\Product\ItemSizeCO@index')->with('success','New Size Added ');
    }

    public function update(Request $request, $id)
    {
        $size = ItemSize::query()->find($id);
        $size->size = $request['size'];
        $size->description = $request['description'];

        DB::begintransaction();

        try {

            $size->save();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Size Updated Successfully'],200);
    }

    public function destroy($id)
    {
        $size = ItemSize::query()->find($id);

        $items = ProductMO::query()->where('company_id',$this->company_id)
            ->where('size_id',$id)->count();

        if($items > 0)
        {
            return response()->json(['error' => 'Product found in this Size. Not Possible to delete'], 404);
        }

        DB::begintransaction();

        try {

            $size->delete();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Size Deleted Successfully'],200);
    }
}
