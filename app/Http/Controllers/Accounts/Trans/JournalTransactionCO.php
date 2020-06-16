<?php

namespace App\Http\Controllers\Accounts\Trans;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Trans\Transaction;
use App\Models\Common\UserActivity;
use App\Models\Company\CompanyProperty;
use App\Models\Company\TransCode;
use App\Models\Projects\Project;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JournalTransactionCO extends Controller
{
    use TransactionsTrait;
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>44015,'user_id'=>$this->user_id
            ],['updated_at'=>Carbon::now()
        ]);

        $company = CompanyProperty::query()->where('company_id',$this->company_id)->first();

        return view('accounts.trans.journal-transaction-index');
    }

    /**
     * Show the form for creating a new resource.
     *
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
        $tr_date = Carbon::createFromFormat('d-m-Y',$request['trans_date'])->format('Y-m-d');
        $period = Carbon::createFromFormat('d-m-Y',$request['trans_date'])->format('Y-M');
        $fp_no = get_fp_from_month_sl(Carbon::createFromFormat('d-m-Y',$request['trans_date'])->format('m'),$this->company_id);
        $trans_id = Carbon::now()->format('Ymdhmis');

//        dd($request->all());

        DB::beginTransaction();

        try{

            $tr_code =  TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','JV')
                ->where('fiscal_year',$this->get_fiscal_year($request['trans_date'],$this->company_id))
                ->lockForUpdate()->first();

            $voucher_no = $tr_code->last_trans_id;

            // DEBIT TRANSACTION ENTRY

            for($i=0; $i< count($request['id']); $i++)
            {

                if($request['drAmt'][$i] > 0 )
                {
                    $ledger_code = Str::substr($request['accDr'][$i],0,3);

                    Transaction::query()->create([
                        'company_id' => $this->company_id,
                        'project_id' => $request['project_code'],
                        'tr_code' => 'JV',
                        'trans_type_id'=>$request['type_id'],
                        'period' => Str::upper($period),
                        'fp_no' => $fp_no,
                        'trans_id' => $trans_id,
                        'trans_group_id' => $trans_id,
                        'trans_date' => $tr_date,
                        'voucher_no' => $voucher_no,
                        'acc_no' => $request['accDr'][$i],
                        'contra_acc'=>null,
                        'dr_amt' => $request['drAmt'][$i],
                        'cr_amt' => 0,
                        'trans_amt' => $request['drAmt'][$i],
                        'currency' => get_currency($this->company_id),
                        'fiscal_year' => $this->fiscal_year,
                        'trans_desc1' => $request['trans_desc'],
                        'trans_desc2' => 'journal Transaction',
                        'post_flag' => False,
                        'user_id' => $this->user_id
                    ]);


                    GeneralLedger::query()->where('acc_no',$request['accDr'][$i])
                        ->where('company_id',$this->company_id)
                        ->increment('dr_00', $request['drAmt'][$i]);

                    GeneralLedger::query()->where('acc_no',$request['accDr'][$i])
                        ->where('company_id',$this->company_id)
                        ->increment('curr_bal', $request['drAmt'][$i]);


                    GeneralLedger::query()->where('ledger_code',$ledger_code)
                        ->where('company_id',$this->company_id)
                        ->where('is_group',true)
                        ->increment('dr_00', $request['drAmt'][$i]);


                    GeneralLedger::query()->where('ledger_code',$ledger_code)
                        ->where('company_id',$this->company_id)
                        ->where('is_group',true)
                        ->increment('curr_bal', $request['drAmt'][$i]);
                }

                if($request['crAmt'][$i] > 0)
                {
                    Transaction::query()->create([
                        'company_id' => $this->company_id,
                        'project_id' => $request['project_code'],
                        'tr_code' => 'JV',
                        'period' => Str::upper($period),
                        'trans_type_id'=>$request['type_id'],
                        'fp_no' => $fp_no,
                        'cheque_no'=>null,
                        'trans_id' => $trans_id,
                        'trans_group_id' => $trans_id,
                        'trans_date' => $tr_date,
                        'voucher_no' => $voucher_no,
                        'acc_no' => $request['accCr'][$i],
                        'dr_amt' => 0,
                        'cr_amt' => $request['crAmt'][$i],
                        'trans_amt' => $request['crAmt'][$i],
                        'currency' => get_currency($this->company_id),
                        'fiscal_year' => $this->fiscal_year,
                        'trans_desc1' => $request['trans_desc'],
                        'trans_desc2' => 'Journal Credit Transaction',
                        'post_flag' => False,
                        'user_id' => $this->user_id
                    ]);

                    $ldgr_credit = Str::substr($request['accCr'][$i],0,3);


                    GeneralLedger::query()->where('acc_no',$request['accCr'][$i])
                        ->where('company_id',$this->company_id)
                        ->increment('cr_00', $request['crAmt'][$i]);

                    GeneralLedger::query()->where('acc_no',$request['accCr'][$i])
                        ->where('company_id',$this->company_id)
                        ->decrement('curr_bal', $request['crAmt'][$i]);


                    GeneralLedger::query()->where('ledger_code',$ldgr_credit)
                        ->where('company_id',$this->company_id)
                        ->where('is_group',true)
                        ->increment('cr_00', $request['crAmt'][$i]);


                    GeneralLedger::query()->where('ledger_code',$ldgr_credit)
                        ->where('company_id',$this->company_id)
                        ->where('is_group',true)
                        ->decrement('curr_bal', $request['crAmt'][$i]);

                    TransCode::query()->where('company_id',$this->company_id)
                        ->where('trans_code','JV')
                        ->increment('last_trans_id');
                }

            }
        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error',$voucher_no.' '.$error);
        }

        DB::commit();

        return redirect()->action('Accounts\Trans\JournalTransactionCO@index')->with('success',$voucher_no.' '.'Saved for authorization');
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
