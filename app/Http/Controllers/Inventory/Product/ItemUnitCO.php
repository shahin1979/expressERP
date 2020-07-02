<?php

namespace App\Http\Controllers\Inventory\Product;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Product\ItemUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ItemUnitCO extends Controller
{
    public function index()
    {
//        $units = ItemUnit::query()->where('company_id',$this->company_id)
//            ->where('name','KG')
//            ->with('parent')->get();
//
//        dd($units);

        $units = ItemUnit::query()->where('company_id',$this->company_id)->where('status',true)
            ->pluck('name','id');
        return view('inventory.product.item-unit-index',compact('units'));
    }

    public function getUnitDBData()
    {
        $units = ItemUnit::query()->where('company_id',$this->company_id)
//            ->where('name','KG')
            ->with('parent');

        return DataTables::of($units)
            ->addColumn('action', function ($units) {

                return '<div class="btn-unit btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="view/'.$units->id.'"  type="button" class="btn btn-view btn-sm btn-success"><i class="fa fa-book-open">View</i></button>
                    <button data-remote="edit/' . $units->id . '" data-rowid="'. $units->id . '"
                        data-name="'. $units->name . '"
                        data-formal="'. $units->formal_name . '"
                        data-decimal="'. $units->no_of_decimal_places . '"
                        data-secondary="'. $units->transformed_name . '"
                        data-formula="'. $units->transformed_formula . '"
                        type="button" class="btn btn-sm btn-unit-edit btn-primary pull-center"><i class="fa fa-edit" >Edit</i></button>
                    <button data-remote="unit/delete/'.$units->id.'"  type="button" class="btn btn-unit-delete btn-sm btn-danger"><i class="fa fa-trash">Delete</i></button>
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

            $ids = ItemUnit::query()->create([
                'company_id' => $this->company_id,
                'name' => $request['name'],
                'status' => true,
                'formal_name' => $request['formal_name'],
                'no_of_decimal_places' => $request['no_of_decimal_places'],
                'transformed_name' => $request['transformed_name'],
                'transformed_formula' => $request['transformed_formula'],
                'user_id' => $this->user_id
            ]);

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error',$error);
        }

        DB::commit();

        return redirect()->action('Inventory\Product\ItemUnitCO@index')->with('success','New Unit Added ');
    }

    public function update(Request $request, $id)
    {
        $unit = ItemUnit::query()->find($id);
        $unit->name = $request['name'];
        $unit->formal_name = $request['formal_name'];
        $unit->no_of_decimal_places = $request['no_of_decimal_places'];
        $unit->transformed_name = $request['transformed_name'];
        $unit->transformed_formula = $request['transformed_formula'];

        DB::begintransaction();

        try {

            $unit->save();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Unit Updated Successfully'],200);
    }
}
