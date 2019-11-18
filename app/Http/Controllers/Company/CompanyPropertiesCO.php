<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
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

        DB::beginTransaction();

        $currency = 'BDT';

        try {

            CompanyProperty::query()->updateOrCreate(
                ['COMPANY_ID' => $this->company_id],
                [
                    'INVENTORY' => $request->has('hInventory') ? 1 : 0,
                    'PROJECT' => $request->has('hProject') ? 1 : 0,
                    'AUTO_LEDGER' => $request->has('hAuto_ledger') ? 1 : 0,
                    'FPSTART' => Carbon::createFromFormat('d-m-Y',$request['fp_start'])->format('Y-m-d'),
                    'CURRENCY' => $currency,
                    'POSTED' => 1
                ]
            );

        // ADD TRANSACTION TYPES AND RELATED VOUCHER NO

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



                //ADD INITIAL ACCOUNTS OF THE GENERAL LEDGER

                GeneralLedger::query()->updateOrCreate(
                    ['COMPANY_ID'=>$this->company_id,'ACC_NO'=>'10112100'],
                    [
                        'LEDGER_CODE'=>'101',
                        'ACC_NAME'=>'CASH IN HAND',
                        'ACC_TYPE'=>'A',
                        'TYPE_CODE'=>12,
                        'ACC_RANGE'=>'10112999',
                        'IS_GROUP'=>true,
                        'CURRENCY'=>$currency,
                        'USER_ID'=>$this->user_id
                    ]
                );

                GeneralLedger::query()->updateOrCreate(
                    ['COMPANY_ID'=>$this->company_id,'ACC_NO'=>'10112101'],
                    [
                        'LEDGER_CODE'=>'101',
                        'ACC_NAME'=>'Cash In Hand',
                        'ACC_TYPE'=>'A',
                        'TYPE_CODE'=>12,
                        'ACC_RANGE'=>'10112101',
                        'IS_GROUP'=>false,
                        'CURRENCY'=>$currency,
                        'USER_ID'=>$this->user_id
                    ]
                );

                GeneralLedger::query()->updateOrCreate(
                    ['COMPANY_ID'=>$this->company_id,'ACC_NO'=>'10212100'],
                    [
                        'LEDGER_CODE'=>'102',
                        'ACC_NAME'=>'CASH AT BANK',
                        'ACC_TYPE'=>'A',
                        'TYPE_CODE'=>12,
                        'ACC_RANGE'=>'10212999',
                        'IS_GROUP'=>true,
                        'CURRENCY'=>$currency,
                        'USER_ID'=>$this->user_id
                    ]
                );

                GeneralLedger::query()->updateOrCreate(
                    ['COMPANY_ID'=>$this->company_id,'ACC_NO'=>'30112100'],
                    [
                        'LEDGER_CODE'=>'301',
                        'ACC_NAME'=>'SALES ACCOUNT',
                        'ACC_TYPE'=>'I',
                        'TYPE_CODE'=>31,
                        'ACC_RANGE'=>'30112999',
                        'IS_GROUP'=>true,
                        'CURRENCY'=>$currency,
                        'USER_ID'=>$this->user_id
                    ]
                );

                GeneralLedger::query()->updateOrCreate(
                    ['COMPANY_ID'=>$this->company_id,'ACC_NO'=>'40112100'],
                    [
                        'LEDGER_CODE'=>'401',
                        'ACC_NAME'=>'PURCHASE ACCOUNT',
                        'ACC_TYPE'=>'E',
                        'TYPE_CODE'=>41,
                        'ACC_RANGE'=>'40112999',
                        'IS_GROUP'=>true,
                        'CURRENCY'=>$currency,
                        'USER_ID'=>$this->user_id
                    ]
                );

                GeneralLedger::query()->updateOrCreate(
                    ['COMPANY_ID'=>$this->company_id,'ACC_NO'=>'50112100'],
                    [
                        'LEDGER_CODE'=>'501',
                        'ACC_NAME'=>'RETAINED EARNINGS',
                        'ACC_TYPE'=>'C',
                        'TYPE_CODE'=>51,
                        'ACC_RANGE'=>'50112999',
                        'IS_GROUP'=>true,
                        'CURRENCY'=>$currency,
                        'USER_ID'=>$this->user_id
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
