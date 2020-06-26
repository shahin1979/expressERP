<?php

namespace App\Http\Controllers\Accounts\Ledger;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use Illuminate\Http\Request;

class OpeningBalanceCO extends Controller
{
    public function index()
    {

        $ledgers = GeneralLedger::query()->where('company_id',$this->company_id)
            ->where('opn_dr','>',0)
            ->orWhere('opn_cr','>',0)->get();


        return view('accounts.ledger.opening-balance-index',compact('ledgers'));
    }

    public function store()
    {
        $data = GeneralLedger::query()->where('company_id',$this->company_id)
            ->where('opn_dr','>',0)
            ->orWhere('opn_cr','>',0)
            ->get();

        if($data->first()->opn_post==false)
        {
            foreach ($data as $row)
            {
                GeneralLedger::query()->where('id',$row->id)
                    ->update([
                        'start_dr'=>$row->opn_dr,
                        'start_cr'=>$row->opn_cr,
                        'curr_bal'=>$row->curr_bal + $row->opn_dr - $row->opn_cr,
                        'opn_post'=>true
                    ]);

                GeneralLedger::query()->where('ledger_code',$row->ledger_code)
                    ->where('is_group',true)
                    ->increment('start_dr',$row->opn_dr);

                GeneralLedger::query()->where('ledger_code',$row->ledger_code)
                    ->where('is_group',true)
                    ->increment('start_cr',$row->opn_cr);

                GeneralLedger::query()->where('ledger_code',$row->ledger_code)
                    ->where('is_group',true)
                    ->increment('curr_bal',$row->curr_bal + $row->opn_dr - $row->opn_cr);

                GeneralLedger::query()->where('ledger_code',$row->ledger_code)
                    ->where('is_group',true)
                    ->update(['opn_post'=>true]);

            }

        }
        return redirect()->action('Accounts\Ledger\OpeningBalanceCO@index');
    }
}
