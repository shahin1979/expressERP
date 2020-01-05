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

class ReceiveTransactionCO extends Controller
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

        $debits = GeneralLedger::query()->where('company_id',$this->company_id)
            ->whereIn('ledger_code',[$company->cash,$company->bank])
            ->orderBy('acc_no')
            ->where('is_group',false)->pluck('acc_name','acc_no');

        $grp_credits = GeneralLedger::query()->where('company_id',$this->company_id)
            ->where('is_group',true)
            ->orderBy('acc_name')
            ->pluck('acc_name','ledger_code');

        $credits = GeneralLedger::query()->where('company_id',$this->company_id)
//            ->whereNotIn('ledger_code',[$company->cash,$company->bank])
            ->orderBy('acc_name','ASC')
            ->where('is_group',false)->pluck('acc_name','acc_no');

        $projects = Project::query()->where('company_id',$this->company_id)
            ->whereNotIn('status',['C'])->pluck('project_name','id');

        return view('accounts.trans.receive-transaction-index',compact('debits','credits','grp_credits','projects'));
    }

    public function getCreditHead(Request $request)
    {
        $input = $request['option'];

        $accList = GeneralLedger::query()->where('company_id',$this->company_id)
            ->where('ledger_code',$input)->where('is_group',false)
            ->orderBy('acc_name')
            ->pluck('acc_name','acc_no');

//        dd($input);

        return response()->json($accList);
    }

    public function getDebitBalance(Request $request)
    {
        $input = $request['option'];

        $balance = GeneralLedger::query()->where('company_id',$this->company_id)
            ->where('acc_no',$input)->value('curr_bal');

        return response()->json($balance);
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

            $data = $request->all();
            $trans_amt = 0;

            $tr_code =  TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','RC')
                ->where('fiscal_year',$this->get_fiscal_year($request['trans_date'],$this->company_id))
                ->lockForUpdate()->first();

            $voucher_no = $tr_code->last_trans_id;




            // CREDIT TRANSACTION ENTRY

            for($i=0; $i< count($request['id']); $i++)
            {

//                if($i==1)
//                {
//
//                    dd($request->all());
//                }

                $ledger_code = Str::substr($request['accCr'][$i],0,3);

                Transaction::create([
                    'company_id' => $this->company_id,
                    'project_id' => $request['project_code'],
                    'tr_code' => 'RC',
                    'trans_type_id'=>$request['type_id'],
                    'period' => $period,
                    'fp_no' => $fp_no,
                    'trans_id' => $trans_id,
                    'trans_group_id' => $trans_id,
                    'trans_date' => $tr_date,
                    'voucher_no' => $voucher_no,
                    'acc_no' => $request['accCr'][$i],
                    'contra_acc'=>$request['acc_dr'],
                    'dr_amt' => 0,
                    'cr_amt' => $request['transAmt'][$i],
                    'trans_amt' => $request['transAmt'][$i],
                    'currency' => get_currency($this->company_id),
                    'fiscal_year' => $this->fiscal_year,
                    'trans_desc1' => $request['transDesc'][$i],
                    'trans_desc2' => $request->filled('trans_desc2') ? $request['trans_desc2'] : 'Receive Transaction',
                    'post_flag' => False,
                    'user_id' => $this->user_id
                ]);




                $trans_amt = $trans_amt + $request['transAmt'][$i];

                GeneralLedger::query()->where('acc_no',$request['accCr'][$i])
                    ->where('company_id',$this->company_id)
                    ->increment('cr_00', $request['transAmt'][$i]);

                GeneralLedger::query()->where('acc_no',$request['accCr'][$i])
                    ->where('company_id',$this->company_id)
                    ->decrement('curr_bal', $request['transAmt'][$i]);

                GeneralLedger::query()->where('ledger_code',$ledger_code)
                    ->where('company_id',$this->company_id)
                    ->where('is_group',true)
                    ->increment('cr_00', $request['transAmt'][$i]);

                GeneralLedger::query()->where('ledger_code',$ledger_code)
                    ->where('company_id',$this->company_id)
                    ->where('is_group',true)
                    ->decrement('curr_bal', $request['transAmt'][$i]);


            }

// DEBIT TRANSACTION ENTRY

            Transaction::create([
                'company_id' => $this->company_id,
                'project_id' => $request['project_code'],
                'tr_code' => 'RC',
                'trans_type_id'=>$request['type_id'],
                'period' => $period,
                'fp_no' => $fp_no,
                'cheque_no'=>$request['chk_no'],
                'trans_id' => $trans_id,
                'trans_group_id' => $trans_id,
                'trans_date' => $tr_date,
                'voucher_no' => $voucher_no,
                'acc_no' => $request['acc_dr'],
                'dr_amt' => $trans_amt,
                'cr_amt' => 0,
                'trans_amt' => $trans_amt,
                'currency' => get_currency($this->company_id),
                'fiscal_year' => $this->fiscal_year,
                'trans_desc1' => $request->filled('trans_desc2') ? $request['trans_desc2'] : 'Payment Transaction',
                'trans_desc2' => 'Debit Transaction',
                'post_flag' => False,
                'user_id' => $this->user_id
            ]);

            $ldgr_debit = Str::substr($request['acc_dr'],0,3);


            GeneralLedger::query()->where('acc_no',$request['acc_dr'])
                ->where('company_id',$this->company_id)
                ->increment('dr_00', $trans_amt);

            GeneralLedger::query()->where('acc_no',$request['acc_dr'])
                ->where('company_id',$this->company_id)
                ->increment('curr_bal', $trans_amt);


            GeneralLedger::query()->where('ledger_code',$ldgr_debit)
                ->where('company_id',$this->company_id)
                ->where('is_group',true)
                ->increment('dr_00', $trans_amt);


            GeneralLedger::query()->where('ledger_code',$ldgr_debit)
                ->where('company_id',$this->company_id)
                ->where('is_group',true)
                ->increment('curr_bal', $trans_amt);

            TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','RC')
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

        return redirect()->action('Accounts\Trans\ReceiveTransactionCO@index')->with('success',$voucher_no.' '.'Saved for authorization');

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
