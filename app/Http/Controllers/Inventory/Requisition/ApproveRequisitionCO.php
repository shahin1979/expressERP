<?php

namespace App\Http\Controllers\Inventory\Requisition;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Movement\Requisition;
use App\Models\Inventory\Movement\TransProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ApproveRequisitionCO extends Controller
{
    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>52025,'user_id'=>$this->user_id
            ]);

        return view('inventory.requisition.approve-requisition-index');
    }

    public function getReqData()
    {
        $query = Requisition::query()->where('company_id',$this->company_id)
            ->where('status',1)->with('items')->with('user')->select('requisitions.*');

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

            ->addColumn('req_for', function (Requisition $requisition) {
                return $requisition->items->map(function($items) {
                    return $items->location->name;
                })->implode('<br>');
            })

            ->editColumn('req_type',function ($requisition) { return $requisition->req_type == 'P' ? 'Purchase' : 'Consumption';})
            ->addColumn('action', function ($requisition) {

                $type = $requisition->req_type == 'P' ? 'Purchase' : 'Consumption';

                return '
                    <button  data-remote="approve/' . $requisition->id . '" type="button" class="btn btn-approve btn-xs btn-primary"></i> Approve</button>
                    <button data-remote="reject/' . $requisition->id . '" type="button" class="btn btn-xs btn-delete btn-danger pull-right">Reject</button>
                    ';
            })
            ->rawColumns(['product','quantity','req_type','req_for','action'])
            ->make(true);
    }

    public function approve($id)
    {
        DB::beginTransaction();

        try {

            Requisition::query()->where('company_id',$this->company_id)
                ->where('id',$id)->update(['status'=>2,'authorized_by'=>$this->user_id]);

            TransProduct::query()->where('company_id',$this->company_id)
                ->where('ref_type','R')
                ->where('ref_id',$id)
                ->update(['status'=>2]);

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Requisition Approved'],200);
    }

}
