<?php

namespace App\Http\Controllers\Inventory\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Movement\Requisition;
use App\Models\Inventory\Movement\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SalesInvoiceDeliveryCO extends Controller
{
    public function index()
    {

        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>56002,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        return view('inventory.delivery.delivery-sales-invoice-index');
    }

    public function getInvoiceItems()
    {
        $query = Sale::query()->where('company_id',$this->company_id)
            ->where('status','AP')
            ->where('delivery_status',false)
            ->with('items')->with('user')
            ->with('customer')->select('sales.*');


        return Datatables::eloquent($query)

            ->addColumn('customer',function ($query){
                return $query->customer->name;
            })
            ->addColumn('product', function ($query) {
                return $query->items->map(function($items) {
                    return '<span style="color: #a71d2a; font-weight: bold">'. $items->item->name .'</span>';
                })->implode('<br>');
            })

            ->addColumn('quantity', function ($query) {
                return $query->items->map(function($items) {
                    return number_format($items->quantity,2);
                })->implode('<br>');
            })

            ->addColumn('due', function ($query) {
                return $query->items->map(function($items) {
                    return number_format(($items->quantity - $items->delivered),2);
                })->implode('<br>');
            })

            ->addColumn('action', function ($query) {

                $type = $query->req_type == 'P' ? 'Purchase' : 'Consumption';

                return '
                    <button  data-remote="viewItems/' . $query->id . '"
                        data-requisition="' . $query->ref_no . '"
                        data-date="' . $query->req_date . '"
                        data-type="' . $type . '"
                        id="edit-requisition" type="button" class="btn btn-delivery-index btn-xs btn-primary">Delivery</button>
                    ';
            })
            ->rawColumns(['product','quantity','due','action'])
            ->make(true);
    }
}
