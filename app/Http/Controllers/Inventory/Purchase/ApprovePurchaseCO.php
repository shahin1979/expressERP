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
use App\Models\Inventory\Product\ItemTax;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            ->where('status','CR')
            ->with(['items'=>function($q){
                $q->where('company_id',$this->company_id);
            }])
            ->with('user')
            ->select('purchases.*');

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
                    return isset($items->supplier->name) ? $items->supplier->name : 'Cash Purchase';
                })->implode('<br>');
            })

            ->addColumn('action', function (Purchase $purchase) {


                return '<div class="btn-unit btn-group-sm" role="group" aria-label="Action Button">
                    <button  data-remote="approve/' . $purchase->id . '" type="button" class="btn btn-sm btn-approve btn-primary"><i>Approve</i></button>
                    <button data-remote="reject/' . $purchase->id . '" type="button" class="btn btn-sm btn-delete btn-danger"><i>Reject</i></button>
                    </div>
                    ';
            })
            ->rawColumns(['product','quantity','price','supplier','action'])
            ->make(true);
    }

    public function approve($id)
    {

        $period = $this->get_fiscal_data_from_current_date($this->company_id);
        $company_properties = CompanyProperty::query()->where('company_id',$this->company_id)->first();
        $invoice = Purchase::query()->where('company_id',$this->company_id)
            ->where('id',$id)->first();
//        dd($invoice);

        $data = TransProduct::query()->where('ref_id',$id)->where('ref_type','P')->get();

        $trans = $data->groupBy('relationship_id')->map(function ($row)  {
            $grouped = Collect();
            $row->amount = $row->sum('total_price');
            $row->tax = $row->sum('tax_total');
            $grouped->push($row);
            return $grouped;
        });

        $taxes = $data->groupBy('tax_id')->map(function ($row)  {
            $grouped = Collect();
            $row->tax = $row->sum('tax_total');
            $grouped->push($row);
            return $grouped;
        });


        $suppliers = Relationship::query()->where('company_id',$this->company_id)
            ->where('relation_type','LS')->get();

        // If Accounting Module Then

        if(CompanyModule::query()->where('company_id',$this->company_id)->where('module_id',4)->exists())
        {

            $input = [];
            $amount = 0;

            DB::beginTransaction();

            try {
                foreach ($trans as $s_id => $voucher) {
                    foreach ($voucher as $row) {

                        $supplier = $suppliers->where('id', $s_id)->first();

                        $input['company_id'] = $this->company_id;
                        $input['project_id'] = null;
                        $input['tr_code'] = 'PR';
                        $input['fp_no'] = $period->fp_no;
                        $input['trans_type_id'] = 9; //  Purchase
                        $input['period'] = Str::upper(Carbon::now()->format('Y-M'));
                        $input['trans_id'] = Carbon::now()->format('Ymdhmis');
                        $input['trans_group_id'] = Carbon::now()->format('Ymdhmis');
                        $input['trans_date'] = Carbon::now();
                        $input['voucher_no'] = $invoice->ref_no;
//                        $input['acc_no'] = $supplier->count() ? $supplier->ledger_acc_no : $company_properties->default_cash;
                        $input['acc_no'] = is_null($supplier) ? $company_properties->default_cash : $supplier->ledger_acc_no;
                        $input['ledger_code'] = Str::substr($input['acc_no'], 0, 3);
                        $input['contra_acc'] = $company_properties->default_purchase;
                        $input['dr_amt'] = 0;
                        $input['cr_amt'] = $row->amount;
                        $input['trans_amt'] = $row->amount;
                        $input['currency'] = get_currency($this->company_id);
                        $input['fiscal_year'] = $period->fiscal_year;
                        $input['trans_desc1'] = 'Purchase Inventory Items';
                        $input['trans_desc2'] = $invoice->contra_ref;
                        $input['post_flag'] = false;
                        $input['user_id'] = $this->user_id;

                        $output = $this->transaction_entry($input);
                        $amount = $amount + $row->amount - $row->tax;
                    }
                }


                //TAX Transactions

                foreach ($taxes as $t_id=>$tax)
                {
                    foreach ($tax as $row)
                    {
                        $acc_no = ItemTax::query()->where('id',$t_id)->first();

                        $input['acc_no'] = is_null($acc_no) ? $company_properties->default_purchase_tax : $acc_no->acc_no;
                        $input['ledger_code'] = Str::substr($input['acc_no'], 0, 3);
                        $input['dr_amt'] = $row->tax;
                        $input['cr_amt'] = 0;
                        $input['trans_amt'] = $row->tax;
                        $input['trans_desc1'] = 'Tax Against Purchase';

                        $output =  $this->transaction_entry($input);
                    }
                }

                // Debit Transaction

                $input['acc_no'] = $company_properties->default_purchase;
                $input['ledger_code'] = Str::substr($company_properties->default_purchase, 0, 3);
                $input['dr_amt'] = $amount;
                $input['cr_amt'] = 0;
                $input['trans_amt'] = $amount;
                $input['trans_desc1'] = 'Purchase Inventory Items';
                $output =  $this->transaction_entry($input);

                // Update purchase invoice as approved

                Purchase::query()->where('id',$id)->update(['authorized_by'=>$this->user_id,'status'=>'AP']);

            }catch (\Exception $e)
            {
                DB::rollBack();
                $error = $e->getMessage();
                return response()->json(['error'=>'Purchase failed to approve : Error : '.$error],404);
            }

            DB::commit();
        }

        // End of Accounting Trans

        return response()->json(['success'=>'Purchase Approved'.$id],200);
    }
}
