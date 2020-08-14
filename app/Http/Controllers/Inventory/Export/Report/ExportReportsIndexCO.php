<?php

namespace App\Http\Controllers\Inventory\Export\Report;

use App\Http\Controllers\Controller;
use App\Models\Common\MenuItem;
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
        $this->menu_log($this->company_id,57075);

        $menus = MenuItem::query()->where('content','R')
            ->where('menu_prefix','5J')->where('show',false)
            ->with(['privilege'=>function ($q)
            {
                $q->where('user_id',$this->user_id)->where('view',true);
            }])->get();
        return view('inventory.export.report.export-reports-index',compact('menus'));
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

                    if($invoice->shipping_status == null)
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
}
