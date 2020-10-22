<?php

namespace App\Http\Controllers\Accounts\Ledger;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\DepreciationMO;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Trans\Transaction;
use App\Models\Common\UserActivity;
use App\Models\Company\FiscalPeriod;
use App\Models\Company\TransCode;
use App\Traits\AccountTrait;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class DepreciationSetupCO extends Controller
{
    use TransactionsTrait;

    public function index()
    {

//        dd($this->get_account_balance('10112101',$this->company_id));

        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>42020,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        $ledgers = GeneralLedger::query()->where('company_id',$this->company_id)
            ->where('type_code',11)->where('is_group',false)
            ->pluck('acc_name','acc_no');

        $contra = GeneralLedger::query()->where('company_id',$this->company_id)
            ->where('acc_type','E')->where('is_group',false)
            ->orderBy('acc_name')
            ->pluck('acc_name','acc_no');

        $period = FiscalPeriod::query()->where('company_id',$this->company_id)
            ->where('depreciation',false)->where('status','A')
            ->orderBy('fp_no')->first();

        $depreciation = DepreciationMO::query()->where('company_id',$this->company_id)
            ->where('fp_no',$period->fp_no)->where('fiscal_year',$period->fiscal_year)
            ->get();

//        dd($period->fp_no);

        foreach ($depreciation as $row)
        {
            $start = $this->get_account_opening_balance($row->acc_no,$this->company_id,$period->start_date);
            $end = $this->get_account_date_balance($row->acc_no,$this->company_id,$period->end_date);
            $dep_amt = (($start*$row->dep_rate)/100 + (($end - $start)*$row->dep_rate)/200)/12;
            $closing = $end - $dep_amt;

            $row->open_bal = $start;
            $row->additional_bal = $end - $start;
            $row->total_bal = $end;
            $row->dep_amt = $dep_amt;
            $row->closing_bal = $closing;
            $row->save();
        }


        return view('accounts.ledger.depreciation-setup-index',compact('ledgers','contra','period'));
    }

    public function getDepreciationData()
    {
        $period = FiscalPeriod::query()->where('company_id',$this->company_id)
            ->where('depreciation',false)->where('status','A')
            ->orderBy('fp_no')->first();

//        $period = $this->get_fiscal_data_from_current_date($this->company_id);

        $rows = DepreciationMO::query()
            ->where('company_id',$this->company_id)
            ->where('approve_status',false)
            ->where('fp_no',$period->fp_no)
            ->where('fiscal_year',$period->fiscal_year)
            ->with('account');

        return DataTables::of($rows)
            ->addColumn('acc_name', function ($rows) {
                return $rows->account->acc_name .'<br/>'. $rows->acc_no;
            })
            ->addColumn('action', function ($rows) {

                return '<div class="btn-group btn-group-sm" role="group" aria-label="Action Button">
                    <button data-id="'. $rows->id . '"
                        data-name="'. $rows->acc_name . '"
                        data-type="'. $rows->type_code . '"
                        type="button" class="btn btn-sm btn-group-edit btn-primary pull-center"><i class="fa fa-edit" >Edit</i></button>
                    <button data-remote="group/delete/'.$rows->id.'"  type="button" class="btn btn-delete btn-sm btn-danger"><i class="fa fa-trash">Delete</i></button>
                    </div>

                    ';
            })
            ->rawColumns(['action','status','acc_name'])
            ->make(true);
    }

    public function create(Request $request)
    {
        $period =

        $fpData =FiscalPeriod::query()
            ->where('depreciation',false)
            ->where('company_id',$this->company_id)
            ->orderBy('fp_no', 'asc')->first();

        DB::beginTransaction();

        try {

            DepreciationMO::query()->insert(
                ['company_id' => $this->company_id,
                    'end_date' => $fpData->end_date,
                    'start_date' =>$fpData->start_date,
                    'fiscal_year'=>$fpData->fiscal_year,
                    'acc_no' => $request['acc_no'],
                    'dep_rate' => $request['rate'],
                    'contra_acc'=>$request['contra_acc'],
                    'fp_no'=>$fpData->fp_no,
                    'user_id' => $this->user_id
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

    public function store(Request $request)
    {

        $today = Carbon::now()->format('Y-m-d');

        $period = FiscalPeriod::query()->where('company_id',$this->company_id)
            ->where('depreciation',false)->where('status','A')
            ->where('fp_no',$request->fp_no)->where('year',$request->year)->first();

        if($period->end_date != $today)
        {
            $error = 'Today is not End of Month';
            return response()->json(['error' => $error], 404);
        }

        $depreciation = DepreciationMO::query()->where('company_id',$this->company_id)
            ->where('fp_no',$request->fp_no)->where('fiscal_year',$period->fiscal_year)
            ->get();

        $contra = $depreciation->unique('contra_acc');

        $input = [];

        DB::beginTransaction();

        try {


            foreach ($contra as $c_acc)
            {

                $tr_code = TransCode::query()->where('company_id', $this->company_id)
                    ->where('trans_code', 'JV')
                    ->where('fiscal_year', $period->fiscal_year)
                    ->lockForUpdate()->first();

                $voucher_no = $tr_code->last_trans_id;
                $trans_amt = 0;

                foreach ($depreciation as $row) {

                    if($row->contra_acc == $c_acc->contra_acc)
                    {

                        $start = $this->get_account_opening_balance($row->acc_no, $this->company_id, $period->start_date);
                        $end = $this->get_account_date_balance($row->acc_no, $this->company_id, $period->end_date);
                        $dep_amt = (($start * $row->dep_rate) / 100 + (($end - $start) * $row->dep_rate) / 200) / 12;

                        if($dep_amt > 0)
                        {
                            $input['company_id'] = $this->company_id;
                            $input['project_id'] = null;
                            $input['tr_code'] = 'JV';
                            $input['fp_no'] = $period->fp_no;
                            $input['trans_type_id'] = 6; //  Depreciation
                            $input['period'] = Carbon::parse($period->end_date)->format('Y-M');
                            $input['trans_id'] = Carbon::now()->format('Ymdhmis');
                            $input['trans_group_id'] = Carbon::now()->format('Ymdhmis');
                            $input['trans_date'] = $period->end_date;
                            $input['voucher_no'] = $voucher_no;
                            $input['acc_no'] = $row->acc_no;
                            $input['ledger_code'] = Str::substr($row->acc_no,0,3);
                            $input['contra_acc'] = $row->contra_acc;
                            $input['dr_amt'] = 0;
                            $input['cr_amt'] = $dep_amt;
                            $input['trans_amt'] = $dep_amt;
                            $input['currency'] = get_currency($this->company_id);
                            $input['fiscal_year'] = $period->fiscal_year;
                            $input['trans_desc1'] = 'Depreciation For The Month ' . $period->month_name . ',' . $period->year;
                            $input['trans_desc2'] = 'Depreciation';
                            $input['post_flag'] = false;
                            $input['user_id'] = $this->user_id;

                            $output = $this->transaction_entry($input); // Make Transaction

                            $trans_amt = $trans_amt + $dep_amt;
                        }

                        DepreciationMO::query()->insert([

                            'company_id'=>$this->company_id,
                            'acc_no'=>$row->acc_no,
                            'fp_no'=> $period->fp_no == 12 ? 1 : $period->fp_no + 1,
                            'fiscal_year'=>$period->fp_no == 12 ? '9999-9999' : $period->fiscal_year,
                            'start_date'=>'2100-01-01',
                            'end_date'=>'2100-01-31',
                            'dep_rate'=>$row->dep_rate,
                            'contra_acc'=>$row->contra_acc,
                            'user_id'=>$this->user_id
                        ]);
                    }
                }

                $input['acc_no'] = $c_acc->contra_acc;
                $input['ledger_code'] = Str::substr($c_acc->contra_acc,0,3);
                $input['contra_acc'] = $c_acc->contra_acc;
                $input['dr_amt'] = $trans_amt;
                $input['cr_amt'] = 0;
                $input['trans_amt'] = $dep_amt;

                $this->transaction_entry($input);

                TransCode::query()->where('company_id',$this->company_id)
                    ->where('trans_code','JV')
                    ->increment('last_trans_id');
            }

            FiscalPeriod::query()->where('company_id',$this->company_id)
                ->where('fp_no',$period->fp_no)->where('fiscal_year',$period->fiscal_year)
                ->update(['depreciation'=>true]);

            DepreciationMO::query()->where('company_id',$this->company_id)
                ->where('fp_no',$period->fp_no)->where('fiscal_year',$period->fiscal_year)
                ->update(['approve_status'=>true,'approve_date'=>$period->end_date, 'authorizer_id'=>$this->user_id]);

        }catch(\Exception $e)
        {
            DB::rollback();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success' => 'Depreciation Posted'], 200);

    }
}
