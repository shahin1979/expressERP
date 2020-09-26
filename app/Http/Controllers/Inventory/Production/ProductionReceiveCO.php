<?php

namespace App\Http\Controllers\Inventory\Production;

use App\Http\Controllers\Controller;
use App\Models\Company\TransCode;
use App\Models\Inventory\Movement\LastBaleNo;
use App\Models\Inventory\Movement\ProductHistory;
use App\Models\Inventory\Movement\Receive;
use App\Models\Inventory\Product\ProductMO;
use App\Traits\CommonTrait;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductionReceiveCO extends Controller
{
    use CommonTrait, TransactionsTrait;

    public function index(Request $request, $line)
    {
        $machine = $line == 'One' ? 1 : 2;
        $menu = $line == 'One' ? 54005 : 54008;

        $this->menu_log($this->company_id,$menu);

        $production = LastBaleNo::query()->where('line_no',$machine)->with('item')->first();
        return view('inventory.production.production-line-one-index',compact('production'));
    }

    public function view(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:txt|max:2048'
        ]);

//        dd($request->all());

//        dd($request->all());
//        $file = $request->hasFile('import_file');

        if ($request->file()) {
            $file = $request->file('import_file');
            $param = [];
//            $prod = null;
            $fiscal = $this->get_fiscal_data_from_current_date($this->company_id);

            switch ($request['action'])
            {
                case 1:
                    $file->move('weight/', 'one.txt');
                    $file = fopen('weight/one.txt', "r") or die("Unable to open file!");
                    $gross =  fgets($file);
                    fclose($file);

                    if(trim($gross) <= 2000 OR trim($gross) >= 4000)
                    {
                        return redirect()->back()->with('error','Invalid Quantity');
                    }

                    $prod = LastBaleNo::query()->where('line_no',$request['action'])->with('item')->first();

                    $tr_code = TransCode::query()->where('company_id', $this->company_id)
                        ->where('trans_code', 'RC') // Delivery Challan
                        ->where('fiscal_year', $fiscal->fiscal_year)
                        ->lockForUpdate()->first();

                    $param['line_no'] = 'One';
                    $param['receive_no'] = $tr_code->last_trans_id;
                    $param['gross_weight'] = trim($gross)/10;

                    TransCode::query()->where('company_id', $this->company_id)
                        ->where('trans_code', 'RC')
                        ->where('fiscal_year', $fiscal)
                        ->increment('last_trans_id');
                    break;

                case 2:

                    $file->move('weight/', 'two.txt');
                    $file = fopen('weight/two.txt', "r") or die("Unable to open file!");
                    $gross =  fgets($file);
                    fclose($file);

                    if(trim($gross) <= 2000 OR trim($gross) >= 4000)
                    {
                        return redirect()->back()->with('error','Invalid Quantity');
                    }

                    $prod = LastBaleNo::query()->where('line_no',$request['action'])->with('item')->first();

                    $tr_code = TransCode::query()->where('company_id', $this->company_id)
                        ->where('trans_code', 'RC') // Delivery Challan
                        ->where('fiscal_year', $fiscal->fiscal_year)
                        ->lockForUpdate()->first();

                    $param['line_no'] = 'Two';
                    $param['receive_no'] = $tr_code->last_trans_id;
                    $param['gross_weight'] = trim($gross)/10;

                    TransCode::query()->where('company_id', $this->company_id)
                        ->where('trans_code', 'RC')
                        ->where('fiscal_year', $fiscal)
                        ->increment('last_trans_id');

                    break;

                default:
                    abort(400, 'No file was uploaded.');

            }

            return view('inventory.production.production-line-one-index',compact('prod','param'));
        }

        return redirect()->back()->with('error','Invalid File');

    }

    public function store(Request $request)
    {

        $request->validate([
            'quantity_in' => 'required|numeric|between:200.00,400.99'
        ]);
//        $product = ProductMO::query()->where('id',$request['product_id'])->first();
        $production = LastBaleNo::query()->where('line_no',$request['line_no'])->with('item')->lockForUpdate()->first();
        $receive = Receive::query()->where('company_id',$this->company_id)->where('challan_no',$request['receive_no'])->first();

        DB::beginTransaction();

        try {

            if($request['quantity_in'] > 200)
            {
                ProductMO::query()->where('id',$request['product_id'])
                    ->where('company_id',$this->company_id)
                    ->increment('purchase_qty',$request['quantity_in']);

                ProductMO::query()->where('id',$request['product_id'])
                    ->where('company_id',$this->company_id)
                    ->increment('on_hand',$request['quantity_in']);

                $baleNo = GenUtil::get_bale_number_new(1);

//                $digit = strlen($baleNo);
//                $startD = $digit - 7;
//                $lotno = substr($baleNo,$startD,5);

                ProductHistory::query()->insert(
                    ['company_id' => $this->company_id,
                        'ref_no' => $request['receive_no'],
                        'ref_id' => $receive->id,
                        'tr_date' => Carbon::now(),
                        'ref_type' =>'F',
                        'contra_ref' => $request['receive_no'],
                        'product_id' => $request['product_id'],
                        'quantity_in' => $request['quantity_in'],
                        'quantity_out' => 0,
                        'unit_price' => $production->item->unit_price,
                        'total_price' => $request['quantity_in']*$production->item->unit_price,
                        'tr_weight' =>$production->tr_weight,
                        'gross_weight' => $request['gross_weight'],
                        'lot_no' =>$production->lot_no,
                        'bale_no' =>$production->bale_no,
                        'relationship_id' =>2,
                        'remarks' =>'Receive from production',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'userId' => Auth::id()
                    ]
                );
            }

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error',$error);
        }

        DB::commit();

        $view = \View::make('stock/production/production-label-print',compact('baleNo','grossWeight','weight','dspec','mspec','mdate'));
        $html = $view->render();

        $pdf = new TCPDF('L', PDF_UNIT, array(105,148), true, 'UTF-8', false);

        $pdf::SetFont('helvetica', '', 10);



// define barcode style
        $style = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => false,
            'cellfitalign' => '',
            'border' => true,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 10,
            'stretchtext' => 4
        );

// PRINT VARIOUS 1D BARCODES

//        $pdf::SetFooterMargin(0);
//        $pdf::setPrintFooter(false);
        $pdf::SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT,0);

        $pdf::AddPage('L');



        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::write1DBarcode($baleNo, 'C39', '', '', '', 25, 0.6, $style, 'N'); //150 top padding
        $pdf::Output($baleNo.'.pdf');


        return redirect()->action('Stock\Production\ProductionController@index');


    }
}
