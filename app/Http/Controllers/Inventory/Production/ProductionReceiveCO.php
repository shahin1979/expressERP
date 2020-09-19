<?php

namespace App\Http\Controllers\Inventory\Production;

use App\Http\Controllers\Controller;
use App\Models\Company\TransCode;
use App\Models\Inventory\Movement\LastBaleNo;
use App\Models\Inventory\Product\ProductMO;
use App\Traits\CommonTrait;
use App\Traits\TransactionsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductionReceiveCO extends Controller
{
    use CommonTrait, TransactionsTrait;

    public function index()
    {
        $this->menu_log($this->company_id,54005);

        $production = LastBaleNo::query()->where('line_no',1)->with('item')->first();
        return view('inventory.production.production-line-one-index',compact('production'));
    }

    public function view(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:txt|max:2048'
        ]);

//        dd($request->all());
//        $file = $request->hasFile('import_file');

        if ($request->file()) {
            $file = $request->file('import_file');
            $param = [];
//            $prod = null;
            $fiscal = $this->get_fiscal_data_from_current_date($this->company_id);

            switch ($request['action'])
            {
                case 'one':
                    $file->move('weight/', 'one.txt');
                    $file = fopen('weight/one.txt', "r") or die("Unable to open file!");
                    $gross =  fgets($file);
                    fclose($file);

                    if(trim($gross) <= 2000 OR trim($gross) >= 4000)
                    {
                        return redirect()->back()->with('error','Invalid Quantity');
                    }

                    $prod = LastBaleNo::query()->where('line_no',1)->with('item')->first();

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

                case 'two':

                    $file->move('weight/', 'two.txt');
                    $file = fopen('weight/two.txt', "r") or die("Unable to open file!");
                    $gross =  fgets($file);
                    fclose($file);

                    if(trim($gross) <= 2000 OR trim($gross) >= 4000)
                    {
                        return redirect()->back()->with('error','Invalid Quantity');
                    }

                    $prod = LastBaleNo::query()->where('line_no',1)->with('item')->first();

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
        $product = ProductMO::query()->where('id',$request['product_id'])->first();

        dd($request->all());
    }
}
