<?php

namespace App\Http\Controllers\Accounts\Ledger;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Common\UserActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RepTrialBalanceCO extends Controller
{
    public function index(Request $request)
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>42050,'user_id'=>$this->user_id
            ]);

        if(!empty($request['date_to']))
        {
            $ledgers = GeneralLedger::query()->where('company_id',$this->company_id)
                ->where('is_group',false)->get();

            $fp_no = get_fp_from_month_sl(Carbon::createFromFormat('d-m-Y',$request['date_to'])->format('m'),$this->company_id);

            $report = collect();

            foreach ($ledgers as $row)
            {
                $report['acc_no'] = $row->acc_no;
                $report['acc_name'] = $row->acc_name;

                $report['opening'] = 0;

                for($i = 0; $i< $fp_no; $i++)
                {
                    $var = str_pad($i,2,"0",STR_PAD_LEFT);
                    $field = 'dr_'.$var;

                    $report['opening_dr'] += $row->{$field};
                    $report['opening_cr'] += $row->{$field};

                }
            }




            dd ($fp_no);
        }

//        dd (Carbon::createFromFormat('d-m-Y',$request['date_to'])->format('Y-m-01'));

        return view('accounts.report.ledger.rep-trial-balance-index');
    }
}
