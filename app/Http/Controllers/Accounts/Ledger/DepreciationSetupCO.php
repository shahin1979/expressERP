<?php

namespace App\Http\Controllers\Accounts\Ledger;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\DepreciationMO;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Company\FiscalPeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class DepreciationSetupCO extends Controller
{
    public function index()
    {

//        $fpData =FiscalPeriod::query()
//            ->where('depreciation',false)
//            ->where('company_id',$this->company_id)
//            ->orderBy('fpno', 'asc')->first();
//
//        dd($fpData);

        $ledgers = GeneralLedger::query()->where('company_id',$this->company_id)
            ->where('type_code',11)->pluck('acc_name','acc_no');

        $contra = GeneralLedger::query()->where('company_id',$this->company_id)
            ->where('acc_type','E')->pluck('acc_name','acc_no');

        return view('accounts.ledger.depreciation-setup-index',compact('ledgers','contra'));
    }

    public function getDepreciationData()
    {
        $rows = DepreciationMO::query()
            ->where('company_id',$this->company_id)->with('account');

        return DataTables::of($rows)
            ->addColumn('action', function ($rows) {

                return '<div class="btn-group btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="view/'.$rows->id.'"  type="button" class="btn btn-view btn-sm btn-success"><i class="fa fa-book-open">View</i></button>
                    <button data-id="'. $rows->id . '"
                        data-name="'. $rows->acc_name . '"
                        data-type="'. $rows->type_code . '"
                        type="button" class="btn btn-sm btn-group-edit btn-primary pull-center"><i class="fa fa-edit" >Edit</i></button>
                    <button data-remote="group/delete/'.$rows->id.'"  type="button" class="btn btn-delete btn-sm btn-danger"><i class="fa fa-trash">Delete</i></button>
                    </div>

                    ';
            })
            ->rawColumns(['action','status'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $fpData =FiscalPeriod::query()
            ->where('depreciation',false)
            ->where('company_id',$this->company_id)
            ->orderBy('fpno', 'asc')->first();



        DB::beginTransaction();

        try {

            DepreciationMO::query()->insert(
                ['COMPANY_ID' => $this->company_id,
                    'END_DATE' => $fpData->enddate,
                    'START_DATE' =>$fpData->startdate,
                    'ACC_NO' => $request['acc_no'],
                    'DEP_RATE' => $request['rate'],
                    'CONTRA_ACC'=>$request['contra_acc'],
                    'FP_NO'=>$fpData->fpno,
                    'USER_ID' => $this->user_id
                ]
            );

        }catch(\Exception $e)
        {
            DB::rollback();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success' => 'Account Added For Depreciation'], 200);
    }
}
