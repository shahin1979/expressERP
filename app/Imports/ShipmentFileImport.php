<?php

namespace App\Imports;

use App\Models\Inventory\Export\ExportShippingFileUpload;
use App\Models\Inventory\Movement\Sale;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ShipmentFileImport implements ToModel, WithStartRow
{

    private $invoice_id;
    private $challan_id;

    public function  __construct(Sale $invoice)
    {
        $this->invoice_id = $invoice->id;
        $this->challan_id = $invoice->delivery_challan_id;
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }


    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        if(Str::length($row[0]) > 0)
        {
            return new ExportShippingFileUpload([
                'company_id'=>1,
                'forwarder'=>$row[0],
                'container' =>$row[1],
                'seal_no' =>$row[3],
                'invoice_id' =>$this->invoice_id,
                'challan_id' =>$this->challan_id,
                'size' =>$row[2],
                'destination' =>$row[4],
                'booking_no' =>$row[5],
                'bl_no' =>$row[6],
                'cbm' =>$row[16],
                'user_id' =>Auth::id(),
            ]);
        }
    }
}
