<?php

namespace App\Http\Controllers\Inventory\Requisition;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Movement\Requisition;
use Carbon\Carbon;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PrintRequisitionCO extends Controller
{
    public function index(Request $request)
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>52050,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        return view('inventory.requisition.report-requisition-index');
    }

    public function getPrintReqData()
    {
        $query = Requisition::query()->where('company_id',$this->company_id)
            ->whereIn('status',[2,3])->with('items')->with('user')
            ->orderBy('ref_no', 'desc')
            ->select('requisitions.*');

        return Datatables::eloquent($query)
            ->addColumn('product', function (Requisition $requisition) {
                return $requisition->items->map(function($items) {
                    return $items->item->name;
                })->implode('<br>');
            })

            ->addColumn('quantity', function (Requisition $requisition) {
                return $requisition->items->map(function($items) {
                    return $items->quantity;
                })->implode('<br>');
            })

            ->addColumn('req_for', function (Requisition $requisition) {
                return $requisition->items->map(function($items) {
                    return $items->costcenter->name;
                })->implode('<br>');
            })

            ->editColumn('req_type',function ($requisition) { return $requisition->req_type == 'P' ? 'Purchase' : 'Consumption';})
            ->addColumn('action', function ($requisition) {

                $type = $requisition->req_type == 'P' ? 'Purchase' : 'Consumption';

                return '
                    <button  data-remote="print/' . $requisition->id . '" type="button" class="btn btn-print btn-xs btn-primary"><i class="fa fa-print">Print</i></button>
                    ';
            })
            ->rawColumns(['product','quantity','req_type','req_for','action'])
            ->make(true);
    }

    public function print($id)
    {
        $requisition = Requisition::query()->where('company_id',$this->company_id)
            ->where('id',$id)->with('items')->with('user','approver')->first();

        $view = \View::make('inventory.requisition.pdf-requisition',compact('requisition'));
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
