<?php

namespace App\Http\Controllers\Inventory\Export;

use App\Http\Controllers\Controller;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;

class ApproveExportDeliveryCO extends Controller
{
    use CommonTrait;

    public function index()
    {
        $this->menu_log($this->company_id,70020);

        return view('inventory.export.index.approve-export-delivery-index');
    }
}
