<?php

namespace App\Http\Controllers\Inventory\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Company\CompanyModule;
use App\Models\Company\CompanyProperty;
use App\Models\Company\Relationship;
use App\Models\Company\TransCode;
use App\Models\Inventory\Movement\Purchase;
use App\Models\Inventory\Movement\TransProduct;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ApprovePurchaseCO extends Controller
{
    use TransactionsTrait;

    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>53025,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

//        dd($this->company_modules->where('module_id',4));

//        dd($this->company_modules);

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

            ->addColumn('price', function (Purchase $purchase) {
                return $purchase->items->map(function($items) {
                    return $items->unit_price;
                })->implode('<br>');
            })

            ->addColumn('supplier', function (Purchase $purchase) {
                return $purchase->items->map(function($items) {
                    return isset($items->supplier->name) ? $items->supplier->name : 'None';
                })->implode('<br>');
            })

            ->addColumn('action', function (Purchase $purchase) {


                return '
                    <button  data-remote="approve/' . $purchase->id . '" type="button" class="btn btn-approve btn-xs btn-info"></i> Approve</button><br/>
                    <button data-remote="reject/' . $purchase->id . '" type="button" class="btn btn-sm btn-delete btn-danger pull-right">Reject  ?</button>
                    ';
            })
            ->rawColumns(['product','quantity','price','supplier','action'])
            ->make(true);
    }

    public function approve($id)
    {

        $period = $this->get_fiscal_data_from_current_date($this->company_id);
        $company_properties = CompanyProperty::query()->where('company_id',$this->company_id)->first();

        $p_data = TransProduct::query()->where('ref_id',$id)->where('ref_type','P')->get();

        // If Accounting Module Then

        if(CompanyModule::query()->where('company_id',$this->company_id)->where('module_id',4)->exists())

        {

            $input = [];
            $amount = 0;

            foreach ($p_data as $row)
            {

                $supplier = Relationship::query()->where('id',$row->relationship_id)->first();

                $input['company_id'] = $this->company_id;
                $input['project_id'] = null;
                $input['tr_code'] = 'PR';
                $input['fp_no'] = $period->fp_no;
                $input['trans_type_id'] = 9; //  Purchase
                $input['period'] = Carbon::now()->format('Y-M');
                $input['trans_id'] = Carbon::now()->format('Ymdhmis');
                $input['trans_group_id'] = Carbon::now()->format('Ymdhmis');
                $input['trans_date'] = $period->end_date;
                $input['voucher_no'] = $row->ref_no;
                $input['acc_no'] = $supplier->ledger_acc_no;
                $input['ledger_code'] = Str::substr($supplier->ledger_acc_no,0,3);
                $input['contra_acc'] = $company_properties->default_purchase;
                $input['dr_amt'] = $row->quantity * $row->unit_price;
                $input['cr_amt'] = 0;
                $input['trans_amt'] = $row->quantity * $row->unit_price;
                $input['currency'] = get_currency($this->company_id);
                $input['fiscal_year'] = $period->fiscal_year;
                $input['trans_desc1'] = 'Local Purchase ';
                $input['trans_desc2'] = $row->ref_id;
                $input['post_flag'] = false;
                $input['user_id'] = $this->user_id;
//                $input['relationship_id'] = $supplier->id;

                $this->transaction_entry($input);

                $amount = $amount + $input['dr_amt'];
            }

            $input['dr_amt'] = 0;
            $input['cr_amt'] = $amount;
            $this->transaction_entry($input);
        }

        // End of Accounting Trans

        return response()->json(['success'=>'Requisition Approved'.$id],200);
    }
}
