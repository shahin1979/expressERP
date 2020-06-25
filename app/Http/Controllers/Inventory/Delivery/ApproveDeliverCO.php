<?php

namespace App\Http\Controllers\Inventory\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Common\UserActivity;
use App\Models\Company\CompanyProperty;
use App\Models\Inventory\Movement\Delivery;
use App\Models\Inventory\Movement\ProductHistory;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ProductMO;
use App\Traits\ProductTrait;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApproveDeliverCO extends Controller
{
    use TransactionsTrait, ProductTrait;

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

                return '
                    <button  data-remote="viewDeliveryItems/' . $query->id . '"
                        data-challan="' . $query->challan_no . '"
                        data-requisition="' . $query->ref_no . '"
                        data-date="' . $query->delivery_date . '"
                        id="view-delivery-details" type="button" class="btn btn-delivery-details btn-xs btn-primary">Details</button>
                    ';

            })
            ->rawColumns(['product','quantity','del_type','del_for','action'])
            ->make(true);
    }

    public function ajax_call($id)
    {
        $challan = Delivery::query()->where('id',$id)
            ->with(['items'=>function($q){
                $q->where('company_id',$this->company_id);
            }])
            ->first();

        $company = CompanyProperty::query()->where('company_id',$this->company_id)->first();
        $accounts = GeneralLedger::query()->where('company_id',$this->company_id)->get();

        $products = TransProduct::query()->where('company_id',$this->company_id)
            ->where('ref_id',$id)->where('ref_type','D')
            ->get();


        $data = $this->get_delivery_transactions_array($products,$this->company_id);

        dd($data);



    }




}
