<?php

namespace App\Http\Controllers\Inventory\Sales;

use App\Http\Controllers\Controller;
use App\Models\Company\Relationship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CreateCustomerCO extends Controller
{
    public function index()
    {

        return view('inventory.sales.customer-info-index');
    }

    public function getCustomerData()
    {
        $customers = Relationship::query()->where('company_id',$this->company_id)
            ->where('relation_type','CS')->get();

        return DataTables::of($customers)
            ->addColumn('action', function ($customers) {

                return '<div class="btn-customer btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="edit/' . $customers->id . '" data-rowid="'. $customers->id . '"
                        data-category="'. $customers->category_id . '"
                        data-name="'. $customers->name . '"
                        data-ledger="'. $customers->acc_no . '"
                        type="button" class="btn btn-sm btn-customer-edit btn-primary pull-center">Edit</i></button>
                    <button data-remote="customer/delete/'.$customers->id.'"  type="button" class="btn btn-customer-delete btn-sm btn-danger">Delete</i></button>
                    </div>

                    ';
            })
            ->editColumn('status',function ($customers){
                return $customers->status == 1 ? 'Active' : 'Inactive';
            })
            ->rawColumns(['action','status'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request['company_id']= $this->company_id;
        $request['relation_type']='CS'; // Credit Sales
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

        return redirect()->action('Inventory\Sales\CreateCustomerCO@index')->with('success','Customer Successfully Added');
    }
}
