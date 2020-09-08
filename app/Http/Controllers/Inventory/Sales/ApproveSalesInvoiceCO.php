<?php

namespace App\Http\Controllers\Inventory\Sales;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Common\UserActivity;
use App\Models\Company\CompanyProperty;
use App\Models\Company\Relationship;
use App\Models\Company\TransCode;
use App\Models\Inventory\Movement\Delivery;
use App\Models\Inventory\Movement\ProductHistory;
use App\Models\Inventory\Movement\Sale;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ItemTax;
use App\Models\Inventory\Product\ProductMO;
use App\Traits\ProductTrait;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use function MongoDB\BSON\toJSON;

class ApproveSalesInvoiceCO extends Controller
{
    use TransactionsTrait, ProductTrait;

    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>56012,'user_id'=>$this->user_id
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

                    <button  data-remote="view/' . $sales->id . '"
                        data-invoice="' . $sales->invoice_no . '"
                        data-date="' . $sales->invoice_date . '"
                        data-customer="' . $sales->customer->name . '"
                        data-discount="' . $sales->discount_amt . '"
                        data-paid="' . $sales->paid_amt . '"
                        data-due="' . $sales->due_amt . '"
                        data-amount="' . number_format($sales->invoice_amt,2) . '"
                        data-type="' . $type . '"
                        id="view-invoice" type="button" class="btn btn-view btn-xs btn-primary pull-left">Details</button>
                    ';
            })
            ->rawColumns(['product','quantity','unit_price','action'])
            ->make(true);
    }

    public function ajax_call($id)
    {
        $invoice= Sale::query()->where('id',$id)
            ->with(['items'=>function($q){
                $q->where('company_id',$this->company_id);
            }])
            ->with('customer')
            ->first();
        $company = CompanyProperty::query()->where('company_id',$this->company_id)->first();
        $accounts = GeneralLedger::query()->where('company_id',$this->company_id)->get();
        $sales_acc = $company->auto_delivery == true ? $company->default_sales : $company->advance_sales;


        $sales = TransProduct::query()->where('company_id',$this->company_id)
            ->where('ref_id',$id)->where('ref_type','S')
            ->with('item')
            ->with(['invoice'=>function($q){
                $q->where('company_id',$this->company_id);
            }])
            ->get();

        $transactions = collect([
            [
                'gl_head' => $invoice->customer->ledger_acc_no,
                'acc_name'=>$invoice->customer->ledger_acc_no.' : '.$this->get_account_name($this->company_id, $invoice->customer->ledger_acc_no),
                'debit_amt' => $sales->sum('total_price') - $invoice->discount_amt - $invoice->paid_amt,
                'credit_amt' => 0
            ]
//            [
//                'gl_head' => $sales_acc,
//                'acc_name'=> $sales_acc.' : '.$accounts->where('acc_no',$sales_acc)->first()->acc_name,
//                'debit_amt' => 0,
//                'credit_amt' => $sales->sum('item_total')
//            ],
        ]);

        if($invoice->discount_amt > 0)
        {
            $discount = [];
            $discount['gl_head'] = $company->discount_sales;
            $discount['acc_name'] = $discount['gl_head'].' : '.$this->get_account_name($this->company_id, $company->discount_sales);
            $discount['debit_amt'] = $invoice->discount_amt;
            $discount['credit_amt'] = 0;
            $transactions->push($discount);
        }

        if($invoice->paid_amt > 0)
        {
            $paid = [];
            $paid['gl_head'] = $company->default_cash;
            $paid['acc_name'] = $paid['gl_head'].' : '.$this->get_account_name($this->company_id, $paid['gl_head']);
            $paid['debit_amt'] = $invoice->paid_amt;
            $paid['credit_amt'] = 0;
            $transactions->push($paid);
        }

        // Debit Transaction Sales / Tax

        if($sales->sum('item_total') > 0)
            {
                $credit = [];
                $credit['gl_head'] = $sales_acc;
                $credit['acc_name'] = $sales_acc.' : '.$this->get_account_name($this->company_id, $sales_acc);
                $credit['debit_amt'] = 0;
                $credit['credit_amt'] = $sales->sum('item_total');
                $transactions->push($credit);
            }

//        Transaction Code for Taxes

        $taxes = $sales->groupBy('tax_id')->map(function ($row)  {
            $grouped = Collect();
            $grouped->tax_amount = $row->sum('tax_total');
            $grouped->push($row);
            return $grouped;
        });

        foreach ($taxes as $head=>$row)
        {
            if($row->tax_amount > 0)
            {
                $tax_acc = ItemTax::query()->where('id',$head)->first();
                $tax_acc_no = Str::length($tax_acc->acc_no) > 1 ? $tax_acc->acc_no : $company->default_sales_tax;

                $line = [];
                $line['gl_head'] = $tax_acc_no;
                $line['acc_name'] = $line['gl_head'].' : '.$this->get_account_name($this->company_id, $line['gl_head']);
                $line['debit_amt'] = 0;
                $line['credit_amt'] = $row->tax_amount;
                $transactions->push($line);
            }
        }

        // Transaction for Delivery Items

        $temp = collect();

        if($company->auto_delivery)
        {
            foreach ($invoice->items as $product)
            {
                $acc_cr = $product->item->subcategory->acc_in_stock;
                $acc_dr = $product->item->subcategory->acc_out_stock;
                $amount = $product->item_total;
                $input = [];
                $input['acc_cr'] = $acc_cr;
                $input['acc_dr'] = $acc_dr;
                $input['amount'] = $this->get_average_price($product->product_id,$this->company_id) * $product->quantity;
                $temp->push($input);
            }
        }

        $temp1 = $temp->groupBy('acc_cr')->map(function ($row)  {
            $grouped = Collect();
            $grouped->tr_amount = $row->sum('amount');
            $grouped->push($row);
            return $grouped;
        });

        $temp2 = $temp->groupBy('acc_dr')->map(function ($row)  {
            $grouped = Collect();
            $grouped->tr_amount = $row->sum('amount');
            $grouped->push($row);
            return $grouped;
        });

        $deliveries = collect();

        foreach ($temp1 as $head=>$id)
        {
            $line = [];
            $line['gl_head'] = $head;
            $line['acc_name'] = $line['gl_head'].' : '.$this->get_account_name($this->company_id, $line['gl_head']);
            $line['debit_amt'] = 0;
            $line['credit_amt'] = $id->tr_amount;
            $deliveries->push($line);
        }

        foreach ($temp2 as $head=>$id)
        {
            $line = [];
            $line['gl_head'] = $head;
            $line['acc_name'] = $line['gl_head'].' : '.$this->get_account_name($this->company_id, $line['gl_head']);
            $line['debit_amt'] = $id->tr_amount;
            $line['credit_amt'] = 0;
            $deliveries->push($line);
        }


        $response = [
            'sales' => $sales,
            'transactions' => $transactions,
            'deliveries'=>$deliveries
        ];

        return json_encode($response, true) ;

//        return response()->json($response);

    }

    public function approve(Request $request, $id)
    {
        DB::beginTransaction();

        try{

            if($request->has('action'))
            {

                $company = CompanyProperty::query()->where('company_id',$this->company_id)->first();
                $delivery = $company->auto_delivery == true ? 1 : 0;
//                $accounts = GeneralLedger::query()->where('company_id',$this->company_id)->get();
                $sales_acc = $company->auto_delivery == true ? $company->default_sales : $company->advance_sales;

                $period = $this->get_fiscal_data_from_current_date($this->company_id);
                $invoice = Sale::query()->where('company_id',$this->company_id)
                    ->where('invoice_no',$id)
                    ->with(['items'=>function($q){
                        $q->where('company_id',$this->company_id)
                            ->where('ref_type','S');
                    }])
                    ->first();

                $customer = Relationship::query()->where('id',$invoice->customer_id)->first();


                switch ($request['action'])
                {
                    case 'approve':

                        if($company->auto_delivery == true)
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
                            $data['extra_field'] =
                            $data['status'] = 'DL'; //Created

                            $inserted = Delivery::query()->create($data); //Insert Data Into Deliveries Table

                            foreach ($invoice->items as $item)
                            {
                                $history['company_id'] = $this->company_id;
                                $history['ref_no'] = $challan_no;
                                $history['ref_id'] = $inserted->id;
                                $history['tr_date'] = $inserted->delivery_date;
                                $history['ref_type'] = 'S'; // Sales Delivery
                                $history['contra_ref'] = $invoice->invoice_no;
                                $history['product_id'] = $item->product_id;
                                $history['quantity_in'] = 0;
                                $history['quantity_out'] = $item->quantity;
                                $history['unit_price'] = $item->unit_price;
                                $history['total_price'] = $item->quantity * $item->unit_price;
                                $history['relationship_id'] = $invoice->customer_id;

                                ProductHistory::query()->create($history);
                                ProductMO::query()->where('id',$item['product_id'])->increment('sell_qty',$item['quantity']);
                                ProductMO::query()->where('id',$item['product_id'])->decrement('on_hand',$item['quantity']);
                                TransProduct::query()->where('id',$item->id)->update(['delivered'=>$item['quantity']]);
                            }

                            TransCode::query()->where('company_id',$this->company_id)
                                ->where('trans_code','DC')
                                ->where('fiscal_year',$period->fiscal_year)
                                ->increment('last_trans_id');
                        }

                        Sale::query()->where('company_id',$this->company_id)
                            ->where('invoice_no',$id)
                            ->update(
                                [
                                'status'=>'AP',
                                'authorized_by'=>$this->user_id,
                                'authorized_date'=>Carbon::now(),
                                'direct_delivery'=>$delivery
                                ]);



                        // Account Transaction for Sales Invoice
                        $t_data = $this->ajax_call($invoice->id);
                        $trans= json_decode($t_data,true);

                        $voucher_no = $invoice->invoice_no;
                        $tr_code = 'SL';
                        $desc2 = $invoice->customer->name;

                        foreach ($trans['transactions'] as $row)
                        {
                            $input = $this->inputs($row,$period,$invoice,$voucher_no,$tr_code,$customer,$sales_acc,$desc2);
                            $this->transaction_entry($input);
                        }

                        // Account transaction for Delivery items

                    if($company->auto_delivery == true)
                    {
                        $desc2 = 'Direct Delivery During Invoice Approve';
                        $voucher_no = $challan_no;
                        $tr_code = 'DC';

                        foreach ($trans['deliveries'] as $row)
                        {
                            $entries = $this->inputs($row,$period,$invoice,$voucher_no,$tr_code, $customer,$sales_acc,$desc2);
//                            $input =
                            $this->transaction_entry($entries);
                        }
                    }

                        break;

                    case 'reject':

                        Sale::query()->where('company_id',$this->company_id)
                            ->where('invoice_no',$id)->update([
                                'status'=>'RJ',
                                'authorized_by'=>$this->user_id,
                                'authorized_date'=>Carbon::now(),
                                ]);

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

    public function inputs($row, $period,$invoice, $voucher_no,$tr_code, $customer, $sales_acc,$desc2)
    {
        $input = [];

        $input['company_id'] = $this->company_id;
        $input['project_id'] = null;
        $input['tr_code'] = $tr_code;
        $input['fp_no'] = $period->fp_no;
        $input['trans_type_id'] = 10; //  Sales
        $input['period'] = Str::upper(Carbon::now()->format('Y-M'));
        $input['trans_id'] = Carbon::now()->format('Ymdhmis');
        $input['trans_group_id'] = Carbon::now()->format('Ymdhmis');
        $input['trans_date'] = Carbon::now();
        $input['voucher_no'] = $voucher_no;
        $input['acc_no'] = $row['gl_head'];
        $input['ledger_code'] = Str::substr($input['acc_no'],0,3);
        $input['ref_no'] = $invoice->invoice_no;
        $input['contra_acc'] = $sales_acc;
        $input['dr_amt'] = $row['debit_amt'];
        $input['cr_amt'] = $row['credit_amt'];
        $input['trans_amt'] = $row['debit_amt'] + $row['credit_amt'];
        $input['currency'] = get_currency($this->company_id);
        $input['fiscal_year'] = $period->fiscal_year;
        $input['trans_desc1'] = 'Local Sales';
        $input['trans_desc2'] = $desc2;
        $input['remote_desc'] = $customer->id;
        $input['post_flag'] = false;
        $input['user_id'] = $this->user_id;

        return $input;


    }
}
