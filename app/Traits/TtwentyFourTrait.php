<?php


namespace App\Traits;


use Illuminate\Support\Facades\DB;

trait TtwentyFourTrait
{
    public function hpsmOne()
    {
        $start_date = '2021-05-13';
        $end_date = '2026-05-12';
        $g_date = '2021-05-12';
        $principal = 2500000;
        $cash_sec = 10000;
        $principal_x = $principal;
        $first_day_count = 0;
        $err = 9;
        $installment = 55646;
        $profit_size = 3750;
        $last_day_month = date("Y-m-t", strtotime($g_date));
        $first_day_month = date("Y-m-1", strtotime($g_date));
        $second_day_count = dateDifference($last_day_month,$g_date);
        $factor = 36500;
        $profit = round((($principal*365*$err)/$factor),0);
        $outstanding = $principal + $profit;


//        dd(dateDifference($last_day_month,$g_date));

//        $report=[];

//        $report['tr_date'] = date("Y-m-t", strtotime($g_date));

//        dd($report);
        $connection = DB::connection('t24test');

        $connection->statement('TRUNCATE TABLE installment;');
        $connection->statement('TRUNCATE TABLE statement;');
        $connection->statement('TRUNCATE TABLE gl_trans;');


        $connection->table('installment')->insert([
            'serial'=>2,
            'description'=>'Cash Security Recovered',
            'tr_date' => $start_date,
            'installment_amt'=> $cash_sec
        ]);

        $connection->table('installment')->insert([
            'serial'=>3,
            'description'=>'Disbursement',
            'tr_date' => $start_date,
            'installment_amt'=> $principal + $cash_sec
        ]);

        $connection->table('installment')->insert([
            'serial'=>4,
            'principal' => $principal,
            'description'=>'Gestation Profit Charge',
            'tr_date' => $g_date,
            'installment_amt'=> $profit
        ]);


        $connection->table('statement')->insert([
            'serial'=>2,
            'description'=>'Cash Security Recovered',
            'tr_date' => $start_date,
            'cash_security_cr'=> $cash_sec
        ]);

        $connection->table('statement')->insert([
            'serial'=>3,
            'description'=>'Disbursement',
            'tr_date' => $start_date,
            'principal_dr'=> $principal
        ]);

        $connection->table('statement')->insert([
            'serial'=>3,
            'description'=>'Cash Security',
            'tr_date' => $start_date,
            'cash_security_dr'=> $cash_sec
        ]);

        $connection->table('statement')->insert([
            'serial'=>4,
            'description'=>'Gestation Profit',
            'tr_date' => $start_date,
            'profit_dr'=> $profit
        ]);

        $connection->table('gl_trans')->insert([
            'serial'=>2,
            'description'=>'Cash Security Recovered',
            'tr_date' => $start_date,
            'gl_head'=>'Cash/Customer A/C/DD/TT/PO',
            'dr_amt'=> $cash_sec,
            'cr_amt'=>0
        ]);

        $connection->table('gl_trans')->insert([
            'serial'=>2,
            'description'=>'Cash Security Recovered',
            'tr_date' => $start_date,
            'gl_head'=>'HPSM Investment Account',
            'dr_amt'=> 0,
            'cr_amt'=>$cash_sec
        ]);


        $connection->table('gl_trans')->insert([
            'serial'=>3,
            'description'=>'Disbursement',
            'tr_date' => $start_date,
            'gl_head'=>'DD/TT/PO/CASH',
            'dr_amt'=> 0,
            'cr_amt'=>$principal + $cash_sec
        ]);

        $connection->table('gl_trans')->insert([
            'serial'=>3,
            'description'=>'Disbursement',
            'tr_date' => $start_date,
            'gl_head'=>'HPSM Investment Account',
            'dr_amt'=> $principal + $cash_sec,
            'cr_amt'=>0
        ]);


        $connection->table('gl_trans')->insert([
            'serial'=>4,
            'description'=>'Gestation Profit',
            'tr_date' => $g_date,
            'gl_head'=>'Unearned Income',
            'dr_amt'=> 0,
            'cr_amt'=>$profit
        ]);

        $connection->table('gl_trans')->insert([
            'serial'=>4,
            'description'=>'Gestation Profit',
            'tr_date' => $g_date,
            'gl_head'=>'HPSM Investment Account',
            'dr_amt'=> $profit,
            'cr_amt'=>0
        ]);





//        dd('here');


        $sl = 5;

        for ($i=0; $i<60; $i++)
        {
            $tr_date = date("Y-m-t", strtotime($g_date));

            $rent = floor((($principal_x*$first_day_count*$err)/$factor) + (($principal*$second_day_count*$err)/$factor));

            $connection->table('installment')->insert([
                'serial'=>$sl,
                'principal_x' => $principal_x,
                'principal' => $principal,
                'description'=>'Rent Charge',
                'first_date'=>$first_day_month,
                'tr_date' => $tr_date,
                'first_range'=>$first_day_count,
                'second_range'=>$second_day_count,
                'first_amount'=>($principal_x*$first_day_count*$err)/$factor,
                'second_amt'=>($principal*$second_day_count*$err)/$factor,
                'installment_amt'=> $rent
            ]);
            /////////////STATEMENT
            ///

            $connection->table('statement')->insert([
                'serial'=>$sl,
                'description'=>'Rent Charge',
                'tr_date' => $tr_date,
                'rent_dr'=> $rent
            ]);

            // GL Trans


            $connection->table('gl_trans')->insert([
                'serial'=>$sl,
                'description'=>'Rent Charge',
                'tr_date' => $tr_date,
                'gl_head'=>'HPSM Investment Income',
                'dr_amt'=> 0,
                'cr_amt'=>$rent
            ]);

            $connection->table('gl_trans')->insert([
                'serial'=>$sl,
                'description'=>'Rent Charge',
                'tr_date' => $tr_date,
                'gl_head'=>'HPSM Investment Account',
                'dr_amt'=> $rent,
                'cr_amt'=>0
            ]);


            /////////////FOR RECOVERY




            $sl ++;

            $final = getNextDay(addMonths($g_date,1));
            $outstanding = $outstanding - $installment + $rent;

            $connection->table('installment')->insert([
                'serial'=>$sl,
                'description'=>'Recovery Installment',
                'tr_date' => $final,
                'formula'=>'=F11',
                'installment_amt'=> $installment,
                'balance'=>$outstanding
            ]);


            $connection->table('statement')->insert([
                'serial'=>$sl,
                'description'=>'Principal Recovery',
                'tr_date' => $final,
                'principal_cr'=> $installment - $profit_size - $rent
            ]);


            $connection->table('statement')->insert([
                'serial'=>$sl,
                'description'=>'Profit Recovery',
                'tr_date' => $final,
                'profit_cr'=> $profit_size
            ]);


            $connection->table('statement')->insert([
                'serial'=>$sl,
                'description'=>'Rent Recovery',
                'tr_date' => $final,
                'rent_cr'=> $rent
            ]);


            $connection->table('gl_trans')->insert([
                'serial'=>$sl,
                'description'=>'Installment Recovery',
                'tr_date' => $final,
                'gl_head'=>'HPSM Investment Account',
                'dr_amt'=> 0,
                'cr_amt'=>$installment
            ]);

            $connection->table('gl_trans')->insert([
                'serial'=>$sl,
                'description'=>'Installment Recovery',
                'tr_date' => $final,
                'gl_head'=>'Cash/DD/TT/PO',
                'dr_amt'=> $installment,
                'cr_amt'=>0
            ]);


            $connection->table('gl_trans')->insert([
                'serial'=>$sl,
                'description'=>'Installment Recovery',
                'tr_date' => $final,
                'gl_head'=>'HPSM Investment Income',
                'dr_amt'=> 0,
                'cr_amt'=>$profit_size
            ]);

            $connection->table('gl_trans')->insert([
                'serial'=>$sl,
                'description'=>'Installment Recovery',
                'tr_date' => $final,
                'gl_head'=>'Unearned Income',
                'dr_amt'=> $profit_size,
                'cr_amt'=>0
            ]);


            $sl ++;

            $g_date = $final;

            $principal_x = $principal;
            $principal = $principal - $installment + $rent + $profit_size;
            $first_day_count = dateDifference($final, date("Y-m-1", strtotime($final)));
            $second_day_count = dateDifference(date("Y-m-t", strtotime($g_date)),$final) + 1;
        }

        return $sl;
    }


