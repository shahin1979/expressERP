<?php

namespace App\Http\Controllers\Inventory\Sales;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Movement\Sale;
use App\Models\Inventory\Movement\TransProduct;
use App\Traits\CompanyTrait;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class EditSalesInvoiceCO extends Controller
{
    use TransactionsTrait, CompanyTrait;

    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>55010,'user_id'=>$this->user_id
            ],['updated_at'=>Carbon::now()
        ]);
        return view('inventory.sales.update-sales-invoice-index');
    }

    public function InvoiceData()
    {
        $query = Sale::query()->where('company_id',$this->company_id)
            ->where('status','CR')->with('items')->with('user')->with('customer')->select('sales.*');


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
                        id="edit-invoice" type="button" class="btn btn-edit btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</button>
                    <button data-remote="delete/' . $sales->id . '" type="button" class="btn btn-xs btn-delete btn-danger pull-right"  ><i class="glyphicon glyphicon-remove"></i>Delete</button>
                    ';
            })
            ->rawColumns(['product','quantity','unit_price','action'])
            ->make(true);
    }

    public function edit($id)
    {
        $sales = TransProduct::query()->where('company_id',$this->company_id)
            ->where('ref_id',$id)->where('ref_type','S')
            ->with('item')->with('invoice')->get();

        return response()->json($sales);
    }

    public function update(Request $request)
    {

//        dd($request->all());
        $invoice = Sale::query()->where('company_id',$this->company_id)
            ->where('invoice_no',$request['invoice'])->first();

        $items = TransProduct::query()->where('company_id',$this->company_id)
            ->where('ref_type','S')
            ->where('ref_no',$request['invoice'])->get();

        $invoice_amt = 0;

        DB::beginTransaction();

        try{

            foreach ($request['item'] as $item) {

                $tax = empty($item['tax_id']) ? 0 : $this->tax_amount($item['tax_id'],$item['price'],$item['quantity']);
                $total_price = $tax + $item['price'] * $item['quantity'];
                $invoice_amt = $invoice_amt + $total_price;

                $trans = TransProduct::query()->find($item['id']);
                $trans->quantity = $item['quantity'];
                $trans->total_price = $total_price;
                $trans->tax_total = $tax;

                $trans->save();

//                TransProduct::query()->where('id',$item['id'])
//                    ->update(['quantity'=>$item['quantity'],
//                        'total_price'=>$total_price,
//                        'tax_total'=>$tax]);
            }

            $invoice->invoice_amt = $invoice_amt;
            $invoice->due_amt = $invoice_amt - $invoice->paid_amt - $invoice->discount_amt;
            $invoice->save();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Invoice Item Quantity Updated'],200);
    }
}
