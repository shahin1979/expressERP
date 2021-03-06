<?php

namespace App\Http\Controllers\Inventory\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Company\Relationship;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SupplierInfoCO extends Controller
{
    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>53002,'user_id'=>$this->user_id
            ],['updated_at'=>Carbon::now()
        ]);

        return view('inventory.purchase.supplier-info-index');
    }

    public function getSupplierData()
    {
        $suppluers = Relationship::query()->where('company_id',$this->company_id)
            ->whereIn('relation_type',['LS','SP'])
            ->orderBy('id')
            ->get();

        return DataTables::of($suppluers)
            ->addColumn('action', function ($suppluers) {

                return '<div class="btn-unit btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="edit/' . $suppluers->id . '" data-rowid="'. $suppluers->id . '"
                        data-address="'. $suppluers->address . '"
                        data-name="'. $suppluers->name . '"
                        data-ledger="'. $suppluers->acc_no . '"
                        type="button" class="btn btn-sm btn-supplier-edit btn-primary"><i class="fa fa-edit" >Edit</i></button>
                    <button data-remote="supplier/delete/'.$suppluers->id.'"  type="button" class="btn btn-supplier-delete btn-sm btn-danger"><i class="fa fa-trash">Delete</i></button>
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
