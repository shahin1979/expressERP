<?php

namespace App\Http\Controllers\Inventory\Import;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Import\ImportLCRegister;
use App\Models\Inventory\Movement\Requisition;
use App\Traits\CommonTrait;
use App\Traits\TransactionsTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApproveImportLCCO extends Controller
{
    use TransactionsTrait, CommonTrait;

    public function index()
    {
        $this->menu_log($this->company_id,51110);

        return view('inventory.import.approve-import-lc-index');
    }

    public function getImportData()
    {
        $requisition = ImportLCRegister::query()->where('company_id',$this->company_id)
            ->where('status','status')->with('items','user')->select('import_lc_registers.*');

        return Datatables::eloquent($requisition)
            ->addColumn('product', function ($requisition) {
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
                    return $items->costcenter->name;
                })->implode('<br>');
            })

            ->editColumn('req_type',function ($requisition) { return $requisition->req_type == 'P' ? 'Purchase' : 'Consumption';})
            ->addColumn('action', function ($requisition) {

                $type = $requisition->req_type == 'P' ? 'Purchase' : 'Consumption';

                return '<div class="btn-req btn-group-sm" role="group" aria-label="Action Button">
                    <button  data-remote="approve/' . $requisition->id . '" type="button" class="btn btn-approve btn-xs btn-primary"></i> Approve</button>
                    <button data-remote="reject/' . $requisition->id . '" type="button" class="btn btn-xs btn-delete btn-danger">Reject</button>
                    ';
            })
            ->rawColumns(['product','quantity','req_type','req_for','action'])
            ->make(true);
    }
}
