<?php

namespace App\Http\Controllers\Inventory\Sales;

use App\Http\Controllers\Controller;
use App\Models\Company\Relationship;
use Illuminate\Http\Request;

class PrintSalesInvoiceCO extends Controller
{
    public function index()
    {
        $customers = Relationship::query()->where('company_id',$this->company_id)
            ->where('relation_type','CS')->orderBy('name')->pluck('name','id');

        return view('inventory.sales.report.print-sales-invoice-index',compact('customers'));
    }
}
