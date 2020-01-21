<?php


namespace App\Traits;


use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Trans\Transaction;
use App\Models\Company\FiscalPeriod;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait TransactionsTrait
{
    public function get_fiscal_year($date,$company_id)
    {
        $cal_date = Carbon::createFromFormat('d-m-Y',$date)->format('Y-m-d');

        $data = FiscalPeriod::query()->where('company_id',$company_id)
            ->whereDate('start_date','<=',$cal_date)
            ->whereDate('end_date','>=',$cal_date)->first();

        return $data->fiscal_year;

    }

    public function create_fiscal_year($date)
    {
        $f_y1 = Carbon::createFromFormat('d-m-Y',$date)->format('Y');
        $f_y2 = Carbon::createFromFormat('d-m-Y',$date)->addMonth(11)->format('Y');
        $fiscal_year = $f_y1.'-'.$f_y2;

        return $fiscal_year;
    }

    // Date comes from Database

    public function get_fiscal_year_db_date($company_id, $date)
    {
        $line = FiscalPeriod::query()->where('company_id',$company_id)
            ->whereDate('start_date','<=',$date)
            ->whereDate('end_date','>=',$date)->first();

        return $line->fiscal_year;
    }

    public function create_fiscal_period($company_id,$date)
    {
//        DB::statement('TRUNCATE TABLE fiscal_periods;');

        $start_date = Carbon::createFromFormat('d-m-Y',$date)->format('Y-m-d');
        $fiscal_period = $this->create_fiscal_year($date);

        for ($m=1; $m <=12; $m++) {

            $year = Carbon::parse($start_date)->format('Y');
            $fpNo= $m;

            $month_sl = Carbon::parse($start_date)->format('m');
            $month = date('F', mktime(0,0,0,$month_sl, 1, date('Y')));
            $status = 'A';


            FiscalPeriod::query()->create([
                'company_id' => $company_id,
                'fiscal_year' => $fiscal_period,
                'year' =>$year,
                'fp_no' => $fpNo,
                'month_serial' => $month_sl,
                'month_name' => $month,
                'start_date' => Carbon::parse($start_date)->format('Y-m-d'),
                'end_date' => Carbon::parse($start_date)->format( 'Y-m-t' ),
                'status' => $status,
                'depreciation' => False,
            ]);

            $start_date = Carbon::parse($start_date)->addMonth(1);

        }

        return $start_date;
    }

    public function transaction_entry(array $input)

    {

        Transaction::query()->create([
            'company_id' => $input['company_id'],
            'project_id' => $input['project_id'],
            'tr_code' => $input['tr_code'],
            'trans_type_id'=>$input['trans_type_id'],
            'period' => $input['period'],
            'fp_no' => $input['fp_no'],
            'trans_id' => $input['trans_id'],
            'trans_group_id' => $input['trans_group_id'],
            'trans_date' => $input['trans_date'],
            'voucher_no' => $input['voucher_no'],
            'acc_no' => $input['acc_no'],
            'contra_acc'=>$input['contra_acc'],
            'dr_amt' => $input['dr_amt'],
            'cr_amt' => $input['cr_amt'],
            'trans_amt' => $input['trans_amt'],
            'currency' => $input['currency'],
            'fiscal_year' => $input['fiscal_year'],
            'trans_desc1' => $input['trans_desc1'],
            'trans_desc2' => $input['trans_desc2'],
            'post_flag' => False,
            'user_id' => $input['user_id']
        ]);



        if($input['dr_amt'] > 0)
        {
            GeneralLedger::query()->where('acc_no',$input['acc_no'])
                ->where('company_id',$this->company_id)
                ->increment('dr_00', $input['dr_amt']);

            GeneralLedger::query()->where('acc_no',$input['acc_no'])
                ->where('company_id',$this->company_id)
                ->increment('curr_bal', $input['dr_amt']);


            GeneralLedger::query()->where('ledger_code',$input['ledger_code'])
                ->where('company_id',$this->company_id)
                ->where('is_group',true)
                ->increment('dr_00', $input['dr_amt']);


            GeneralLedger::query()->where('ledger_code',$input['ledger_code'])
                ->where('company_id',$this->company_id)
                ->where('is_group',true)
                ->increment('curr_bal', $input['dr_amt']);
        }
//
        if($input['cr_amt'] > 0)
        {
            GeneralLedger::query()->where('acc_no',$input['acc_no'])
                ->where('company_id',$this->company_id)
                ->increment('cr_00', $input['cr_amt']);

            GeneralLedger::query()->where('acc_no',$input['acc_no'])
                ->where('company_id',$this->company_id)
                ->decrement('curr_bal', $input['cr_amt']);


            GeneralLedger::query()->where('ledger_code',$input['ledger_code'])
                ->where('company_id',$this->company_id)
                ->where('is_group',true)
                ->increment('cr_00', $input['cr_amt']);


            GeneralLedger::query()->where('ledger_code',$input['ledger_code'])
                ->where('company_id',$this->company_id)
                ->where('is_group',true)
                ->decrement('curr_bal', $input['cr_amt']);

        }

    return $input;

    }
}
