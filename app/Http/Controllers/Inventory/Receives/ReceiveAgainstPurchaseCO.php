<?php

namespace App\Http\Controllers\Inventory\Receives;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Movement\Purchase;
use Illuminate\Http\Request;

class ReceiveAgainstPurchaseCO extends Controller
{
    public function index()
    {
        return view('inventory.receives.receive-purchase-index');
    }

    public function PurchaseData()
    {
        $query = Purchase::query()->where('company_id',$this->company_id)
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
}
