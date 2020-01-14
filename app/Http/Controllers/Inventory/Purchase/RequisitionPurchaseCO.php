<?php

namespace App\Http\Controllers\Inventory\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Company\Relationship;
use App\Models\Inventory\Movement\Requisition;
use App\Models\Inventory\Product\ItemTax;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RequisitionPurchaseCO extends Controller
{
    public function index()
    {
        return view('inventory.purchase.requisition-purchase-index');
    }

    public function getReqPurchaseData()
    {
        $query = Requisition::query()->where('company_id',$this->company_id)
            ->where('status',2)->with('items')->with('user')->select('requisitions.*');

        return Datatables::eloquent($query)
            ->addColumn('product', function (Requisition $requisition) {
                return $requisition->items->map(function($items) {
                    return '<span style="color: #a71d2a; font-weight: bold">'. $items->item->name .'</span>';
                })->implode('<br>');
            })

            ->addColumn('quantity', function (Requisition $requisition) {
                return $requisition->items->map(function($items) {
                    return $items->quantity;
                })->implode('<br>');
            })

            ->addColumn('req_for', function (Requisition $requisition) {
                return $requisition->items->map(function($items) {
                    return $items->location->name;
                })->implode('<br>');
            })

            ->editColumn('req_type',function ($requisition) { return $requisition->req_type == 'P' ? 'Purchase' : 'Consumption';})
            ->addColumn('action', function ($requisition) {

                $type = $requisition->req_type == 'P' ? 'Purchase' : 'Consumption';

                return '
                    <button  data-remote="purchaseIndex/' . $requisition->id . '" type="button" class="btn btn-purchase btn-xs btn-primary"></i>Purchase</button>
                    ';
            })
            ->rawColumns(['product','quantity','req_type','req_for','action'])
            ->make(true);
    }

    public function purchase($id)
    {
        $suppliers = Relationship::query()->where('company_id',$this->company_id)
            ->where('relation_type','LS')
            ->orderBy('name')
            ->pluck('name','id');

        $taxes = ItemTax::query()->where('company_id',$this->company_id)->pluck('name','id');

        $requisitions = Requisition::query()->where('id',$id)->with('items')->first();

//        dd($requisitions)
        return view('inventory.purchase.requisition-purchase-submit',compact('requisitions','suppliers','taxes'));
    }

    public function itemSum(Request $request)
    {
        $input_items = $request['item'];

//        $zero = $numberFormatter->getSymbol(\NumberFormatter::ZERO_DIGIT_SYMBOL);


        $json = new \stdClass;

        $sub_total = 0;
        $tax_total = 0;

        $items = array();

        if ($input_items) {
            foreach ($input_items as $key => $item) {
                $item_tax_total= 0;
                $item_sub_total = ($item['price'] * $item['quantity']); //Money function gets last two digit as decimal

                if (!empty($item['tax'])) {
                    $tax = ItemTax::query()->where('id', $item['tax'])->first();


                    if($tax->calculating_mode == 'P')
                    {
                        $item_tax_total = (($item['price'] * $item['quantity']) / 100) * $tax->rate;
                    }

                    if($tax->calculating_mode == 'F')
                    {
                        $item_tax_total = $item['quantity']*$tax->rate;
                    }

                }
//
                $sub_total += $item_sub_total;
                $tax_total += $item_tax_total;

                $total = $item_sub_total + $item_tax_total;

                $items[$key] = $total;
            }
        }

        $json->items = $items;

        $json->sub_total = $sub_total;
        $json->tax_total = $tax_total;

        $grand_total = $sub_total + $tax_total;

        $json->grand_total = $grand_total;

        return response()->json($json);
    }
}
