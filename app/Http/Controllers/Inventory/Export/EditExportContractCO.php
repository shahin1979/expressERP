<?php

namespace App\Http\Controllers\Inventory\Export;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Export\ExportContract;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EditExportContractCO extends Controller
{
    use CommonTrait;

    public function index()
    {
        $this->menu_log($this->company_id,70010);

        return view('inventory.export.index.approve-export-contract-index');
    }

    public function getExportContractData()
    {
        $query = ExportContract::query()->where('export_contracts.company_id',$this->company_id)
            ->where('export_contracts.status','CR')->with('items')->with('user')
            ->with('customer');

//            ->select('export_contracts.*');

        return DataTables::eloquent($query)
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

            ->addColumn('action', function ($query) {

                return '<div class="btn-req btn-group-sm" role="group" aria-label="Action Button">
                    <button  data-remote="approve/' . $query->id . '" type="button" class="btn btn-approve btn-xs btn-primary"></i> Approve</button>
                    <button data-remote="reject/' . $query->id . '" type="button" class="btn btn-xs btn-delete btn-danger">Reject</button>
                    ';
            })
            ->rawColumns(['product','quantity','action'])
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
