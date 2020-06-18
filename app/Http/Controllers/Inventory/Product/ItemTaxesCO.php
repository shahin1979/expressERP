<?php

namespace App\Http\Controllers\Inventory\Product;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Product\ItemTax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use function Matrix\trace;

class ItemTaxesCO extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return view('inventory.product.item-tax-index');
    }

    public function getTaxesDBData()
    {
        $taxes = ItemTax::query()->where('company_id',$this->company_id)->get();
//            ->where('status',true);

        return DataTables::of($taxes)
            ->addColumn('action', function ($taxes) {

                return '<div class="btn-tax btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="view/'.$taxes->id.'"  type="button" class="btn btn-view-tax btn-sm btn-success"><i class="fa fa-book-open">View</i></button>
                    <button data-rowid="'. $taxes->id . '"
                        data-name="'. $taxes->name . '"
                        data-applicable="'. $taxes->applicable_on . '"
                        data-ledger="'. $taxes->acc_no . '"
                        data-rate = "'. $taxes->rate . '"
                        data-calculating = "'. $taxes->calculating_mode . '"
                        data-description = "'. $taxes->description . '"
                        data-status = "'. $taxes->status . '"
                        type="button" class="btn btn-sm btn-tax-edit btn-primary pull-center"><i class="fa fa-edit" >Edit</i></button>
                    <button data-remote="taxes/delete/'.$taxes->id.'"  type="button" class="btn btn-tax-delete btn-sm btn-danger"><i class="fa fa-trash">Delete</i></button>
                    </div>

                    ';
            })
            ->editColumn('status', function ($taxes) {
                return $taxes->status == true ? 'Active' : 'Disable';
            })
            ->rawColumns(['action','status'])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        DB::begintransaction();

        try {

            $ids = ItemTax::query()->create([
                'company_id' => $this->company_id,
                'name' => $request['name'],
                'applicable_on' => $request['applicable_on'],
                'rate' => $request['rate'],
                'calculating_mode' => $request['calculating_mode'],
                'description' => $request['description'],
                'acc_no' =>$request['acc_no'],
                'status' => true,
                'user_id' => $this->user_id
            ]);

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->withInput()->with('error',$error);
        }

        DB::commit();

        return redirect()->action('Inventory\Product\ItemTaxesCO@index')->with('success','New Tax Added ');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id)
    {
        $itemTax = ItemTax::query()->find($id);
        $itemTax->name = $request['name'];
        $itemTax->status = $request['status'] == 'true' ? 1 : 0;
        $itemTax->applicable_on = $request['applicable_on'];
        $itemTax->rate = $request['rate'];
        $itemTax->calculating_mode = $request['calculating_mode'];
        $itemTax->description = $request['description'];
        $itemTax->acc_no = $request['acc_no'];

        DB::begintransaction();

        try {

            $itemTax->save();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Tax Updated Successfully'],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
