<?php

namespace App\Http\Controllers\Accounts\Costcenter;

use App\Http\Controllers\Company\FiscalPeriodCO;
use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\CostCenter;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Trans\Transaction;
use App\Models\Common\UserActivity;
use App\Models\Company\FiscalPeriod;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CostCenterCO extends Controller
{
    use TransactionsTrait;

    public function index()
    {

        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>42002,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        $months = FiscalPeriod::query()->where('company_id',$this->company_id)
            ->where('fiscal_year',$this->get_fiscal_year(Carbon::now()->format('d-m-Y'),$this->company_id))->get();

        return view('accounts.costcenter.index.cost-center-index',compact('months'));
    }

    public function getdata()
    {
        $costs = CostCenter::query()->where('company_id',$this->company_id)
            ->where('status',true)->get();

        return DataTables::of($costs)
            ->addColumn('balance',function ($costs){
                return $costs->current_year_budget - abs($costs->current_year_budget);
            })
            ->addColumn('action', function ($costs) {

                return '<button data-remote="edit/'.$costs->id.'"
                                data-number="'.$costs->acc_no.'"
                                data-name="'.$costs->name.'"
                                data-bdt00="'.$costs->current_year_budget.'"
                                data-bdt01="'.$costs->bgt_01.'"
                                data-bdt02="'.$costs->bgt_02.'"
                                data-bdt03="'.$costs->bgt_03.'"
                                data-bdt04="'.$costs->bgt_04.'"
                                data-bdt05="'.$costs->bgt_05.'"
                                data-bdt06="'.$costs->bgt_06.'"
                                data-bdt07="'.$costs->bgt_07.'"
                                data-bdt08="'.$costs->bgt_08.'"
                                data-bdt09="'.$costs->bgt_09.'"
                                data-bdt10="'.$costs->bgt_10.'"
                                data-bdt11="'.$costs->bgt_11.'"
                                data-bdt12="'.$costs->bgt_12.'"
                    type="button" class="btn btn-edit btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</button>
                <button data-remote="report/'.$costs->id.'"  type="button" class="btn btn-report btn-xs btn-info pull-right"><i class="glyphicon glyphicon-list"></i> Report</button>';
            })
            ->rawColumns(['balance','action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $fiscal = $this->get_fiscal_data_from_current_date($this->company_id);

        try {

            $total = 0;
            for($i=1; $i<=12; $i++)
            {
                $total = $total + $request['bgt_'.str_pad($i,2,"0",STR_PAD_LEFT)];
            }
            $request['fiscal_year'] = $fiscal->fiscal_year;
            $request['current_year_budget'] = $total;
            $request['company_id'] = $this->company_id;
            $request['user_id'] = $this->user_id;

            $request['budget_01'] = $request['bgt_01'];
            $request['budget_02'] = $request['bgt_02'];
            $request['budget_03'] = $request['bgt_03'];
            $request['budget_04'] = $request['bgt_04'];
            $request['budget_05'] = $request['bgt_05'];
            $request['budget_06'] = $request['bgt_06'];
            $request['budget_07'] = $request['bgt_07'];
            $request['budget_08'] = $request['bgt_08'];
            $request['budget_09'] = $request['bgt_09'];
            $request['budget_10'] = $request['bgt_10'];
            $request['budget_11'] = $request['bgt_11'];
            $request['budget_12'] = $request['bgt_12'];

            CostCenter::query()->create($request->all());

        } catch (\Exception $e) {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with(['error'=>$error]);
        }

        DB::commit();

        return redirect()->action('Accounts\Costcenter\CostCenterCO@index')->with('success','Cost Center Created');

    }

    public function summary(Request $request)
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>42065,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);



        if(!empty($request['action']))
        {
            $fiscal = $this->get_fiscal_data_from_current_date($this->company_id);
            $report = CostCenter::query()->where('fiscal_year',$fiscal->fiscal_year)->get();

            $amount = Transaction::query()->whereNotNull('cost_center_id')->get();

            $params = collect();
//            $params['to_date'] = $request['date_to'];

//            dd($fiscal);

            return view('accounts.costcenter.index.summary-report-index',compact('report','fiscal','amount'));
        }

        return view('accounts.costcenter.index.summary-report-index');
    }
}
