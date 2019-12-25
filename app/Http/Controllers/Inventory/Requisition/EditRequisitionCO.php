<?php

namespace App\Http\Controllers\Inventory\Requisition;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Movement\Requisition;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ProductMO;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EditRequisitionCO extends Controller
{
    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>54015,'user_id'=>$this->user_id
            ]);

//        $data = TransProduct::query()->with('item')->get();
//
//        dd($data);

        $products = ProductMO::query()->where('company_id',$this->company_id)->pluck('name','id');
        return view('inventory.requisition.edit-requisition-index',compact('products'));
    }

    public function getReqData()
    {
        $query = Requisition::query()->where('status',1)->with('items')->select('requisitions.*');


        return Datatables::eloquent($query)
            ->addColumn('product', function (Requisition $requisition) {
                return $requisition->items->map(function($items) {
                    return $items->item->name;
                })->implode('<br>');
            })

            ->addColumn('quantity', function (Requisition $requisition) {
                return $requisition->items->map(function($items) {
                    return $items->quantity;
                })->implode('<br>');
            })

            ->editColumn('req_type',function ($requisition) { return $requisition->reqType == 'P' ? 'Purchase' : 'Consumption';})
            ->addColumn('action', function ($requisition) {

                return '
                    <button  data-remote="edit/' . $requisition->id . '" id="edit-requisition" type="button" class="btn btn-edit btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</button>
                    <button data-remote="delete/' . $requisition->id . '" type="button" class="btn btn-xs btn-delete btn-danger pull-right"  ><i class="glyphicon glyphicon-remove"></i>Delete</button>
                    ';
            })
            ->rawColumns(['product','quantity','req_type','action'])
            ->make(true);
    }
}
