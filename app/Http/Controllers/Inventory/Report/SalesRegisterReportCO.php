<?php

namespace App\Http\Controllers\Inventory\Report;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Movement\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalesRegisterReportCO extends Controller
{
    public function index(Request $request)
    {

        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>59020,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        if($request['date_from'])
        {
            $from_date = Carbon::createFromFormat('d-m-Y',$request['date_from'])->format('Y-m-d');
            $to_date = Carbon::createFromFormat('d-m-Y',$request['date_to'])->format('Y-m-d');

            $invoice = Sale::query()->where('company_id',$this->company_id)
                ->whereBetween('invoice_date',[$from_date,$to_date])
                ->with(['items'=>function($q){
                    $q->where('company_id',$this->company_id)
                        ->where('ref_type','S');
                }])
                ->with('user')->get();

//            dd($invoice);

            return view('inventory.report.sales-register-report-index',compact('invoice'));


        }
        return view('inventory.report.sales-register-report-index');
    }
}