    public function hpsmTwo()
    {
        $start_date = '2021-05-13';
        $end_date = '2026-05-12';
        $g_date = '2021-05-12';
        $principal = 2500000;
        $cash_sec = 10000;
        $principal_x = $principal;
        $first_day_count = 0;
        $err = 9;
        $installment = 55646;
        $profit_size = 3750;
        $last_day_month = date("Y-m-t", strtotime($g_date));
        $first_day_month = date("Y-m-1", strtotime($g_date));
        $second_day_count = dateDifference($last_day_month,$g_date);
        $factor = 36500;
        $profit = round((($principal*365*$err)/$factor),0);
        $outstanding = $principal + $profit;


//        dd(dateDifference($last_day_month,$g_date));

//        $report=[];

//        $report['tr_date'] = date("Y-m-t", strtotime($g_date));

//        dd($report);
        $connection = DB::connection('t24test');

        $connection->statement('TRUNCATE TABLE installment;');
        $connection->statement('TRUNCATE TABLE statement;');
        $connection->statement('TRUNCATE TABLE gl_trans;');


        $connection->table('installment')->insert([
            'serial'=>2,
            'description'=>'Cash Security Recovered',
            'tr_date' => $start_date,
            'installment_amt'=> $cash_sec
        ]);

        $connection->table('installment')->insert([
            'serial'=>3,
            'description'=>'Disbursement',
            'tr_date' => $start_date,
            'installment_amt'=> $principal + $cash_sec
        ]);

        $connection->table('installment')->insert([
            'serial'=>4,
            'principal' => $principal,
            'description'=>'Gestation Profit Charge',
            'tr_date' => $g_date,
            'installment_amt'=> $profit
        ]);


        $connection->table('statement')->insert([
            'serial'=>2,
            'description'=>'Cash Security Recovered',
            'tr_date' => $start_date,
            'cash_security_cr'=> $cash_sec
        ]);

        $connection->table('statement')->insert([
            'serial'=>3,
            'description'=>'Disbursement',
            'tr_date' => $start_date,
            'principal_dr'=> $principal
        ]);

        $connection->table('statement')->insert([
            'serial'=>3,
            'description'=>'Cash Security',
            'tr_date' => $start_date,
            'cash_security_dr'=> $cash_sec
        ]);

        $connection->table('statement')->insert([
            'serial'=>4,
            'description'=>'Gestation Profit',
            'tr_date' => $start_date,
            'profit_dr'=> $profit
        ]);

        $connection->table('gl_trans')->insert([
            'serial'=>2,
            'description'=>'Cash Security Recovered',
            'tr_date' => $start_date,
            'gl_head'=>'Cash/Customer A/C/DD/TT/PO',
            'dr_amt'=> $cash_sec,
            'cr_amt'=>0
        ]);

        $connection->table('gl_trans')->insert([
            'serial'=>2,
            'description'=>'Cash Security Recovered',
            'tr_date' => $start_date,
            'gl_head'=>'HPSM Investment Account',
            'dr_amt'=> 0,
            'cr_amt'=>$cash_sec
        ]);


        $connection->table('gl_trans')->insert([
            'serial'=>3,
            'description'=>'Disbursement',
            'tr_date' => $start_date,
            'gl_head'=>'DD/TT/PO/CASH',
            'dr_amt'=> 0,
            'cr_amt'=>$principal + $cash_sec
        ]);

        $connection->table('gl_trans')->insert([
            'serial'=>3,
            'description'=>'Disbursement',
            'tr_date' => $start_date,
            'gl_head'=>'HPSM Investment Account',
            'dr_amt'=> $principal + $cash_sec,
            'cr_amt'=>0
        ]);


        $connection->table('gl_trans')->insert([
            'serial'=>4,
            'description'=>'Gestation Profit',
            'tr_date' => $g_date,
            'gl_head'=>'Unearned Income',
            'dr_amt'=> 0,
            'cr_amt'=>$profit
        ]);

        $connection->table('gl_trans')->insert([
            'serial'=>4,
            'description'=>'Gestation Profit',
            'tr_date' => $g_date,
            'gl_head'=>'HPSM Investment Account',
            'dr_amt'=> $profit,
            'cr_amt'=>0
        ]);





//        dd('here');


        $sl = 5;
        $outstanding_rent = 0;

        for ($i=0; $i<60; $i++)
        {
            $tr_date = date("Y-m-t", strtotime($g_date));

            $rent = floor((($principal_x*$first_day_count*$err)/$factor) + (($principal*$second_day_count*$err)/$factor));

            $connection->table('installment')->insert([
                'serial'=>$sl,
                'principal_x' => $principal_x,
                'principal' => $principal,
                'description'=>'Rent Charge',
                'first_date'=>$first_day_month,
                'tr_date' => $tr_date,
                'first_range'=>$first_day_count,
                'second_range'=>$second_day_count,
                'first_amount'=>($principal_x*$first_day_count*$err)/$factor,
                'second_amt'=>($principal*$second_day_count*$err)/$factor,
                'installment_amt'=> $rent
            ]);
            /////////////STATEMENT
            ///

            $connection->table('statement')->insert([
                'serial'=>$sl,
                'description'=>'Rent Charge',
                'tr_date' => $tr_date,
                'rent_dr'=> $rent
            ]);

            // GL Trans


            $connection->table('gl_trans')->insert([
                'serial'=>$sl,
                'description'=>'Rent Charge',
                'tr_date' => $tr_date,
                'gl_head'=>'HPSM Investment Income',
                'dr_amt'=> 0,
                'cr_amt'=>$rent
            ]);

            $connection->table('gl_trans')->insert([
                'serial'=>$sl,
                'description'=>'Rent Charge',
                'tr_date' => $tr_date,
                'gl_head'=>'HPSM Investment Account',
                'dr_amt'=> $rent,
                'cr_amt'=>0
            ]);


            /////////////FOR RECOVERY







            $final = getNextDay(addMonths($g_date,1));
            $outstanding = $outstanding - $installment + $rent;

            $sl ++;

            if($sl==32)
            {
                $g_date = $final;
                $first_day_count = dateDifference($final, date("Y-m-1", strtotime($final)));
                $second_day_count = dateDifference(date("Y-m-t", strtotime($g_date)),$final) + 1;
                $outstanding_rent = $rent;
//                $g_date = $final;


            }else{

                if($sl==33)
                {
                    $installment = $installment *2;
                    $rent = $rent + $outstanding_rent;
                    $profit_size = $profit_size*2;


                }else{
                    $installment = 55646;
                    $profit_size = 3750;
                }


                $connection->table('installment')->insert([
                    'serial'=>$sl,
                    'description'=>'Recovery Installment',
                    'tr_date' => $final,
                    'formula'=>'=F11',
                    'installment_amt'=> $installment,
                    'balance'=>$outstanding
                ]);


                $connection->table('statement')->insert([
                    'serial'=>$sl,
                    'description'=>'Principal Recovery',
                    'tr_date' => $final,
                    'principal_cr'=> $installment - $profit_size - $rent
                ]);


                $connection->table('statement')->insert([
                    'serial'=>$sl,
                    'description'=>'Profit Recovery',
                    'tr_date' => $final,
                    'profit_cr'=> $profit_size
                ]);


                $connection->table('statement')->insert([
                    'serial'=>$sl,
                    'description'=>'Rent Recovery',
                    'tr_date' => $final,
                    'rent_cr'=> $rent
                ]);


                $connection->table('gl_trans')->insert([
                    'serial'=>$sl,
                    'description'=>'Installment Recovery',
                    'tr_date' => $final,
                    'gl_head'=>'HPSM Investment Account',
                    'dr_amt'=> 0,
                    'cr_amt'=>$installment
                ]);

                $connection->table('gl_trans')->insert([
                    'serial'=>$sl,
                    'description'=>'Installment Recovery',
                    'tr_date' => $final,
                    'gl_head'=>'Cash/DD/TT/PO',
                    'dr_amt'=> $installment,
                    'cr_amt'=>0
                ]);

                $connection->table('gl_trans')->insert([
                    'serial'=>$sl,
                    'description'=>'Installment Recovery',
                    'tr_date' => $final,
                    'gl_head'=>'HPSM Investment Income',
                    'dr_amt'=> 0,
                    'cr_amt'=>$profit_size
                ]);

                $connection->table('gl_trans')->insert([
                    'serial'=>$sl,
                    'description'=>'Installment Recovery',
                    'tr_date' => $final,
                    'gl_head'=>'Unearned Income',
                    'dr_amt'=> $profit_size,
                    'cr_amt'=>0
                ]);


                $sl ++;

                $g_date = $final;

                $principal_x = $principal;
                $principal = $principal - $installment + $rent + $profit_size;
                $first_day_month = date("Y-m-1", strtotime($g_date));
                $first_day_count = dateDifference($final, date("Y-m-1", strtotime($final)));
                $second_day_count = dateDifference(date("Y-m-t", strtotime($g_date)),$final) + 1;
            }
        }

        return $sl;
    }

}
