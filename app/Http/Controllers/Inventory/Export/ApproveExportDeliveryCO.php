<?php

namespace App\Http\Controllers\Inventory\Export;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Common\UserActivity;
use App\Models\Company\CompanyProperty;
use App\Models\Inventory\Movement\Delivery;
use App\Models\Inventory\Movement\ProductHistory;
use App\Models\Inventory\Movement\Sale;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ProductMO;
use App\Traits\CommonTrait;
use App\Traits\ProductTrait;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ApproveExportDeliveryCO extends Controller
{
    use CommonTrait,TransactionsTrait, ProductTrait;

    public function index(Request $request)
    {
        $this->menu_log($this->company_id,57030);

        $selections = Delivery::query()->where('company_id',$this->company_id)
            ->where('delivery_type','EX')
            ->where('status','CR')
            ->pluck('challan_no','id');

        if(isset($request['challan_id'])) {
            $delivery = Delivery::query()->where('company_id', $this->company_id)
                ->where('id', $request['challan_id'])
                ->with('serials')->with('items')
                ->first();

            return view('inventory.export.index.approve-export-delivery-index', compact('delivery'));
        }


        return view('inventory.export.index.approve-export-delivery-index',compact('selections'));
    }

    public function approve(Request $request)
    {

        switch ($request['action'])
        {
            case    'approve':

                DB::beginTransaction();

                try{

//                    $data = Delivery::query()->get();
                    Delivery::query()->where('challan_no',$request['challan_no'])
                        ->where('company_id',$this->company_id)
                        ->update(['status'=>'DL','approve_date'=>Carbon::now(),'approve_by'=>$this->user_id]);

                }catch (\Exception $e)
                {
                    DB::rollBack();
                    $error = $e->getMessage();
                    return response()->json(['error' => $error], 404);
                }

                DB::commit();

                break;

            case 'reject':
                DB::beginTransaction();

                try {
                    Delivery::query()->where('challan_no',$request['challan_no'])
                        ->where('company_id',$this->company_id)
                        ->update(['status','RJ','approve_date'=>Carbon::now(),'approve_by'=>$this->user_id]);

                }catch (\Exception $e)
                {
                    DB::rollBack();
                    $error = $e->getMessage();
                    return redirect()->action('Inventory\Export\ApproveExportDeliveryCO@index');
                }


        }
        return redirect()->action('Inventory\Export\ApproveExportDeliveryCO@index');
    }

    public function inputs($row, $period, $delivery, $desc2)
    {
        $input = [];

        $input['company_id'] = $this->company_id;
        $input['project_id'] = null;
        $input['cost_center_id'] = $delivery->delivery_type == 'SL' ? null : $delivery->relationship_id;
        $input['tr_code'] = 'DC';
        $input['fp_no'] = $period->fp_no;
        $input['trans_type_id'] = 10; //  Sales
        $input['period'] = Str::upper(Carbon::now()->format('Y-M'));
        $input['trans_id'] = Carbon::now()->format('Ymdhmis');
        $input['trans_group_id'] = Carbon::now()->format('Ymdhmis');
        $input['trans_date'] = Carbon::now();
        $input['voucher_no'] = $delivery->challan_no;
        $input['acc_no'] = $row['gl_head'];
        $input['ledger_code'] = Str::substr($input['acc_no'],0,3);
        $input['ref_no'] = $delivery->ref_no;
        $input['contra_acc'] = $delivery->relationship_id;
        $input['dr_amt'] = $row['debit_amt'];
        $input['cr_amt'] = $row['credit_amt'];
        $input['trans_amt'] = $row['debit_amt'] + $row['credit_amt'];
        $input['currency'] = get_currency($this->company_id);
        $input['fiscal_year'] = $period->fiscal_year;
        $input['trans_desc1'] = $delivery->delivery_type == 'SL' ? 'Sales Delivery' : 'Delivery for Consumption';;
        $input['trans_desc2'] = $desc2;
        $input['remote_desc'] = $delivery->relationship_id;
        $input['post_flag'] = false;
        $input['user_id'] = $this->user_id;

        return $input;
    }
}
