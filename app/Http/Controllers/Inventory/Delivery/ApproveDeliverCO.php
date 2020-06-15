<?php

namespace App\Http\Controllers\Inventory\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Movement\Delivery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApproveDeliverCO extends Controller
{
    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>56010,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        return view('inventory.delivery.approve-delivery-index');
    }

    public function getDeliveryItems()
    {
        $query = Delivery::query()->where('company_id',$this->company_id)
            ->where('status','CR')
            ->with('items')->with('user')->select('deliveries.*');


        return Datatables::eloquent($query)
            ->addColumn('product', function ($query) {
                return $query->items->map(function($items) {
                    return $items->item->name;
                })->implode('<br>');
            })

            ->addColumn('quantity', function ($query) {
                return $query->items->map(function($items) {
                    return $items->quantity;
                })->implode('<br>');
            })

            ->addColumn('del_for', function ($query) {
                return $query->items->map(function($items) {
                    return isset($items->relationship_id) ?  $items->location->name : 'None';
                })->implode('<br>');
            })

            ->addColumn('del_type', function ($query) {
                return $query->delivery_type == 'SL' ? 'Sales' : ($query->delivery_type == 'CM' ? 'Consumption' : 'Export');
            })

            ->addColumn('action', function ($query) {

                $type = $query->req_type == 'P' ? 'Purchase' : 'Consumption';

                return '
                    <button  data-remote="viewItems/' . $query->id . '"
                        data-requisition="' . $query->ref_no . '"
                        data-date="' . $query->req_date . '"
                        data-type="' . $type . '"
                        id="edit-requisition" type="button" class="btn btn-delivery-details btn-xs btn-primary">Details</button>
                    ';

            })
            ->rawColumns(['product','quantity','del_type','del_for','action'])
            ->make(true);
    }
}
