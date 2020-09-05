<?php

namespace App\Http\Controllers\Inventory\Export;

use App\Http\Controllers\Controller;
use App\Imports\ShipmentFileImport;
use App\Models\Inventory\Movement\Sale;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UploadShipmentFileCO extends Controller
{
    use CommonTrait;

    public function index()
    {
        $this->menu_log($this->company_id,57040);

        $invoices = Sale::query()->where('company_id',$this->company_id)->where('invoice_type','EX')
            ->pluck('invoice_no','id');

        return view('inventory.export.index.upload-shipment-file-index',compact('invoices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:xls,xlx',
        ]);

//        dd($request->all());

        $invoice = Sale::query()->find($request['invoice_id']);

        if($request->hasFile('import_file')) {
            $path = $request->file('import_file')->getRealPath();
            Excel::import(new ShipmentFileImport($invoice),request()->file('import_file'));
        }

        return redirect()->action('Inventory\Export\UploadShipmentFileCO@index');
    }
}
