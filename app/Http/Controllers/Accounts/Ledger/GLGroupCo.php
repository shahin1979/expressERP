<?php

namespace App\Http\Controllers\Accounts\Ledger;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Company\FiscalPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class GLGroupCo extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        DB::enableQueryLog();

//        $ledgers = GeneralLedger::query()->with('details')->get();

//        dd();

        return view('accounts.gledger.gl-group-index');
    }

    public function getGLGroupData()
    {
        $ledgers = GeneralLedger::query()->where('is_group',True)
            ->where('company_id',$this->company_id)->with('details');

        return DataTables::of($ledgers)
            ->addColumn('action', function ($ledgers) {

                return '<div class="btn-group btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="view/'.$ledgers->id.'"  type="button" class="btn btn-view btn-sm btn-secondary"><i class="fa fa-open">View</i></button>
                    <button data-remote="edit/' . $ledgers->id . '" data-rowid="'. $ledgers->id . '" 
                        data-name="'. $ledgers->name . '" 
                        data-shortname="'. $ledgers->short_name . '" 
                        data-code="'. $ledgers->department_code . '"
                        data-top="'. $ledgers->top_rank . '"
                        data-email="'. $ledgers->email . '"
                        data-description="'. $ledgers->description . '"
                        data-leave = "'. $ledgers->leave_steps . '"
                        type="button" href="#department-update-modal" data-target="#department-update-modal" data-toggle="modal" class="btn btn-sm btn-department-edit btn-primary pull-center"><i class="fa fa-edit" >Edit</i></button>
                    </div>
                    ';
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
