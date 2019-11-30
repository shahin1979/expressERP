<?php

namespace App\Http\Controllers\Accounts\Trans;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Trans\Transaction;
use App\Models\Company\CompanyProperty;
use App\Models\Company\FiscalPeriod;
use App\Models\Company\TransCode;
use App\Models\Projects\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Response;
//use Illuminate\Support\Facades\Response;

class PaymentTransactionsCO extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {


//        $balance = GeneralLedger::where('company_id',1)
//            ->where('acc_no','10112101')->value('curr_bal');
//
//        dd($balance);

        $company = CompanyProperty::query()->where('company_id',$this->company_id)->first();

        $credits = GeneralLedger::query()->where('company_id',$this->company_id)
            ->whereIn('ledger_code',[$company->cash,$company->bank])
            ->orderBy('acc_no')
            ->where('is_group',false)->pluck('acc_name','acc_no');

        $grp_debits = GeneralLedger::query()->where('company_id',$this->company_id)
            ->where('is_group',true)
            ->orderBy('acc_name')
            ->pluck('acc_name','ledger_code');

        $debits = GeneralLedger::query()->where('company_id',$this->company_id)
//            ->whereNotIn('ledger_code',[$company->cash,$company->bank])
            ->orderBy('acc_name','ASC')
            ->where('is_group',false)->pluck('acc_name','acc_no');

        $projects = Project::query()->where('company_id',$this->company_id)
            ->whereNotIn('status',['C'])->pluck('project_name','id');

