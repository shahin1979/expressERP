<?php

namespace App\Http\Controllers\Inventory\Report;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Product\ProductMO;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Elibyy\TCPDF\Facades\TCPDF;

class StockPositionCO extends Controller
{
    public function index(Request $request)
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>58015,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        $report = null;

        if($request['report_date'])
        {
            $report = ProductMO::query()->where('company_id',$this->company_id)
                ->where('status',true)->get();

            $category = $report->unique('category_id');

            switch($request['action'])
            {
                case 'preview':

                    return view('inventory.report.stock-position-index',compact('report','category'));
                    break;

                case 'print':

                    ini_set('max_execution_time', 900);
                    ini_set('pcre.backtrack_limit', 5000000);

                    $view = \View::make('inventory.report.pdf.pdf-stock-position-report',compact('report','category'));
                    $html = $view->render();

                    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

                    $pdf::setFooterCallback(function($pdf){

                        // Position at 15 mm from bottom
                        $pdf->SetY(-15);
                        // Set font
                        $pdf->SetFont('helvetica', 'I', 8);
                        // Page number
                        $pdf->Cell(0, 10, 'Page '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

                    });

                    $pdf::SetMargins(10, 5, 5,0);

                    $pdf::AddPage();

                    $pdf::writeHTML($html, true, false, true, false, '');

                    $pdf::Output('stock.pdf');

                    break;

            }

        }
        return view('inventory.report.stock-position-index',compact('report'));
    }
}
