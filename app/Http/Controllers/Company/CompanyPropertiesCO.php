<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\TransCode;
use App\Models\Common\Country;
use App\Models\Company\Company;
use App\Models\Company\CompanyProperty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyPropertiesCO extends Controller
{
    public function index()
    {
        $basic = CompanyProperty::query()->where('COMPANY_ID',$this->company_id)->first();

//        dd($basic);

        $company = Company::query()->where('id',$this->company_id)->first();

        $currencies = Country::query()->pluck('country_code','country_name');

//        $country = geoip_country_name_by_name('www.matincottonmills.com');


//        dd($country);
        return view('company.company-basic-data-index',compact('company','basic','currencies'));
    }

    public function store(Request $request)
    {

//        DB::setDateFormat('Y-m-d');

//        $request['COMPANY_ID'] = $this->company_id;
//        $request['user_id'] = $this->user_id;
//        $request['INVENTORY'] = $request->has('hInventory') ? 1 : 0;
//        $request['PROJECT'] = $request->has('hProject') ? 1 : 0;
//        $request['AUTO_LEDGER'] = $request->has('hAuto_ledger') ? 1 : 0;
//        $request['FPSTART'] = Carbon::createFromFormat('d-m-Y',$request['fp_start'])->format('Y-m-d');
//        $request['CURRENCY'] = 'BDT';
//        $request['POSTED'] = 1;

//        dd($request);


        DB::beginTransaction();

        try {

            CompanyProperty::query()->updateOrCreate(
                ['COMPANY_ID' => $this->company_id],
                [
                    'INVENTORY' => $request->has('hInventory') ? 1 : 0,
                    'PROJECT' => $request->has('hProject') ? 1 : 0,
                    'AUTO_LEDGER' => $request->has('hAuto_ledger') ? 1 : 0,
                    'FPSTART' => Carbon::createFromFormat('d-m-Y',$request['fp_start'])->format('Y-m-d'),
                    'CURRENCY' => 'BDT',
                    'POSTED' => 1
                ]
            );

            if($request['posted'] == 1)
            {
                $yr = Carbon::now()->format('Y');

                TransCode::query()->updateOrCreate(
                    ['COMPANY_ID'=>$this->company_id,'TRANS_CODE'=>'PM'],
                    [
                    'COMPANY_ID'=>$this->company_id,
                    'TRANS_CODE'=>'PM',
                    'TRANS_NAME'=>'Payment',
                    'LAST_TRANS_ID'=>$yr.'10000001'
                    ]
                );

                TransCode::query()->updateOrCreate(
                    ['COMPANY_ID'=>$this->company_id,'TRANS_CODE'=>'RC'],
                    [
                        'COMPANY_ID'=>$this->company_id,
                        'TRANS_CODE'=>'RC',
                        'TRANS_NAME'=>'Receive',
                        'LAST_TRANS_ID'=>$yr.'20000001'
                    ]
                );

                TransCode::query()->updateOrCreate(
                    ['COMPANY_ID'=>$this->company_id,'TRANS_CODE'=>'JV'],
                    [
                        'COMPANY_ID'=>$this->company_id,
                        'TRANS_CODE'=>'JV',
                        'TRANS_NAME'=>'Journal',
                        'LAST_TRANS_ID'=>$yr.'30000001'
                    ]
                );

                TransCode::query()->updateOrCreate(
                    ['COMPANY_ID'=>$this->company_id,'TRANS_CODE'=>'RQ'],
                    [
                        'COMPANY_ID'=>$this->company_id,
                        'TRANS_CODE'=>'RQ',
                        'TRANS_NAME'=>'Requisition',
                        'LAST_TRANS_ID'=>$yr.'40000001'
                    ]
                );

                TransCode::query()->updateOrCreate(
                    ['COMPANY_ID'=>$this->company_id,'TRANS_CODE'=>'SL'],
                    [
                        'COMPANY_ID'=>$this->company_id,
                        'TRANS_CODE'=>'SL',
                        'TRANS_NAME'=>'Sales Invoice',
                        'LAST_TRANS_ID'=>$yr.'50000001'
                    ]
                );

                TransCode::query()->updateOrCreate(
                    ['COMPANY_ID'=>$this->company_id,'TRANS_CODE'=>'DC'],
                    [
                        'COMPANY_ID'=>$this->company_id,
                        'TRANS_CODE'=>'DC',
                        'TRANS_NAME'=>'Delivery Challan',
                        'LAST_TRANS_ID'=>$yr.'60000001'
                    ]
                );

                TransCode::query()->updateOrCreate(
                    ['COMPANY_ID'=>$this->company_id,'TRANS_CODE'=>'PR'],
                    [
                        'COMPANY_ID'=>$this->company_id,
                        'TRANS_CODE'=>'PR',
                        'TRANS_NAME'=>'Purchase Invoice',
                        'LAST_TRANS_ID'=>$yr.'70000001'
                    ]
                );

                TransCode::query()->updateOrCreate(
                    ['COMPANY_ID'=>$this->company_id,'TRANS_CODE'=>'IR'],
                    [
                        'COMPANY_ID'=>$this->company_id,
                        'TRANS_CODE'=>'IR',
                        'TRANS_NAME'=>'Item Receive',
                        'LAST_TRANS_ID'=>$yr.'80000001'
                    ]
                );
            }

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
//            $request->session()->flash('alert-danger', $error.'Not Saved');
            return redirect()->back()->with('error','Not Saved '.$error);
        }

        DB::commit();

        return redirect()->action('Company\CompanyPropertiesCO@index')->with('success','Company data updated');
    }
}
