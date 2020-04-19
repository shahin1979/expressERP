<?php

namespace App\Http\Controllers\Accounts\Trans;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Trans\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EditGLVoucherCO extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request)
    {
        $data = null;
        $glheads = GeneralLedger::query()->where('company_id',$this->company_id)
            ->where('is_group',false)
            ->select(DB::Raw("CONCAT(acc_name,CONCAT(' | ',acc_no)) as acc_name"), 'acc_no')
            ->pluck('acc_name','acc_no');

//        dd($glheads->nameaccount);

        if(!empty($request['voucher_no']))
        {
            $data = Transaction::query()->where('company_id',$this->company_id)
                ->where('voucher_no',$request['voucher_no'])->get();

//            $desc = $data->unique('tr_code')->first()->tr_code == 'PM' ? $data->where('cr_amt','>',0)->first()->trans_desc1 : 'Nai';
//
//            dd($desc);

//            $test = $data->unique('voucher_no')->first()->voucher_no;
//            dd($test);

        }
        return view('accounts.trans.edit-gl-voucher-index',compact('data','glheads'));
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
        //
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
    public function update(Request $request)
    {

        $old_data = Transaction::query()->where('company_id',$this->company_id)
            ->where('voucher_no',$request['voucher_no'])->get();

//        dd($request->all());


        DB::beginTransaction();
        {
            try{
                foreach ($old_data as $row)
                {
                    $ledger_code = Str::substr($row->acc_no,0,3);

                    GeneralLedger::query()->where('company_id',$this->company_id)
                        ->where('ledger_code',$ledger_code)->where('is_group',true)
                        ->decrement('dr_00',$row->dr_amt);

                    GeneralLedger::query()->where('company_id',$this->company_id)
                        ->where('ledger_code',$ledger_code)->where('is_group',true)
                        ->decrement('cr_00',$row->cr_amt);

                    GeneralLedger::query()->where('company_id',$this->company_id)
                        ->where('acc_no',$row->acc_no)
                        ->decrement('dr_00',$row->dr_amt);

                    GeneralLedger::query()->where('company_id',$this->company_id)
                        ->where('acc_no',$row->acc_no)
                        ->decrement('cr_00',$row->cr_amt);


                    GeneralLedger::query()->where('company_id',$this->company_id)
                        ->where('ledger_code',$ledger_code)->where('is_group',true)
                        ->decrement('curr_bal',$row->dr_amt);

                    GeneralLedger::query()->where('company_id',$this->company_id)
                        ->where('acc_no',$row->acc_no)
                        ->decrement('curr_bal',$row->dr_amt);

                    GeneralLedger::query()->where('company_id',$this->company_id)
                        ->where('ledger_code',$ledger_code)->where('is_group',true)
                        ->increment('curr_bal',$row->cr_amt);

                    GeneralLedger::query()->where('company_id',$this->company_id)
                        ->where('acc_no',$row->acc_no)
                        ->increment('curr_bal',$row->cr_amt);
                }


                $tr_date = Carbon::createFromFormat('d-m-Y',$request['trans_date'])->format('Y-m-d');
                $period = Carbon::createFromFormat('d-m-Y',$request['trans_date'])->format('Y-M');
                $fp_no = get_fp_from_month_sl(Carbon::createFromFormat('d-m-Y',$request['trans_date'])->format('m'),$this->company_id);

                for($i=0; $i< count($request['id']); $i++)
                {
                    Transaction::query()->where('id',$request['id'][$i])
                        ->update([
                            'cr_amt'=>$request['cr_amt'][$i],
                            'dr_amt'=>$request['dr_amt'][$i],
                            'trans_amt'=> $request['dr_amt'][$i] + $request['dr_amt'][$i],
                            'acc_no'=>$request['acc_no'][$i],
                            'trans_desc1'=>$request['description'][$i],
                            'cheque_no'=>$request['cheque_no'],
                            'trans_date'=>$tr_date,
                            'period'=>$period,
                            'FP_NO'=>$fp_no
                        ]);


                    $ledger_code = Str::substr($request['acc_no'][$i],0,3);

                    GeneralLedger::query()->where('company_id',$this->company_id)
                        ->where('ledger_code',$ledger_code)->where('is_group',true)
                        ->increment('dr_00',$request['dr_amt'][$i]);

                    GeneralLedger::query()->where('company_id',$this->company_id)
                        ->where('ledger_code',$ledger_code)->where('is_group',true)
                        ->increment('cr_00',$request['cr_amt'][$i]);


                    GeneralLedger::query()->where('company_id',$this->company_id)
                        ->where('acc_no',$request['acc_no'][$i])
                        ->increment('dr_00',$request['dr_amt'][$i]);

                    GeneralLedger::query()->where('company_id',$this->company_id)
                        ->where('acc_no',$request['acc_no'][$i])
                        ->increment('cr_00',$request['cr_amt'][$i]);


                    GeneralLedger::query()->where('company_id',$this->company_id)
                        ->where('ledger_code',$ledger_code)->where('is_group',true)
                        ->increment('curr_bal',$request['dr_amt'][$i]);

                    GeneralLedger::query()->where('company_id',$this->company_id)
                        ->where('acc_no',$request['acc_no'][$i])
                        ->decrement('curr_bal',$request['cr_amt'][$i]);

                    GeneralLedger::query()->where('company_id',$this->company_id)
                        ->where('ledger_code',$ledger_code)->where('is_group',true)
                        ->decrement('curr_bal',$request['cr_amt'][$i]);

                    GeneralLedger::query()->where('company_id',$this->company_id)
                        ->where('acc_no',$request['acc_no'][$i])
                        ->increment('curr_bal',$request['dr_amt'][$i]);

                }

            }catch (\Exception $e)
            {
                DB::rollBack();
                $error = $e->getMessage();
                return redirect()->back()->with('error',$error);
            }

            DB::commit();

        }

        return redirect()->action('Accounts\Trans\EditGLVoucherCO@index')->with('success',$request['voucher_no'].' '.'Successfully Updated');
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
