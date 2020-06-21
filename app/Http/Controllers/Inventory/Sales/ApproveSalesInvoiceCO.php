<?php

namespace App\Http\Controllers\Inventory\Sales;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Company\CompanyProperty;
use App\Models\Company\Relationship;
use App\Models\Company\TransCode;
use App\Models\Inventory\Movement\Delivery;
use App\Models\Inventory\Movement\ProductHistory;
use App\Models\Inventory\Movement\Sale;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ProductMO;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ApproveSalesInvoiceCO extends Controller
{
    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>55012,'user_id'=>$this->user_id
            ],['updated_at'=>Carbon::now()
        ]);

        $basic = CompanyProperty::query()->where('company_id',$this->company_id)->first();

        return view('inventory.sales.approve-sales-invoice-index',compact('basic'));
    }

    public function InvoiceApproveData()
    {
        $query = Sale::query()->where('company_id',$this->company_id)
            ->where('status','CR')->with('items')
            ->with('user')->with('customer')->select('sales.*');

        return Datatables::eloquent($query)
            ->addColumn('product', function (Sale $sales) {
                return $sales->items->map(function($items) {
                    return $items->item->name;
                })->implode('<br>');
            })

            ->addColumn('quantity', function (Sale $sales) {
                return $sales->items->map(function($items) {
                    return $items->quantity;
                })->implode('<br>');
            })

            ->addColumn('unit_price', function (Sale $sales) {
                return $sales->items->map(function($items) {
                    return $items->unit_price;
                })->implode('<br>');
            })
            ->addColumn('action', function ($sales) {

                $type = $sales->req_type == 'CS' ? 'CASH' : 'CREDIT';

                return '

                    <button  data-remote="edit/' . $sales->id . '"
                        data-invoice="' . $sales->invoice_no . '"
                        data-date="' . $sales->invoice_date . '"
                        data-customer="' . $sales->customer->name . '"
                        data-amount="' . number_format($sales->invoice_amt,2) . '"
                        data-type="' . $type . '"
                        id="view-invoice" type="button" class="btn btn-view btn-xs btn-primary pull-left">Details</button>
                    ';
            })
            ->rawColumns(['product','quantity','unit_price','action'])
            ->make(true);
    }

    public function approve(Request $request, $id)
    {
        DB::beginTransaction();

        try{

            if($request->has('action'))
            {
                switch ($request['action'])
                {
                    case 'approve':

                        $basic = CompanyProperty::query()->where('company_id',$this->company_id)->first();
                        $delivery = $basic->auto_delivery == true ? 1 : 0;

                        Sale::query()->where('company_id',$this->company_id)
                            ->where('invoice_no',$id)->update([
                                'status'=>'AP',
                                'authorized_by'=>$this->user_id,
                                'authorized_date'=>Carbon::now(),
                                'direct_delivery'=>$delivery,
                                'delivery_status'=>$delivery]);

                        $period = $this->get_fiscal_data_from_current_date($this->company_id);
                        $invoice = Sale::query()->where('company_id',$this->company_id)
                            ->where('invoice_no',$id)->with('items')->first();


                        if($basic->auto_delivery == true)
                        {
                            $tr_code =  TransCode::query()->where('company_id',$this->company_id)
                                ->where('trans_code','DC')
                                ->where('fiscal_year',$period->fiscal_year)
                                ->lockForUpdate()->first();

                            $challan_no = $tr_code->last_trans_id;

                            $data['company_id'] = $this->company_id;
                            $data['challan_no'] = $challan_no;
                            $data['ref_no'] = $id;
                            $data['relationship_id'] = $invoice->customer_id;
                            $data['delivery_type'] = 'SL';
                            $data['delivery_date'] = Carbon::now();
                            $data['user_id'] = $this->user_id;
                            $data['status'] = 'AP'; //Created

                            $inserted = Delivery::query()->create($data); //Insert Data Into Deliveries Table

                            foreach ($invoice->items as $item)
                            {
                                $history['company_id'] = $this->company_id;
                                $history['ref_no'] = $challan_no;
                                $history['ref_id'] = $inserted->id;
                                $history['tr_date'] = $inserted->delivery_date;
                                $history['ref_type'] = 'D';
                                $history['contra_ref'] = $invoice->invoice_no;
                                $history['product_id'] = $item->product_id;
                                $history['quantity_in'] = 0;
                                $history['quantity_out'] = $item->quantity;
                                $history['unit_price'] = $item->unit_price;
                                $history['total_price'] = $item->quantity * $item->unit_price;
                                $history['relationship_id'] = $invoice->customer_id;

                                ProductHistory::query()->create($history);
                                ProductMO::query()->where('id',$item['product_id'])->increment('sell_qty',$item['quantity']);
                                TransProduct::query()->where('id',$item->id)->update(['delivered'=>$item['quantity']]);
                            }

                            TransCode::query()->where('company_id',$this->company_id)
                                ->where('trans_code','DC')
                                ->where('fiscal_year',$period->fiscal_year)
                                ->increment('last_trans_id');
                        }

                        // Accounts Voucher

                        $tr_code =  TransCode::query()->where('company_id',$this->company_id)
                            ->where('trans_code','SL')
                            ->where('fiscal_year',$period->fiscal_year)
                            ->lockForUpdate()->first();

                        $input = [];

                        $customer = Relationship::query()->where('id',$invoice->customer_id)->first();

                        //Debit Transaction

                        $input['company_id'] = $this->company_id;
                        $input['project_id'] = null;
                        $input['tr_code'] = 'SL';
                        $input['fp_no'] = $period->fp_no;
                        $input['trans_type_id'] = 8; //  Sales
                        $input['period'] = Str::upper(Carbon::now()->format('Y-M'));
                        $input['trans_id'] = Carbon::now()->format('Ymdhmis');
                        $input['trans_group_id'] = Carbon::now()->format('Ymdhmis');
                        $input['trans_date'] = Carbon::now();
                        $input['voucher_no'] = $tr_code->last_trans_id;
                        $input['acc_no'] = $customer->ledger_acc_no;
                        $input['ledger_code'] = Str::substr($customer->ledger_acc_no,0,3);
                        $input['ref_no'] = $invoice->invoice_no;
                        $input['contra_acc'] = $basic->auto_delivery == true ? $basic->default_sales : $basic->advance_sales;
                        $input['dr_amt'] = $invoice->due_amt;
                        $input['cr_amt'] = 0;
                        $input['trans_amt'] = $invoice->due_amt;
                        $input['currency'] = get_currency($this->company_id);
                        $input['fiscal_year'] = $period->fiscal_year;
                        $input['trans_desc1'] = 'Local Sales';
                        $input['trans_desc2'] = $basic->auto_delivery == true ? $challan_no : $invoice->invoice_no;
                        $input['remote_desc'] = $customer->id;
                        $input['post_flag'] = false;
                        $input['user_id'] = $this->user_id;

                        $this->transaction_entry($input);

                        // Credit Transaction
                        $input['acc_no'] = $basic->auto_delivery == true ? $basic->default_sales : $basic->advance_sales;
                        $input['ledger_code'] = Str::substr($input['acc_no'],0,3);
                        $input['dr_amt'] = 0;
                        $input['cr_amt'] = $invoice->due_amt;
                        $this->transaction_entry($input);

                        break;

                    case 'reject':

                        $basic = CompanyProperty::query()->where('company_id',$this->company_id)->first();

                        $delivery = $basic->auto_delivery == true ? 1 : 0;

                        Sale::query()->where('company_id',$this->company_id)
                            ->where('invoice_no',$id)->update([
                                'status'=>'RJ',
                                'authorized_by'=>$this->user_id,
                                'authorized_date'=>Carbon::now(),
                                'direct_delivery'=>$delivery,
                                'delivery_status'=>$delivery]);

                        break;
                }
            }

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Invoice Approved'],200);
    }
}
