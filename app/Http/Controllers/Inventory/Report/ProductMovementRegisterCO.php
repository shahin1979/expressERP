<?php

namespace App\Http\Controllers\Inventory\Report;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Movement\ProductHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductMovementRegisterCO extends Controller
{
    public function index(Request $request)
    {

        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>58010,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        $param = [];

        if($request['report_date'])
        {
            $tr_date = Carbon::createFromFormat('d-m-Y',$request['report_date'])->format('Y-m-d');
            $report = ProductHistory::query()->where('company_id',$this->company_id)
                ->whereDate('tr_date',$tr_date)->get();

            $param['report_date'] = $request['report_date'];

            return view('inventory.report.product-movement-register',compact('report','param'));
        }

        return view('inventory.report.product-movement-register');
    }
}
