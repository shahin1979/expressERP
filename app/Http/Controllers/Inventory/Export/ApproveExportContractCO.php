<?php

namespace App\Http\Controllers\Inventory\Export;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Export\ExportContract;
use App\Models\Inventory\Movement\TransProduct;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ApproveExportContractCO extends Controller
{
    use CommonTrait;

    public function index()
    {
        $this->menu_log($this->company_id,58010);

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

            ->addColumn('price', function ($query) {
                return $query->items->map(function($items) {
                    return $items->unit_price;
                })->implode('<br>');
            })

            ->editColumn('contract_amt', function ($query) {
                return $query->contract_amt.$query->currency;
            })

            ->addColumn('action', function ($query) {

                return '<div class="btn-req btn-group-sm" role="group" aria-label="Action Button">
                    <button  data-remote="contract/approve/' . $query->id . '" type="button" class="btn btn-approve btn-xs btn-primary"></i>Approve</button>
                    <button data-remote="contract/reject/' . $query->id . '" type="button" class="btn btn-xs btn-reject btn-danger">Reject</button>
                    ';
            })
            ->rawColumns(['product','quantity','price','action'])
            ->make(true);
    }

    public function approve($id)
    {
        DB::beginTransaction();

        try {

            ExportContract::query()->where('company_id',$this->company_id)
                ->where('id',$id)->update(
                    ['status'=>'AP','approved_by'=>$this->user_id, 'approve_date'=>Carbon::now()]);

            TransProduct::query()->where('company_id',$this->company_id)
                ->where('ref_type','E')
                ->where('ref_id',$id)
                ->update(['status'=>2]); //Approved

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Export Contract Approved'],200);
    }

    public function reject($id)
    {
        DB::beginTransaction();

        try {

            ExportContract::query()->where('company_id',$this->company_id)
                ->where('id',$id)->update(
                    ['status'=>'RJ','approved_by'=>$this->user_id, 'approve_date'=>Carbon::now()]);

            TransProduct::query()->where('company_id',$this->company_id)
                ->where('ref_type','E')
                ->where('ref_id',$id)
                ->update(['status'=>6]); //Rejected

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Export Contract Rejected'],200);
    }
}
