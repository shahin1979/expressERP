<?php

namespace App\Http\Controllers\Inventory\Sales;

use App\Http\Controllers\Controller;
use App\Models\Company\Relationship;
use App\Models\Inventory\Movement\Sale;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PrintSalesInvoiceCO extends Controller
{
    public function index()
    {
        $customers = Relationship::query()->where('company_id',$this->company_id)
            ->where('relation_type','CS')->orderBy('name')->pluck('name','id');

        return view('inventory.sales.report.print-sales-invoice-index',compact('customers'));
    }

    public function getInvoice()
    {
        $query = Sale::query()->where('company_id',$this->company_id)
            ->where('status','AP')
            ->with('items')->with('user')->with('customer')
            ->orderBy('sales.invoice_date','DESC')
            ->select('sales.*');


        return Datatables::eloquent($query)
            ->addColumn('product', function (Sale $sales) {
                return $sales->items->map(function($items) {
                    return $items->item->name;
                })->implode('<br>');
            })

            ->addColumn('quantity', function (Sale $sales) {
                return $sales->items->map(function($items) {
                    return $items->quantity;
                })->implode('<br>');
            })

            ->addColumn('unit_price', function (Sale $sales) {
                return $sales->items->map(function($items) {
                    return $items->unit_price;
                })->implode('<br>');
            })
            ->addColumn('action', function ($sales) {
                return '
                    <button  data-remote="printInvoice/' . $sales->id . '"
                        id="print-invoice" type="button" class="btn btn-print btn-xs btn-primary"><i class="fa fa-print">Print</i></button>
                    ';
            })
            ->rawColumns(['product','quantity','unit_price','action'])
            ->make(true);
    }

    public function printInvoice($id)
    {
        $invoice = Sale::query()->where('id',$id)->with('items')->with('customer')->first();

        $view = \View::make('inventory.sales.report.pdf-sales-invoice',compact('invoice'));
        $html = $view->render();

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);
//                    $pdf = new TCPDF('L', PDF_UNIT, array(105,148), true, 'UTF-8', false);
//                    $pdf::setMargin(0,0,0);


        $pdf::SetMargins(15, 0, 5,0);

        $pdf::AddPage();

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('requisitions.pdf');

        return view('inventory.requisition.pdf-requisition');
    }
}
