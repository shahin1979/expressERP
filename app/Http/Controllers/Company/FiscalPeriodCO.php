<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\FiscalPeriod;
use App\Models\Company\TransCode;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class FiscalPeriodCO extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    use TransactionsTrait;

    public function index()
    {
        return view('company.fiscal-period-index');
    }

    public function getFiscalData()
    {
        $fiscal = FiscalPeriod::query()->where('status','A')->where('company_id',$this->company_id);
//        dd($fiscal);


        return DataTables::of($fiscal)
            ->addColumn('depreciation', function ($fiscal) {
                if($fiscal->depriciation == True)
                    return '<input type="checkbox" name="depreciation" value="'.$fiscal->fpNo.'" disabled="disabled" checked="checked">';
                else
                    return '<input type="checkbox" name="depreciation" value="'.$fiscal->fpNo.'" disabled="disabled">';
            })

            ->editColumn('start_date', function ($fiscal) {
                return Carbon::parse($fiscal->start_date)->format('d-m-Y');
            })
            ->editColumn('end_date', function ($fiscal) {
                return Carbon::parse($fiscal->end_date)->format('d-m-Y');
            })
            ->rawColumns(['depreciation','start_date','end_date'])
            ->make(true);
    }


    public function getTRCodeData()
    {
        $year = $this->get_fiscal_data_from_current_date($this->company_id);

        $codes = TransCode::query()->where('fiscal_year',$year->fiscal_year)->where('company_id',$this->company_id);

        return DataTables::of($codes)
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
