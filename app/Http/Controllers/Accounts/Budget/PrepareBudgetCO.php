<?php

namespace App\Http\Controllers\Accounts\Budget;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Common\UserActivity;
use App\Models\Company\FiscalPeriod;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PrepareBudgetCO extends Controller
{
    use TransactionsTrait;

    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>43005,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        $months = FiscalPeriod::query()->where('company_id',$this->company_id)
            ->where('fiscal_year',$this->get_fiscal_year(Carbon::now()->format('d-m-Y'),$this->company_id))->get();

        return view('accounts.budget.prepare-budget-index')->with('months',$months);
    }

    public function getBudgetInfo()
    {
        $accounts = GeneralLedger::query()->where('company_id',$this->company_id)
            ->where('acc_type','E')
            ->where('is_group',false)
            ->with('details')->get();

        return DataTables::of($accounts)
            ->addColumn('balance',function ($accounts){
                return $accounts->cyr_bgt_tr - abs($accounts->curr_bal);
            })
            ->addColumn('action', function ($accounts) {

                return '<button data-remote="edit/'.$accounts->id.'"
                                data-accNo="'.$accounts->acc_no.'"
                                data-accName="'.$accounts->acc_name.'"
                                data-bdt00="'.$accounts->cyr_bgt_tr.'"
                                data-bdt01="'.$accounts->bgt_01.'"
                                data-bdt02="'.$accounts->bgt_02.'"
                                data-bdt03="'.$accounts->bgt_03.'"
                                data-bdt04="'.$accounts->bgt_04.'"
                                data-bdt05="'.$accounts->bgt_05.'"
                                data-bdt06="'.$accounts->bgt_06.'"
                                data-bdt07="'.$accounts->bgt_07.'"
                                data-bdt08="'.$accounts->bgt_08.'"
                                data-bdt09="'.$accounts->bgt_09.'"
                                data-bdt10="'.$accounts->bgt_10.'"
                                data-bdt11="'.$accounts->bgt_11.'"
                                data-bdt12="'.$accounts->bgt_12.'"
                    type="button" class="btn btn-edit btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</button>
                <button data-remote="report/'.$accounts->id.'"  type="button" class="btn btn-report btn-xs btn-info pull-right"><i class="glyphicon glyphicon-list"></i> Report</button>';
            })
            ->rawColumns(['balance','action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        dd($request->all());

    }
}
