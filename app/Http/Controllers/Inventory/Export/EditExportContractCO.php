<?php

namespace App\Http\Controllers\Inventory\Export;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Export\ExportContract;
use App\Models\Inventory\Movement\Sale;
use App\Models\Inventory\Movement\TransProduct;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class EditExportContractCO extends Controller
{
    use CommonTrait;

    public function index()
    {
        $this->menu_log($this->company_id,58007);

        return view('inventory.export.index.edit-export-contract-index');
    }

    public function getEditContractData()
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

                return '
                    <button  data-remote="contract/edit/' . $query->id . '"
                        data-invoice="' . $query->invoice_no . '"
                        data-contract="' . $query->export_contract_no . '"
                        data-date="' . $query->contract_date . '"
                        data-customer="' . $query->customer->name . '"
                        data-amount="' . number_format($query->contract_amt,2) .$query->currency . '"
                        data-currency="' . $query->currency . '"
                        id="edit-invoice" type="button" class="btn btn-edit btn-xs btn-primary"><i class="fa fa-edit"></i>Edit</button>
                    <button data-remote="delete/' . $query->id . '" type="button" class="btn btn-xs btn-delete btn-danger pull-right"><i class="fa fa-remove"></i>Delete</button>
                    ';
            })
            ->rawColumns(['product','quantity','action'])
            ->make(true);
    }

    public function edit($id)
    {
        $contract = TransProduct::query()->where('company_id',$this->company_id)
            ->where('ref_id',$id)->where('ref_type','E')
            ->with('item')->with('contract')->get();

        return response()->json($contract);
    }

    public function update(Request $request)
    {
        $contract = ExportContract::query()->where('company_id',$this->company_id)
            ->where('invoice_no',$request['invoice_no'])->first();

        $contract_amt = 0;

        DB::beginTransaction();

        try{

            foreach ($request['item'] as $item) {

                $total_price = $item['price'] * $item['quantity'];
                $contract_amt = $contract_amt + $total_price;

                $trans = TransProduct::query()->find($item['id']);
                $trans->quantity = $item['quantity'];
                $trans->unit_price = $item['price'];
                $trans->total_price = $total_price;

                $trans->save();

            }

            $contract->contract_amt = $contract_amt;
            $contract->save();

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Export Contract Updated'],200);
    }
}
