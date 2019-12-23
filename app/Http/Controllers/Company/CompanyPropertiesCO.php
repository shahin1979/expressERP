<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Common\AppModule;
use App\Models\Company\CompanyModule;
use App\Models\Company\FiscalPeriod;
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
        $basic = CompanyProperty::query()->where('company_id',$this->company_id)->first();

//        dd($basic);

        $comp = Company::query()->where('id',$this->company_id)->first();

        $currencies = Country::query()->pluck('country_code','country_name');

        $modules = AppModule::query()->get();

        $comp_modules = CompanyModule::query()->where('company_id',$this->company_id)->get();



//        $country = geoip_country_name_by_name('www.matincottonmills.com');


//        dd($country);
        return view('company.company-basic-data-index',compact('comp','basic','currencies','modules','comp_modules'));
    }

    public function store(Request $request)
    {

//        dd($request->all());
//
//        foreach ($request['module_id'])
//        {
//
//        }

        DB::beginTransaction();

        $currency = 'BDT';

        try {

            CompanyProperty::query()->updateOrCreate(
                ['company_id' => $this->company_id],
                [
                    'inventory' => $request->has('hInventory') ? 1 : 0,
                    'project' => $request->has('hProject') ? 1 : 0,
                    'auto_ledger' => $request->has('hAuto_ledger') ? 1 : 0,
                    'fp_start' => Carbon::createFromFormat('d-m-Y',$request['fp_start'])->format('Y-m-d'),
                    'trans_min_date'=> Carbon::createFromFormat('d-m-Y',$request['fp_start'])->format('Y-m-d'),
                    'currency' => $currency,
                    'posted' => 1
                ]
            );

            // delete existing company modules data
            CompanyModule::query()->where('company_id', $this->company_id)->delete();

            //insert new modules for the company
            foreach ($request['module_id'] as $row)
            {
                CompanyModule::query()->updateOrCreate(['company_id' => $this->company_id,'module_id'=>$row],
                    [
                        'status'=>true
                    ]);
            }


            if($request->hasfile('company_logo'))
            {
                $file = $request->file('company_logo');
                $name = $this->company_id.'_logo.'.$file->getClientOriginalExtension();
                $file->move(public_path().'/company/', $name);

                CompanyProperty::query()->where('company_id',$this->company_id)->update(['company_logo'=>'company/'.$name]);
            }


        // ADD TRANSACTION TYPES AND RELATED VOUCHER NO

            if($request['posted'] != 1)
            {
                $yr = Carbon::now()->format('Y');

                TransCode::query()->updateOrCreate(
                    ['company_id'=>$this->company_id,'trans_code'=>'PM'],
                    [
                    'company_id'=>$this->company_id,
                    'trans_code'=>'PM',
                    'trans_name'=>'Payment',
                    'last_trans_id'=>$yr.'10000001'
                    ]
                );

                TransCode::query()->updateOrCreate(
                    ['company_id'=>$this->company_id,'trans_code'=>'RC'],
                    [
                        'company_id'=>$this->company_id,
                        'trans_code'=>'RC',
                        'trans_name'=>'Receive',
                        'last_trans_id'=>$yr.'20000001'
                    ]
                );

                TransCode::query()->updateOrCreate(
                    ['company_id'=>$this->company_id,'trans_code'=>'JV'],
                    [
                        'company_id'=>$this->company_id,
                        'trans_code'=>'JV',
                        'trans_name'=>'Journal',
                        'last_trans_id'=>$yr.'30000001'
                    ]
                );

                TransCode::query()->updateOrCreate(
                    ['company_id'=>$this->company_id,'trans_code'=>'RQ'],
                    [
                        'company_id'=>$this->company_id,
                        'trans_code'=>'RQ',
                        'trans_name'=>'Requisition',
                        'last_trans_id'=>$yr.'40000001'
                    ]
                );

                TransCode::query()->updateOrCreate(
                    ['company_id'=>$this->company_id,'trans_code'=>'SL'],
                    [
                        'company_id'=>$this->company_id,
                        'trans_code'=>'SL',
                        'trans_name'=>'Sales Invoice',
                        'last_trans_id'=>$yr.'50000001'
                    ]
                );

                TransCode::query()->updateOrCreate(
                    ['company_id'=>$this->company_id,'trans_code'=>'DC'],
                    [
                        'company_id'=>$this->company_id,
                        'trans_code'=>'DC',
                        'trans_name'=>'Delivery Challan',
                        'last_trans_id'=>$yr.'60000001'
                    ]
                );

                TransCode::query()->updateOrCreate(
                    ['company_id'=>$this->company_id,'trans_code'=>'PR'],
                    [
                        'company_id'=>$this->company_id,
                        'trans_code'=>'PR',
                        'trans_name'=>'Purchase Invoice',
                        'last_trans_id'=>$yr.'70000001'
                    ]
                );

                TransCode::query()->updateOrCreate(
                    ['company_id'=>$this->company_id,'trans_code'=>'IR'],
                    [
                        'company_id'=>$this->company_id,
                        'trans_code'=>'IR',
                        'trans_name'=>'Item Receive',
                        'last_trans_id'=>$yr.'80000001'
                    ]
                );



                //ADD INITIAL ACCOUNTS OF THE GENERAL LEDGER

                GeneralLedger::query()->updateOrCreate(
                    ['company_id'=>$this->company_id,'acc_no'=>'10112100'],
                    [
                        'ledger_code'=>'101',
                        'acc_name'=>'CASH ON HAND',
                        'acc_type'=>'A',
                        'type_code'=>12,
                        'acc_range'=>'10112999',
                        'is_group'=>true,
                        'currency'=>$currency,
                        'user_id'=>$this->user_id
                    ]
                );

                GeneralLedger::query()->updateOrCreate(
                    ['company_id'=>$this->company_id,'acc_no'=>'10112101'],
                    [
                        'ledger_code'=>'101',
                        'acc_name'=>'Cash In Hand',
                        'acc_type'=>'A',
                        'type_code'=>12,
                        'acc_range'=>'10112102',
                        'is_group'=>false,
                        'currency'=>$currency,
                        'user_id'=>$this->user_id
                    ]
                );

                GeneralLedger::query()->updateOrCreate(
                    ['company_id'=>$this->company_id,'acc_no'=>'10212100'],
                    [
                        'ledger_code'=>'102',
                        'acc_name'=>'CASH AT BANK',
                        'acc_type'=>'A',
                        'type_code'=>12,
                        'acc_range'=>'10212999',
                        'is_group'=>true,
                        'currency'=>$currency,
                        'user_id'=>$this->user_id
                    ]
                );

                GeneralLedger::query()->updateOrCreate(
                    ['company_id'=>$this->company_id,'acc_no'=>'30112100'],
                    [
                        'ledger_code'=>'301',
                        'acc_name'=>'SALES ACCOUNT',
                        'acc_type'=>'I',
                        'type_code'=>31,
                        'acc_range'=>'30112999',
                        'is_group'=>true,
                        'currency'=>$currency,
                        'user_id'=>$this->user_id
                    ]
                );

                GeneralLedger::query()->updateOrCreate(
                    ['company_id'=>$this->company_id,'acc_no'=>'40112100'],
                    [
                        'ledger_code'=>'401',
                        'acc_name'=>'PURCHASE ACCOUNT',
                        'acc_type'=>'E',
                        'type_code'=>41,
                        'acc_range'=>'40112999',
                        'is_group'=>true,
                        'currency'=>$currency,
                        'user_id'=>$this->user_id
                    ]
                );

                GeneralLedger::query()->updateOrCreate(
                    ['company_id'=>$this->company_id,'acc_no'=>'50112100'],
                    [
                        'ledger_code'=>'501',
                        'acc_name'=>'RETAINED EARNINGS A/C',
                        'acc_type'=>'C',
                        'type_code'=>51,
                        'acc_range'=>'50112999',
                        'is_group'=>true,
                        'currency'=>$currency,
                        'user_id'=>$this->user_id
                    ]
                );


                // Insert Fiscal period

                $start_date = Carbon::createFromFormat('d-m-Y',$request['fp_start'])->format('Y-m-d');

                $end_dt = Carbon::createFromFormat('d-m-Y',$request['fp_start'])->endOfMonth();

                $f_y1 = Carbon::createFromFormat('d-m-Y',$request['fp_start'])->format('Y');
                $f_y2 = Carbon::createFromFormat('d-m-Y',$request['fp_start'])->addMonth(11)->format('Y');
                $f_y = $f_y1.'-'.$f_y2;


                for ($m=1; $m <=12; $m++) {

                    $year = Carbon::parse($start_date)->format('Y');
                    $fpNo= $m;

                    $month_sl = Carbon::parse($start_date)->format('m');
                    $month = date('F', mktime(0,0,0,$month_sl, 1, date('Y')));
                    $status = true;


                    FiscalPeriod::create([
                        'company_id' => $this->company_id,
                        'fiscal_year' => $f_y,
                        'year' =>$year,
                        'fp_no' => $fpNo,
                        'month_serial' => $month_sl,
                        'month_name' => $month,
                        'start_date' => Carbon::parse($start_date)->format('Y-m-d'),
                        'end_date' => Carbon::parse($start_date)->format( 'Y-m-t' ),
                        'status' => $status,
                        'depreciation' => false
                    ]);

                    $start_date = Carbon::parse($start_date)->addMonth(1);

                }
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
