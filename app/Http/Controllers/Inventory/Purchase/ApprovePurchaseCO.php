<?php

namespace App\Http\Controllers\Inventory\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Movement\Purchase;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ApprovePurchaseCO extends Controller
{
    use TransactionsTrait;

    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>56025,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        return view('inventory.purchase.approve-purchase-index');
    }

    public function getPurchaseData()
    {
        $query = Purchase::query()->where('company_id',$this->company_id)
            ->where('status','CR')->with('items')->with('user')->select('purchases.*');

        return DataTables::eloquent($query)
            ->addColumn('product', function (Purchase $purchase) {
                return $purchase->items->map(function($items) {
                    return $items->item->name;
                })->implode('<br>');
            })

            ->addColumn('quantity', function (Purchase $purchase) {
                return $purchase->items->map(function($items) {
                    return $items->quantity;
                })->implode('<br>');
            })

            ->addColumn('action', function (Purchase $purchase) {


                return '
                    <button  data-remote="approve/' . $purchase->id . '" type="button" class="btn btn-approve btn-xs btn-primary"></i> Approve</button>
                    <button data-remote="reject/' . $purchase->id . '" type="button" class="btn btn-xs btn-delete btn-danger pull-right">Reject</button>
                    ';
            })
            ->rawColumns(['product','quantity','action'])
            ->make(true);
    }

    public function approve()
    {
        $period = $this->get_fiscal_data_from_current_date($this->company_id);
        // If Accounting Module Then
        if($this->company_modules->where('module_id',4)->exist())
        {
            $input = [];

            $input['company_id'] = $this->company_id;
            $input['project_id'] = null;
            $input['tr_code'] = 'PR';
            $input['fp_no'] = $period->fp_no;
            $input['trans_type_id'] = 9; //  Depreciation
            $input['period'] = Carbon::now()->format('Y-M');
            $input['trans_id'] = Carbon::now()->format('Ymdhmis');
            $input['trans_group_id'] = Carbon::now()->format('Ymdhmis');
            $input['trans_date'] = $period->end_date;
//            $input['voucher_no'] = $pur_no;
//            $input['acc_no'] = $supplier->ledger_acc_no;
//            $input['ledger_code'] = Str::substr($supplier->ledger_acc_no,0,3);
//            $input['contra_acc'] = $row->contra_acc;
            $input['dr_amt'] = 0;
//            $input['cr_amt'] = $dep_amt;
//            $input['trans_amt'] = $dep_amt;
            $input['currency'] = get_currency($this->company_id);
            $input['fiscal_year'] = $period->fiscal_year;
            $input['trans_desc1'] = 'Depreciation For The Month ' . $period->month_name . ',' . $period->year;
            $input['trans_desc2'] = 'Depreciation';
            $input['post_flag'] = false;
            $input['user_id'] = $this->user_id;

            $this->transaction_entry($input);
//                        dd('Yes');
        }

        // End of Accounting Trans
    }
}
