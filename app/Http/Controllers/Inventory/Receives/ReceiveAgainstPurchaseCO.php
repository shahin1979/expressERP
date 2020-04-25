<?php

namespace App\Http\Controllers\Inventory\Receives;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Movement\Purchase;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReceiveAgainstPurchaseCO extends Controller
{
    public function index()
    {
        return view('inventory.receives.receive-purchase-index');
    }

    public function getData()
    {
        $query = Purchase::query()->where('company_id',$this->company_id)
            ->where('status','PR')->with('items')->with('user')->select('purchases.*');


        return DataTables::eloquent($query)
            ->addColumn('product', function ($query) {
                return $query->items->map(function($items) {
                    return $items->item->name;
                })->implode('<br>');
            })

            ->addColumn('supplier', function ($query) {
                return $query->items->map(function($items) {
                    return $items->supplier->name;
                })->implode('<br>');
            })

            ->addColumn('quantity', function ($query) {
                return $query->items->map(function($items) {
                    return $items->quantity;
                })->implode('<br>');
            })

            ->addColumn('unit_price', function ($query) {
                return $query->items->map(function($items) {
                    return $items->unit_price;
                })->implode('<br>');
            })
            ->addColumn('action', function ($query) {


                return '
                    <button  data-remote="receive/' . $query->id . '"
                        data-order="' . $query->ref_no . '"
                        data-date="' . $query->po_date . '"
                        data-amount="' . number_format($query->invoice_amt,2) . '"
                        id="receive-invoice" type="button" class="btn btn-receive btn-xs btn-primary"><i class="fa fa-edit">Receive</i></button>
                    <button data-remote="return/' . $query->id . '" type="button" class="btn btn-xs btn-return btn-danger pull-right"  ><i class="fa fa-remove">Return</i></button>
                    ';
            })
            ->rawColumns(['product','quantity','unit_price','action','supplier'])
            ->make(true);
    }
}
