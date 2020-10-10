<?php

namespace App\Http\Controllers\Inventory\Sales;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Product\ProductMO;
use App\Models\Inventory\Product\SalesRateHistory;
use App\Traits\CompanyTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleItemsRateCO extends Controller
{
    use CompanyTrait;
    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>56004,'user_id'=>$this->user_id
            ],['updated_at'=>Carbon::now()
        ]);

        $products = ProductMO::query()->where('company_id',$this->company_id)
            ->where('category_id',$this->get_default_finished_foods_category_id($this->company_id))
            ->with(['rate'=> function ($query) {
                $query->where('status', 'C');
            }])->get();

//        dd($products);

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

    public function approveSalesRateIndex(Request $request)
    {
        $products = SalesRateHistory::query()
            ->where('company_id',$this->company_id)
            ->where('status','C')
            ->with('item')->get();

        return view('inventory.sales.approve-sales-rate-index',compact('products'));
    }

    public function approve(Request $request)
    {

            DB::beginTransaction();

            try {
                $rate = SalesRateHistory::query()->where('id',$request['row_id'])->first();
                $product = ProductMO::query()->where('id',$rate->product_id)->first();

                $current = SalesRateHistory::query()->where('product_id',$rate->product_id)
                    ->where('company_id',$this->company_id)
                    ->where('status','A')
                    ->update(['status'=>'H']); // Making History

                SalesRateHistory::query()->where('id',$request['row_id'])
                    ->where('company_id',$this->company_id)
                    ->update([
                    'start_date'=>Carbon::now(),
                    'status'=>'A', //Making Approved
                    'approve_id'=>$this->user_id,
                    'approve_date'=>Carbon::now()
                ]);

                ProductMO::query()->where('id',$product->id)
                    ->update([
                        'wholesale_price'=>$rate->wholesale_price,
                        'retail_price'=>$rate->retail_price,
                        'unit_price'=>$rate->wholesale_price
                    ]);

            }catch (\Exception $e)
            {
                DB::rollBack();
                $error = $e->getMessage();
                return response()->json(['error' => $error], 404);
            }
            DB::commit();

        return response()->json(['success' => 'Item New Rate Approved','product-name' => $product->name], 200);
    }

    public function reject(Request $request)
    {

        DB::beginTransaction();

        try {
            $rate = SalesRateHistory::query()->where('id',$request['row_id'])->first();
            $product = ProductMO::query()->where('id',$rate->product_id)->first();

            SalesRateHistory::query()->where('id',$request['row_id'])
                ->update([
                    'start_date'=>Carbon::now(),
                    'status'=>'R', // Making Reject
                    'approve_id'=>$this->user_id,
                    'approve_date'=>Carbon::now()
                ]);
        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }
        DB::commit();

        return response()->json(['success' => 'Item New Rate Rejected','product-name' => $product->name], 200);
    }


}
