<?php

namespace App\Http\Controllers\Accounts\Ledger;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class GLAccountHeadCo extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {

        $groups = GeneralLedger::query()->where('is_group',true)
            ->where('company_id',$this->company_id)
            ->select(DB::Raw("concat(concat(acc_name,' : '), acc_type) as acc_name"),'ledger_code')
            ->orderBy('acc_name')
            ->pluck('acc_name','ledger_code');

        return view('accounts.gledger.glhead-index',compact('groups'));
    }

    public function getGLAccountHeadData()
    {
        $ledgers = GeneralLedger::query()->where('is_group',false)
            ->where('company_id',$this->company_id)->with('details')->with('parent');

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

    }

    public function store(Request $request)
    {
        $group = GeneralLedger::query()->where('ledger_code',$request['LEDGER_CODE'])->where('is_group',true)->first();

        $request['COMPANY_ID'] = $this->company_id;
        $request['USER_ID'] = $this->user_id;
        $max_acc = GeneralLedger::query()->where('ledger_code',$request['LEDGER_CODE'])->max('acc_no');
        $request['ACC_NO'] = $max_acc + 2;
        $request['ACC_TYPE'] = $group->acc_type;
        $request['TYPE_CODE'] = $group->type_code;
        $request['IS_GROUP'] = false;

        DB::begintransaction();

        try {

            GeneralLedger::query()->create($request->all());

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success' => 'New GL Head Added ','acc_name'=>$request['acc_name']], 200);
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