        return view('accounts.trans.payment-transaction-index',compact('debits','credits','grp_debits','projects'));
    }

    public function getDebitHead(Request $request)
    {
        $input = $request['option'];

        $accList = GeneralLedger::query()->where('company_id',$this->company_id)
            ->where('ledger_code',$input)->where('is_group',false)
            ->orderBy('acc_name')
            ->pluck('acc_name','acc_no');

//        dd($input);

        return response()->json($accList);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCreditBalance(Request $request)
    {
        $input = $request['option'];

        $balance = GeneralLedger::query()->where('company_id',$this->company_id)
            ->where('acc_no',$input)->value('curr_bal');

        return response()->json($balance);
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

        DB::beginTransaction();

        try{

            $data = $request->all();
            $trans_amt = 0;

            $tr_code =  TransCode::query()->where('company_id',$this->company_id)->where('trans_code','PM')
                ->lockForUpdate()->first();

            $voucher_no = $tr_code->last_trans_id;

           // DEBIT TRANSACTION ENTRY

            for($i=0; $i< count($data['id']); $i++)
            {

                $ledger_code = Str::substr($request['accDr'][$i],0,3);

                Transaction::create([
                    'COMPANY_ID' => $this->company_id,
                    'PROJECT_ID' => $request['project_code'],
                    'TR_CODE' => 'PM',
                    'TRANS_TYPE'=>$request['type_id'],
                    'PERIOD' => $period,
                    'FP_NO' => $fp_no,
                    'TRANS_ID' => $trans_id,
                    'TRANS_GROUP_ID' => $trans_id,
                    'TRANS_DATE' => $tr_date,
                    'VOUCHER_NO' => $voucher_no,
                    'ACC_NO' => $request['accDr'][$i],
                    'CONTRA_ACC'=>$request['acc_cr'],
                    'DR_AMT' => $request['transAmt'][$i],
                    'CR_AMT' => 0,
                    'TRANS_AMT' => $request['transAmt'][$i],
                    'CURRENCY' => get_currency($this->company_id),
                    'FISCAL_YEAR' => $this->fiscal_year,
                    'TRANS_DESC1' => $request['transDesc'][$i],
                    'TRANS_DESC2' => $request->filled('trans_desc2') ? $request['trans_desc2'] : 'Payment Transaction',
                    'POST_FLAG' => False,
                    'USER_ID' => $this->user_id
                ]);

                $trans_amt = $trans_amt + $request['transAmt'][$i];


                GeneralLedger::query()->where('acc_no',$request['accDr'][$i])
                    ->where('company_id',$this->company_id)
                    ->increment('dr_00', $request['transAmt'][$i]);

                GeneralLedger::query()->where('acc_no',$request['accDr'][$i])
                    ->where('company_id',$this->company_id)
                    ->increment('curr_bal', $request['transAmt'][$i]);


                GeneralLedger::query()->where('ledger_code',$ledger_code)
                    ->where('company_id',$this->company_id)
                    ->where('is_group',true)
                    ->increment('dr_00', $request['transAmt'][$i]);


                GeneralLedger::query()->where('ledger_code',$ledger_code)
                    ->where('company_id',$this->company_id)
                    ->where('is_group',true)
                    ->increment('curr_bal', $request['transAmt'][$i]);



//
//
//                AccountModel::where('accNo',$request->input('accCr'))
//                    ->where('compCode',$comp_code)
//                    ->increment('cr00', $request->input('transAmt')[$i]);
//
//                AccountModel::where('accNo',$request->input('accCr'))
//                    ->where('compCode',$comp_code)
//                    ->decrement('currBal', $request->input('transAmt')[$i]);
//
//                AccountModel::where('ldgrCode',GenUtil::get_cash_ledger_code($comp_code))
//                    ->where('compCode',$comp_code)->where('isGroup',True)
//                    ->increment('cr00', $request->input('transAmt')[$i]);
//
//                AccountModel::where('ldgrCode',GenUtil::get_cash_ledger_code($comp_code))
//                    ->where('compCode',$comp_code)->where('isGroup',True)
//                    ->decrement('currBal', $request->input('transAmt')[$i]);


            }

// CREDIT TRANSACTION ENTRY

            Transaction::create([
                'COMPANY_ID' => $this->company_id,
                'PROJECT_ID' => $request['project_code'],
                'TR_CODE' => 'PM',
                'TRANS_TYPE'=>$request['type_id'],
                'PERIOD' => $period,
                'FP_NO' => $fp_no,
                'CHEQUE_NO'=>$request['chk_no'],
                'TRANS_ID' => $trans_id,
                'TRANS_GROUP_ID' => $trans_id,
                'TRANS_DATE' => $tr_date,
                'VOUCHER_NO' => $voucher_no,
                'ACC_NO' => $request['acc_cr'],
                'DR_AMT' => 0,
                'CR_AMT' => $trans_amt,
                'TRANS_AMT' => $trans_amt,
                'CURRENCY' => get_currency($this->company_id),
                'FISCAL_YEAR' => $this->fiscal_year,
                'TRANS_DESC1' => $request->filled('trans_desc2') ? $request['trans_desc2'] : 'Payment Transaction',
                'TRANS_DESC2' => 'Debit Transaction',
                'POST_FLAG' => False,
                'USER_ID' => $this->user_id
            ]);

            $ldgr_credit = Str::substr($request['acc_cr'],0,3);


            GeneralLedger::query()->where('acc_no',$request['acc_cr'])
                ->where('company_id',$this->company_id)
                ->increment('cr_00', $trans_amt);

            GeneralLedger::query()->where('acc_no',$request['acc_cr'])
                ->where('company_id',$this->company_id)
                ->decrement('curr_bal', $trans_amt);


            GeneralLedger::query()->where('ledger_code',$ldgr_credit)
                ->where('company_id',$this->company_id)
                ->where('is_group',true)
                ->increment('cr_00', $trans_amt);


            GeneralLedger::query()->where('ledger_code',$ldgr_credit)
                ->where('company_id',$this->company_id)
                ->where('is_group',true)
                ->decrement('curr_bal', $trans_amt);

            TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','PM')
                ->increment('last_trans_id');

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error',$voucher_no.' '.$error);
//            $request->session('error', $error.' '.$voucher_no.' Not Saved');
//            return redirect()->back();
        }

        DB::commit();

        return redirect()->action('Accounts\Trans\PaymentTransactionsCO@index')->with('success',$voucher_no.' '.'Saved for authorization');


    }

//    public function checkDate(Request $request)
//    {
//        $tr_date = Carbon::createFromFormat('d-m-Y',$request['trans_date'])->format('Y-m-d');
//
//        if(date_validation($tr_date) == false)
//        {
//            return response()->json(['error'=>'Invalid Date Selected'],404);
//        }
//        return response()->json(['success'=>$request['trans_date']],200);
//
//    }

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
