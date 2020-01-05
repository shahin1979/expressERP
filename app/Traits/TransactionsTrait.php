<?php


namespace App\Traits;


use App\Models\Company\FiscalPeriod;
use Carbon\Carbon;

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

//    public function get_fp_no($date,$company_id)
//    {
//        $fp_no = get_fp_from_month_sl(Carbon::createFromFormat('d-m-Y',$date)->format('m'),$company_id);
//    }

}
