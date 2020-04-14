<?php

namespace App\Http\Controllers\Inventory\Sales;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Product\ProductMO;
use App\Models\Inventory\Product\SalesRateHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleItemsRateCO extends Controller
{
    public function index()
    {
        $products = ProductMO::query()->where('company_id',$this->company_id)
            ->where('category_id',3)->get();

        return view('inventory.sales.sales-item-rate-index',compact('products'));
    }

    public function update(Request $request)
    {
        if($request['wholesale'] + $request['retail'] > 0)
        {
            DB::beginTransaction();

            try {
                $product = ProductMO::query()->where('id',$request['row_id'])->first();
                SalesRateHistory::query()->insert([
                    'company_id'=>$this->company_id,
                    'product_id'=>$product->id,
                    'start_date'=>Carbon::now(),
                    'wholesale_price' =>$request['wholesale'],
                    'retail_price'=>$request['retail'],
                    'status'=>'C',
                    'user_id'=>$this->user_id
                ]);
            }catch (\Exception $e)
            {
                DB::rollBack();
                $error = $e->getMessage();
                return response()->json(['error' => $error], 404);
            }
            DB::commit();
        }else{
            return response()->json(['error' => 'Amount not permitted'], 404);
        }

        return response()->json(['success' => 'Item New Rate Inserted For Approval','product-name' => $product->name], 200);
    }
}
