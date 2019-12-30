<?php

namespace App\Http\Controllers\Accounts\Trans;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Trans\Transaction;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = CompanyProperty::query()->where('company_id',$this->company_id)->first();





        return view('accounts.trans.journal-transaction-index');
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
        $tr_date = Carbon::createFromFormat('d-m-Y',$request['trans_date'])->format('Y-m-d');
        $period = Carbon::createFromFormat('d-m-Y',$request['trans_date'])->format('Y-M');
        $fp_no = get_fp_from_month_sl(Carbon::createFromFormat('d-m-Y',$request['trans_date'])->format('m'),$this->company_id);
        $trans_id = Carbon::now()->format('Ymdhmis');

//        dd($request->all());

        DB::beginTransaction();

        try{

            $tr_code =  TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','JV')
                ->where('fiscal_year',$this->get_fiscal_year($request['trans_date']))
                ->lockForUpdate()->first();

            $voucher_no = $tr_code->last_trans_id;

            // DEBIT TRANSACTION ENTRY

            for($i=0; $i< count($request['id']); $i++)
            {

                if($request['drAmt'][$i] > 0 )
                {
                    $ledger_code = Str::substr($request['accDr'][$i],0,3);

                    Transaction::create([
                        'COMPANY_ID' => $this->company_id,
                        'PROJECT_ID' => $request['project_code'][$i],
                        'TR_CODE' => 'JV',
                        'TRANS_TYPE'=>$request['type_id'],
                        'PERIOD' => $period,
                        'FP_NO' => $fp_no,
                        'TRANS_ID' => $trans_id,
                        'TRANS_GROUP_ID' => $trans_id,
                        'TRANS_DATE' => $tr_date,
                        'VOUCHER_NO' => $voucher_no,
                        'ACC_NO' => $request['accDr'][$i],
                        'CONTRA_ACC'=>null,
                        'DR_AMT' => $request['drAmt'][$i],
                        'CR_AMT' => 0,
                        'TRANS_AMT' => $request['drAmt'][$i],
                        'CURRENCY' => get_currency($this->company_id),
                        'FISCAL_YEAR' => $this->fiscal_year,
                        'TRANS_DESC1' => $request['trans_desc'],
                        'TRANS_DESC2' => 'journal Transaction',
                        'POST_FLAG' => False,
                        'USER_ID' => $this->user_id
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
                    Transaction::create([
                        'COMPANY_ID' => $this->company_id,
                        'PROJECT_ID' => $request['project_code'],
                        'TR_CODE' => 'JV',
                        'PERIOD' => $period,
                        'TRANS_TYPE'=>$request['type_id'],
                        'FP_NO' => $fp_no,
                        'CHEQUE_NO'=>null,
                        'TRANS_ID' => $trans_id,
                        'TRANS_GROUP_ID' => $trans_id,
                        'TRANS_DATE' => $tr_date,
                        'VOUCHER_NO' => $voucher_no,
                        'ACC_NO' => $request['accCr'][$i],
                        'DR_AMT' => 0,
                        'CR_AMT' => $request['crAmt'][$i],
                        'TRANS_AMT' => $request['crAmt'][$i],
                        'CURRENCY' => get_currency($this->company_id),
                        'FISCAL_YEAR' => $this->fiscal_year,
                        'TRANS_DESC1' => $request['trans_desc'],
                        'TRANS_DESC2' => 'Journal Credit Transaction',
                        'POST_FLAG' => False,
                        'USER_ID' => $this->user_id
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
//            $request->session('error', $error.' '.$voucher_no.' Not Saved');
//            return redirect()->back();
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
