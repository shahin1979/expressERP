<?php


namespace App\Traits;


use Carbon\Carbon;

trait TransactionsTrait
{
    public function get_fiscal_year($date)
    {
//        $start_date = Carbon::createFromFormat('d-m-Y',$date)->format('Y-m-d');

//        $end_dt = Carbon::createFromFormat('d-m-Y',$date)->endOfMonth();

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
