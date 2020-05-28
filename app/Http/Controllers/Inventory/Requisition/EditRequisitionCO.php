<?php

namespace App\Http\Controllers\Inventory\Requisition;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Movement\Requisition;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ProductMO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class EditRequisitionCO extends Controller
{
    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>52015,'user_id'=>$this->user_id
            ]);

        $products = ProductMO::query()->where('company_id',$this->company_id)->pluck('name','id');
        return view('inventory.requisition.edit-requisition-index',compact('products'));
    }

    public function getReqData()
    {
        $query = Requisition::query()->where('company_id',$this->company_id)
            ->where('status',1)->with('items')->with('user')->select('requisitions.*');


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

            ->addColumn('req_for', function ($query) {
                return $query->items->map(function($items) {
                    return isset($items->relationship_id) ? (isset($items->location->name) ? $items->location->name : 'None') : 'None';
                })->implode('<br>');
            })


            ->editColumn('req_type',function ($requisition) { return $requisition->req_type == 'P' ? 'Purchase' : 'Consumption';})
            ->addColumn('action', function ($requisition) {

                $type = $requisition->req_type == 'P' ? 'Purchase' : 'Consumption';

                return '
                    <button  data-remote="edit/' . $requisition->id . '"
                        data-requisition="' . $requisition->ref_no . '"
                        data-date="' . $requisition->req_date . '"
                        data-type="' . $type . '"
                        id="edit-requisition" type="button" class="btn btn-edit btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</button>
                    <button data-remote="delete/' . $requisition->id . '" type="button" class="btn btn-xs btn-delete btn-danger pull-right"  ><i class="glyphicon glyphicon-remove"></i>Delete</button>
                    ';
            })
            ->rawColumns(['product','quantity','req_type','req_for','action'])
            ->make(true);
    }

    public function edit($id)
    {
        $requisition = TransProduct::query()->where('company_id',$this->company_id)
            ->where('ref_id',$id)->where('ref_type','R')
            ->with('item')->with('requisition')->with('location') ->get();

        return response()->json($requisition);
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        try{

            foreach ($request['item'] as $item) {

                TransProduct::query()->where('id',$item['id'])->update(['quantity'=>$item['quantity']]);
            }
        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Requisition Updated'],200);
    }
}
