<?php

namespace App\Http\Controllers\Accounts\Ledger;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Common\AccountType;
use App\Models\Accounts\Common\AccountTypeDetail;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Company\FiscalPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class GLGroupCo extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {

        $types = AccountTypeDetail::query()
            ->pluck('description','type_code');

        return view('accounts.gledger.gl-group-index',compact('types'));
    }

    public function getGLGroupData()
    {
        $ledgers = GeneralLedger::query()->where('is_group',True)
            ->where('company_id',$this->company_id)->with('details');

        return DataTables::of($ledgers)
            ->addColumn('action', function ($ledgers) {

                return '<div class="btn-group btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="view/'.$ledgers->id.'"  type="button" class="btn btn-view btn-sm btn-success"><i class="fa fa-book-open">View</i></button>
                    <button data-remote="edit/' . $ledgers->id . '" data-rowid="'. $ledgers->id . '"
                        data-name="'. $ledgers->name . '"
                        data-shortname="'. $ledgers->short_name . '"
                        data-code="'. $ledgers->department_code . '"
                        data-top="'. $ledgers->top_rank . '"
                        data-email="'. $ledgers->email . '"
                        data-description="'. $ledgers->description . '"
                        data-leave = "'. $ledgers->leave_steps . '"
                        type="button" href="#department-update-modal" data-target="#department-update-modal" data-toggle="modal" class="btn btn-sm btn-department-edit btn-primary pull-center"><i class="fa fa-edit" >Edit</i></button>
                    <button data-remote="delete/'.$ledgers->id.'"  type="button" class="btn btn-delete btn-sm btn-danger"><i class="fa fa-trash">Delete</i></button>
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


    public function store(Request $request)
    {
        $group_type = AccountTypeDetail::query()->where('type_code',$request['TYPE_CODE'])
            ->with('parent')->first();


//        $type = $group_type->type->description;

//        dd(Str::substr($group_type->parent->description,0,1));

        $request['COMPANY_ID'] = $this->company_id;
        $request['USER_ID'] = $this->user_id;
        $max_ledger = GeneralLedger::query()->where('acc_type',Str::substr($group_type->parent->description,0,1))->max('ledger_code');
        $ledger_code = $max_ledger+1;
        $acc_no = $ledger_code.'12100';
        $request['ACC_NO'] = $acc_no;
        $request['LEDGER_CODE'] = $ledger_code;
        $request['ACC_TYPE'] = Str::substr($group_type->parent->description,0,1);
//        $request['TYPE_CODE'] = $request['TYPE_CODE'];
        $request['IS_GROUP'] = true;

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

        return response()->json(['success' => 'New Group Head Added ','acc_name'=>$request['acc_name']], 200);
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
