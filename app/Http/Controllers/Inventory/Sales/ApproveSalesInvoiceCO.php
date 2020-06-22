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
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use function MongoDB\BSON\toJSON;

class ApproveSalesInvoiceCO extends Controller
{
    use TransactionsTrait;

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

                    <button  data-remote="view/' . $sales->id . '"
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

    public function ajax_call($id)
    {
        $invoice= Sale::query()->where('id',$id)
            ->with(['items'=>function($q){
                $q->where('company_id',$this->company_id);
            }])
            ->first();
        $company = CompanyProperty::query()->where('company_id',$this->company_id)->first();
        $accounts = GeneralLedger::query()->where('company_id',$this->company_id)->get();
        $sales_acc = $company->auto_delivery == true ? $company->default_sales : $company->advance_sales;


        $sales = TransProduct::query()->where('company_id',$this->company_id)
            ->where('ref_id',$id)->where('ref_type','S')
            ->with('item')->with('invoice')->get();

        $transactions = collect([
            [
                'gl_head' => $invoice->customer->ledger_acc_no,
                'acc_name'=>$invoice->customer->ledger_acc_no.' : '.$accounts->where('acc_no',$invoice->customer->ledger_acc_no)->first()->acc_name,
                'debit_amt' => $sales->sum('total_price'),
                'credit_amt' => 0
            ],
            [
                'gl_head' => $sales_acc,
                'acc_name'=> $sales_acc.' : '.$accounts->where('acc_no',$sales_acc)->first()->acc_name,
                'debit_amt' => 0,
                'credit_amt' => $sales->sum('item_total')
            ],
        ]);

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
                $line = [];
                $line['gl_head'] = ItemTax::query()->where('id',$head)->first()->acc_no;
                $line['acc_name'] = $line['gl_head'].' : '.$accounts->where('acc_no',$line['gl_head'])->first()->acc_name;
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
                $input['amount'] = $amount;
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
            $line['acc_name'] = $line['gl_head'].' : '.$accounts->where('acc_no',$line['gl_head'])->first()->acc_name;
            $line['debit_amt'] = 0;
            $line['credit_amt'] = $id->tr_amount;
            $deliveries->push($line);
        }

        foreach ($temp2 as $head=>$id)
        {
            $line = [];
            $line['gl_head'] = $head;
            $line['acc_name'] = $line['gl_head'].' : '.$accounts->where('acc_no',$line['gl_head'])->first()->acc_name;
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
                switch ($request['action'])
                {
                    case 'approve':

                        $company = CompanyProperty::query()->where('company_id',$this->company_id)->first();
                        $delivery = $company->auto_delivery == true ? 1 : 0;
                        $accounts = GeneralLedger::query()->where('company_id',$this->company_id)->get();
                        $sales_acc = $company->auto_delivery == true ? $company->default_sales : $company->advance_sales;

                        $period = $this->get_fiscal_data_from_current_date($this->company_id);
                        $invoice = Sale::query()->where('company_id',$this->company_id)
                            ->where('invoice_no',$id)->with('items')->first();

                        $customer = Relationship::query()->where('id',$invoice->customer_id)->first();

                        Sale::query()->where('company_id',$this->company_id)
                            ->where('invoice_no',$id)->update([
                                'status'=>'AP',
                                'authorized_by'=>$this->user_id,
                                'authorized_date'=>Carbon::now(),
                                'direct_delivery'=>$delivery,
                                'delivery_status'=>$delivery]);

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
                                ProductMO::query()->where('id',$item['product_id'])->decrement('on_hand',$item['quantity']);
                                TransProduct::query()->where('id',$item->id)->update(['delivered'=>$item['quantity']]);
                            }

                            TransCode::query()->where('company_id',$this->company_id)
                                ->where('trans_code','DC')
                                ->where('fiscal_year',$period->fiscal_year)
                                ->increment('last_trans_id');
                        }


                        $t_data = $this->ajax_call($invoice->id);
                        $trans= json_decode($t_data,true);

//                        dd($trans);

                        foreach ($trans['transactions'] as $row)
                        {

                            $input = [];

                            $input['company_id'] = $this->company_id;
                            $input['project_id'] = null;
                            $input['tr_code'] = 'SL';
                            $input['fp_no'] = $period->fp_no;
                            $input['trans_type_id'] = 8; //  Sales
                            $input['period'] = Str::upper(Carbon::now()->format('Y-M'));
                            $input['trans_id'] = Carbon::now()->format('Ymdhmis');
                            $input['trans_group_id'] = Carbon::now()->format('Ymdhmis');
                            $input['trans_date'] = Carbon::now();
                            $input['voucher_no'] = $invoice->invoice_no;
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
                            $input['trans_desc2'] = $company->auto_delivery == true ? $challan_no : $invoice->invoice_no;
                            $input['remote_desc'] = $customer->id;
                            $input['post_flag'] = false;
                            $input['user_id'] = $this->user_id;

                            $this->transaction_entry($input);
                        }

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
