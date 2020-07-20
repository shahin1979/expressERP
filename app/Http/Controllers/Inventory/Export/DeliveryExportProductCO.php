<?php

namespace App\Http\Controllers\Inventory\Export;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Export\ExportContract;
use App\Models\Inventory\Movement\TransProduct;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;

class DeliveryExportProductCO extends Controller
{
    use CommonTrait;

    public function index()
    {
        $this->menu_log($this->company_id,70015);

        $contracts = ExportContract::query()->where('company_id',$this->company_id)
            ->where('status','AP')->pluck('export_contract_no','id');

        return view('inventory.export.index.delivery-export-product-index',compact('contracts'));
    }

    public function getExportProdDT($contract)
    {

        $total = TransProduct::query()->where(DB::Raw("REPLACE(refNo,'/','')"),$contract)->sum('qtyOut');

        $scontract = InvoiceItem::where(DB::Raw("REPLACE(exscno,'/','')"),$contract)->first();

        $balance = $scontract->quantity - $total ;


        $dData = TempDeliveryModel::where('refNo',$scontract->exscno)->with('item')->orderBy('id','DESC');




        return Datatables::of($dData)
            ->addColumn('action', function ($dData) {

                return '
                    <button data-remote="delivery.data.delete/' . $dData->id . '" type="button" class="btn btn-delete btn-xs btn-danger pull-right" ><i class="glyphicon glyphicon-remove"></i>Del</button>
                    ';
            })
            ->with('total',$total)
            ->with('balance',$balance)
            ->make(true);

    }
}
