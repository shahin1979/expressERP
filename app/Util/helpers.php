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

if (!function_exists('get_account_curr_balance')) {

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
    function get_account_curr_balance($acc_no, $company_id)
    {
        $balance = GeneralLedger::query()->where('company_id',$company_id)
            ->where('acc_no',$acc_no)->value('curr_bal');

//        dd($balance);

        return $balance;
    }
}

// get account name from account number


if (!function_exists('get_account_name_from_number')) {

    /**
     * generate secure random numbers
     *
     */
    function get_account_name_from_number($company_id,$acc_no)
    {
        $ledger = GeneralLedger::query()->where('company_id',$company_id)
            ->where('acc_no',$acc_no)->first();

        return $ledger->acc_name;
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
        $start_date = FiscalPeriod::query()->where('status',true)->where('fp_no',1)->value('start_date');
        $end_date = FiscalPeriod::query()->where('status',true)->where('fp_no',5)->value('end_date');

        if($tr_date >= $start_date and $tr_date <= $end_date)
        {
            return true;
        }

        return false;
    }
}

if (!function_exists('get_fp_from_month_sl')) {
    function get_fp_from_month_sl($month_serial, $company_id)
    {

        $fpno = FiscalPeriod::where('month_serial',(int)$month_serial)
            ->where('company_id',$company_id)
            ->value('fp_no');

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

if (!function_exists('convertToCamelCase')) {

    function convertToCamelCase(string $value, string $encoding = null) {
        if ($encoding == null)
        {
            $encoding = mb_internal_encoding();
        }

        $stripChars = "()[]{}=?!.:,-_+\"#~/";
        $len = strlen( $stripChars );

            for($i = 0; $len > $i; $i ++)
            {
                $value = str_replace( $stripChars [$i], " ", $value );
            }
        $value = mb_convert_case( $value, MB_CASE_TITLE, $encoding );
        $value = preg_replace( "/\s+/", " ", $value );

        return $value;
    }
}

if (!function_exists('dateDifference')) {

    /**
     *
     * @param int $min
     * @param int $max
     *
     * @param int $bytes
     *
     * @return int|number
     */
    function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat);
    }

}
// Convert No to word in Bangladeshi Format

if (!function_exists('convert_number_to_words_bd_format')) {

    function convert_number_to_words_bd_format($number) {

        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'Zero',
            1                   => 'One',
            2                   => 'Two',
            3                   => 'Three',
            4                   => 'Four',
            5                   => 'Five',
            6                   => 'Six',
            7                   => 'Seven',
            8                   => 'Eight',
            9                   => 'Nine',
            10                  => 'Ten',
            11                  => 'Eleven',
            12                  => 'Twelve',
            13                  => 'Thirteen',
            14                  => 'Fourteen',
            15                  => 'Fifteen',
            16                  => 'Sixteen',
            17                  => 'Seventeen',
            18                  => 'Eighteen',
            19                  => 'Nineteen',
            20                  => 'Twenty',
            30                  => 'Thirty',
            40                  => 'Fourty',
            50                  => 'Fifty',
            60                  => 'Sixty',
            70                  => 'Seventy',
            80                  => 'Eighty',
            90                  => 'Ninety',
            100                 => 'Hundred',
            1000                => 'Thousand',
            100000              => 'Lac',
            10000000            => 'Crore',
            1000000000          => 'Billion',
            1000000000000       => 'Trillion',
            1000000000000000    => 'Quadrillion',
            1000000000000000000 => 'Quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . convert_number_to_words_bd_format(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . convert_number_to_words_bd_format($remainder);
                }
                break;

            case ($number > 99999 && $number < 10000000) :


                    $baseUnit = pow(100000, floor(log($number, 100000)));
                    $numBaseUnits = (int) ($number / $baseUnit);
                    $remainder = $number % $baseUnit;

                    $string = convert_number_to_words_bd_format($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                    if ($remainder) {
                        $string .= $remainder < 100 ? $conjunction : $separator;
                        $string .= convert_number_to_words_bd_format($remainder);
                    }

                break;

            case ($number > 10000000) :

                $baseUnit = pow(10000000, floor(log($number, 10000000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;

                $string = convert_number_to_words_bd_format($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= convert_number_to_words_bd_format($remainder);
                }

                break;

            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = convert_number_to_words_bd_format($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= convert_number_to_words_bd_format($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }
}

// International Format

if (!function_exists('convert_number_to_words')) {

    function convert_number_to_words($number) {

        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'Zero',
            1                   => 'One',
            2                   => 'Two',
            3                   => 'Three',
            4                   => 'Four',
            5                   => 'Five',
            6                   => 'Six',
            7                   => 'Seven',
            8                   => 'Eight',
            9                   => 'Nine',
            10                  => 'Ten',
            11                  => 'Eleven',
            12                  => 'Twelve',
            13                  => 'Thirteen',
            14                  => 'Fourteen',
            15                  => 'Fifteen',
            16                  => 'Sixteen',
            17                  => 'Seventeen',
            18                  => 'Eighteen',
            19                  => 'Nineteen',
            20                  => 'Twenty',
            30                  => 'Thirty',
            40                  => 'Fourty',
            50                  => 'Fifty',
            60                  => 'Sixty',
            70                  => 'Seventy',
            80                  => 'Eighty',
            90                  => 'Ninety',
            100                 => 'Hundred',
            1000                => 'Thousand',
            1000000             => 'Million',
            1000000000          => 'Billion',
            1000000000000       => 'Trillion',
            1000000000000000    => 'Quadrillion',
            1000000000000000000 => 'Quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }


}


if (!function_exists('addMonths')) {

    /**
     * get company address
     *
     * @param int $min
     * @param int $max
     *
     * @param int $bytes
     *
     * @return int|number
     */
    function addMonths($date, $months)
    {
        {
            $date = new \DateTime($date);
            $date->modify("+" . $months . " months");

            $date->modify("-1 day");

            return $date->format('Y-m-d');
        }
    }
}


if (!function_exists('getNextDay')) {

    /**
     * get Next day from given date
     *
     * @param int $min
     * @param int $max
     *
     * @param int $bytes
     *
     * @return int|number
     */
    function getNextDay($date_1)
    {

        $date_2 = date('Y-m-d', strtotime('+1 day', strtotime($date_1)));

        return $date_2;
    }

}


if (!function_exists('dateDifference')) {

    /**
     * get company address
     *
     * @param int $min
     * @param int $max
     *
     * @param int $bytes
     *
     * @return int|number
     */
    function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat);
    }

}

