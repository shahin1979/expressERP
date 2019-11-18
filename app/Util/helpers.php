<?php
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Company\FiscalPeriod;

if (!function_exists('int_random')) {

    /**
     * generate secure random numbers
     *
     * @param int $min
     * @param int $max
     *
     * @param int $bytes
     *
     * @return int|number
     */
    function int_random($min = 10000000, $max = 99999999, $bytes = 4)
    {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $strong = true;
            $n = 0;

            do {
                $n = hexdec(
                    bin2hex(openssl_random_pseudo_bytes($bytes, $strong))
                );
            } while ($n < $min || $n > $max);

            return $n;
        } else {
            return mt_rand($min, $max);
        }
    }
}


if (!function_exists('convert_to_camel_case')) {

    /**
     * generate secure random numbers
     *
     * @param int $min
     * @param int $max
     *
     * @param int $bytes
     *
     * @return int|number
     */
    function convert_to_camel_case(string $value, string $encoding = null) {
        if ($encoding == null){
            $encoding = mb_internal_encoding();
        }

        $stripChars = "()[]{}=?!.:,-_+\"#~/";
        $len = strlen( $stripChars );

        for($i = 0; $len > $i; $i ++) {
            $value = str_replace( $stripChars [$i], " ", $value );
        }
        $value = mb_convert_case( $value, MB_CASE_TITLE, $encoding );
        $value = preg_replace( "/\s+/", " ", $value );

        return $value;
    }
}

if (!function_exists('get_account_balance')) {

    /**
     * generate secure random numbers
     *
     * @param int $min
     * @param int $max
     *
     * @param int $bytes
     *
     * @return int|number
     */
    function get_account_balance($acc_no, $company_id)
    {
        $balance = GeneralLedger::query()->where('company_id',$company_id)
            ->where('acc_no','10112101')->value('curr_bal');

//        dd($balance);

        return $balance;
    }
}


if (!function_exists('date_validation')) {

    /**
     * Check inputed date in between fiscal period
     *
     * @param $money
     *
     * @param bool $returnMoneyObject
     *
     * @return mixed
     */
    function date_validation($tr_date)
    {
        $start_date = FiscalPeriod::query()->where('status',true)->where('fpno',1)->value('startdate');
        $end_date = FiscalPeriod::query()->where('status',true)->where('fpno',5)->value('enddate');

        if($tr_date >= $start_date and $tr_date <= $end_date)
        {
            return true;
        }

        return false;
    }
}

if (!function_exists('get_fp_from_month_sl')) {
    function get_fp_from_month_sl($monthsl, $company_id)
    {

        $fpno = FiscalPeriod::where('MONTHSL',(int)$monthsl)
            ->where('company_id',$company_id)
            ->value('fpno');

        return $fpno;
    }
}


if (!function_exists('get_currency')) {
    function get_currency($company_id)
    {
        $currency = \App\Models\Company\CompanyProperty::query()
            ->where('company_id',$company_id)
            ->value('currency');

        return $currency;
    }
}



