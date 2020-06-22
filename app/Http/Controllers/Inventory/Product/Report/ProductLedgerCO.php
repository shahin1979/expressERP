<?php

namespace App\Http\Controllers\Inventory\Product\Report;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Movement\ProductHistory;
use App\Models\Inventory\Product\ProductMO;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductLedgerCO extends Controller
{
    public function index(Request $request)
    {

        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>51060,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        if($request['product_id'])
        {
            $param['from_date'] = $request['from_date'];
            $param['to_date'] = $request['to_date'];

            $product = ProductMO::query()->where('company_id',$this->company_id)
                ->where('id',$request['product_id'])->first();

            $report = ProductHistory::query()->where('company_id',$this->company_id)
                ->where('product_id',$request['product_id'])->get();

//            $stats = ProductHistory::query()->where('product_id',$request['product_id'])
//                ->sum(function ($row) {
//                return $row->quantity_in - $row->quantity_out;
//            });

            dd($report->sum('balance'));



            return view('inventory.product.report.product-ledger-index',compact('product','param'));
        }


        return view('inventory.product.report.product-ledger-index');
    }

    public function autocomplete(Request $request)
    {
        $term = $request['term'];

        $items = ProductMO::query()->select('id as item_id', 'name','tax_id','unit_price','unit_name')
            ->where('company_id',$this->company_id)
            ->where('name', 'LIKE', '%'.$term.'%')->get();

        return response()->json($items);
    }
}
