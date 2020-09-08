<?php

namespace App\Http\Controllers\Inventory\Export\Report;

use App\Http\Controllers\Controller;
use App\Models\Common\MenuItem;
use App\Models\Inventory\Movement\Delivery;
use App\Models\Inventory\Movement\ProductHistory;
use App\Models\Inventory\Movement\Sale;
use App\Models\Inventory\Product\ProductUniqueId;
use App\Traits\CommonTrait;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExportReportsIndexCO extends Controller
{
    use CommonTrait;

    public function index()
    {
        $this->menu_log($this->company_id,58075);

        $menus = MenuItem::query()->where('content','R')
            ->where('menu_prefix','5J')->where('show',false)
            ->with(['privilege'=>function ($q)
            {
                $q->where('user_id',$this->user_id)->where('view',true);
            }])->get();

        $invoices = Sale::query()->where('invoice_type','EX')
            ->where('company_id',$this->company_id)
            ->pluck('invoice_no','id');

        $deliveries = Delivery::query()->where('delivery_type','EX')
            ->where('company_id',$this->company_id)
            ->pluck('challan_no','id');

        return view('inventory.export.report.export-reports-index',compact('menus','invoices','deliveries'));
    }

    public function invoice(Request $request)
    {
        $invoices = Sale::query()->where('invoice_type','EX')
            ->where('company_id',$this->company_id)
            ->pluck('invoice_no','id');

        if(isset($request['action']))
        {

            $invoice = Sale::query()->where('id',$request['invoice_no'])
                ->with(['items'=>function($q){
                    $q->where('company_id',$this->company_id);
                }])
                ->first();

            $products = ProductHistory::query()->where('company_id',$this->company_id)
                ->whereHas('serial',function($query) use ($invoice) {
                    $query->where('sales_ref_id',$invoice->id);
                })
                ->select('lot_no',DB::raw('count(*) as bale_count, sum(quantity_in) as net_weight, sum(gross_weight) as gross_weight '))
                ->groupBy('lot_no')
                ->get();


            switch ($request['action'])
            {


                case 'pre-shipment':

                    $view = \View::make('inventory.export.report.print.commercial-invoice-pre-shipment-pdf',compact('invoice','products'));
                    $html = $view->render();

                    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

                    $pdf::SetMargins(15, 5, 10,10);

                    $pdf::AddPage();

                    $pdf::writeHTML($html, true, false, true, false, '');
                    $pdf::Output('Preshipment.pdf');

                break;

                case 'print':



                    if($invoice->shipping_status===true)
                    {
                        return redirect()->back()->with('error','You can print only after shipment ');
                    }

                    $view = \View::make('inventory.export.report.print.commercial-invoice-pdf',compact('invoice','products'));
                    $html = $view->render();

                    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

                    $pdf::SetMargins(15, 5, 10,10);

                    $pdf::AddPage();

                    $pdf::writeHTML($html, true, false, true, false, '');
                    $pdf::Output('Invoice.pdf');

                break;
            }
        }

        return view('inventory.export.report.commercial-invoice-index',compact('invoices'));
    }

    public function packingList(Request $request)
    {

        $invoice = Sale::query()->where('delivery_challan_id',$request['challan_id'])
            ->with('customer')->first();

        $products = ProductHistory::query()->where('company_id',$this->company_id)
            ->whereHas('serial',function($query) use ($invoice) {
                $query->where('delivery_ref_id',$invoice->delivery_challan_id);
            })
            ->select('lot_no',DB::raw('count(*) as bale_count, sum(quantity_in) as net_weight, sum(gross_weight) as gross_weight '))
            ->groupBy('lot_no')
            ->get();




        switch ($request['action'])
        {
            case 'pre-shipment':

                $view = \View::make('inventory.export.report.print.packing-list-pre-shipment-pdf',compact('invoice','products'));
                $html = $view->render();
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

                $pdf::SetMargins(15, 5, 10,10);

                $pdf::AddPage();

                $pdf::writeHTML($html, true, false, true, false, '');
                $pdf::Output('packing-pre-shipment.pdf');

                break;

            case 'print':

                switch($request['report_id'])
                {
                    case 'P':
                        $view = \View::make('inventory.export.report.print.packing-list-pdf',compact('invoice','products'));
                        $html = $view->render();
                        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);
                        $pdf::SetMargins(15, 5, 10,10);
                        $pdf::AddPage();
                        $pdf::writeHTML($html, true, false, true, false, '');
                        $pdf::Output('packing-pre-shipment.pdf');
                        break;

                    case 'D':
                        $view = \View::make('inventory.export.report.print.details-packing-list-pdf',compact('invoice','products'));
                        $html = $view->render();
                        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);
                        $pdf::SetMargins(15, 5, 10,10);
                        $pdf::AddPage();
                        $pdf::writeHTML($html, true, false, true, false, '');
                        $pdf::Output('packing-pre-shipment.pdf');
                        break;

                    case 'C':

                        $items = ProductHistory::query()->where('company_id',$this->company_id)
                            ->whereHas('serial',function($query) use ($invoice) {
                                $query->where('delivery_ref_id',$invoice->delivery_challan_id);
                            })->get();

                        $containers = $items->unique('container');

                        $view = \View::make('inventory.export.report.print.container-packing-list-pdf',compact('invoice','items','containers'));
                        $html = $view->render();
                        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);
                        $pdf::SetMargins(15, 5, 10,10);
                        $pdf::AddPage();
                        $pdf::writeHTML($html, true, false, true, false, '');
                        $pdf::Output('packing-pre-shipment.pdf');
                }

        }




    }
}
