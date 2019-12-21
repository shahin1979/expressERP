<?php

namespace App\Http\Controllers\Accounts\Ledger;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Trans\Transaction;
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

        return view('accounts.ledger.glhead-index',compact('groups'));
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
                        data-name="'. $ledgers->acc_name . '"
                        type="button" class="btn btn-sm btn-ledger-edit btn-primary pull-center"><i class="fa fa-edit" >Edit</i></button>
                        <button data-remote="head/delete/'.$ledgers->id.'"  type="button" class="btn btn-delete btn-sm btn-danger"><i class="fa fa-trash">Delete</i></button>
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
        $group = GeneralLedger::query()->where('ledger_code',$request['ledger_code'])->where('is_group',true)->first();

        $request['company_id'] = $this->company_id;
        $request['user_id'] = $this->user_id;
        $max_acc = GeneralLedger::query()->where('ledger_code',$request['ledger_code'])->max('acc_no');
        $request['acc_no'] = $max_acc + 2;
        $request['acc_type'] = $group->acc_type;
        $request['type_code'] = $group->type_code;
        $request['is_group'] = false;

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
     */
    public function update(Request $request, $id)
    {
        $ledger = GeneralLedger::query()->find($id);
        $ledger->acc_name = $request['acc_name'];

        DB::beginTransaction();

        try{

            $ledger->save();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success' => 'GL Head Updated ','acc_name'=>$request['acc_name']], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
//     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ledger = GeneralLedger::query()->find($id);

        if($ledger->start_dr + $ledger->start_cr <> 0)

        {
            return response()->json(['error' => 'Transaction Exists. Not Possible To Delete'], 404);
        }

        if(Transaction::query()->where('acc_no',$ledger->acc_no)
            ->where('company_id',$this->company_id)->exists())
        {
            return response()->json(['error' => 'Transaction Exists. Not Possible To Delete'], 404);
        }

        DB::beginTransaction();

        try{

            $ledger->delete();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success' => 'GL Head Deleted '], 200);
    }
}
