<?php

namespace App\Http\Controllers\Inventory\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Company\Relationship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SupplierInfoCO extends Controller
{
    public function index()
    {

        return view('inventory.purchase.supplier-info-index');
    }

    public function getSupplierData()
    {
        $suppluers = Relationship::query()->where('company_id',$this->company_id)
            ->where('relation_type','LS')->get();

        return DataTables::of($suppluers)
            ->addColumn('action', function ($suppluers) {

                return '<div class="btn-category btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="edit/' . $suppluers->id . '" data-rowid="'. $suppluers->id . '"
                        data-category="'. $suppluers->category_id . '"
                        data-name="'. $suppluers->name . '"
                        data-ledger="'. $suppluers->acc_no . '"
                        type="button" class="btn btn-sm btn-category-edit btn-primary pull-center">Edit</i></button>
                    <button data-remote="subcategory/delete/'.$suppluers->id.'"  type="button" class="btn btn-category-delete btn-sm btn-danger">Delete</i></button>
                    </div>

                    ';
            })
            ->editColumn('status',function ($suppluers){
                return $suppluers->status == 1 ? 'Active' : 'Inactive';
            })
            ->rawColumns(['action','status'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request['company_id']= $this->company_id;
        $request['relation_type']='LS';
        $request['user_id'] = $this->user_id;

        DB::beginTransaction();

        try {

            Relationship::query()->create($request->all());

        } catch (\Exception $e) {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error',$error);
        }

        DB::commit();

        return redirect()->action('Inventory\Purchase\SupplierInfoCO@index')->with('success','Supplier Successfully Added');
    }
}
