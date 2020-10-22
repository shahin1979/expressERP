<?php

namespace App\Http\Controllers\Inventory\Production;

use App\Http\Controllers\Controller;
use App\Models\Company\TransCode;
use App\Models\Inventory\Movement\Receive;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ProductMO;
use App\Traits\CommonTrait;
use App\Traits\CompanyTrait;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionFromFactoryCO extends Controller
{
    use CommonTrait, CompanyTrait, TransactionsTrait;

    public function index()
    {
        $this->menu_log($this->company_id,54010);

        $products = ProductMO::query()->where('status',true)
            ->where('category_id',$this->get_default_finished_goods_category_id($this->company_id))
            ->pluck('name','id');

        return view('inventory.production.production-from-factory-index',compact('products'));
    }

    public function store(Request $request)
    {
//        dd($request->all());

        DB::beginTransaction();
        try{

            $fiscal_year = $this->get_fiscal_data_from_current_date($this->company_id);
            $products = ProductMO::query()->where('company_id',$this->company_id)->where('status',true)->get();

            $tr_code =  TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','IR')
                ->where('fiscal_year',$fiscal_year->fiscal_year)
                ->lockForUpdate()->first();

            $receive_no = $tr_code->last_trans_id;

            $receive=[];

            $receive['company_id'] = $this->company_id;
            $receive['challan_no'] = $receive_no;
            $receive['ref_no'] = $receive_no;
            $receive['receive_date'] = Carbon::now()->format('Y-m-d');
            $receive['receive_type'] = 'PR';
            $receive['status'] = 'CR'; //Created
            $receive['user_id'] = $this->user_id;


            $inserted = Receive::query()->create($receive); //Insert Data Into Sales Table
            $new_receive_id = $inserted->id;

            if ($request['item']) {

                foreach ($request['item'] as $item) {
                    if ($item['quantity'] > 0)
                    {
//
                        $move['company_id'] = $this->company_id;
                        $move['ref_no'] = $receive_no;
                        $move['ref_id'] = $inserted->id;
                        $move['ref_type'] = 'F'; //From Factory
                        $move['relationship_id'] = 12; //Production Department
                        $move['tr_date']= $receive['receive_date'];
                        $move['product_id'] = $item['product_id'];
                        $move['name'] = $products->where('id',$item['product_id'])->first()->name;
                        $move['quantity'] = $item['quantity'];
                        $move['received'] = $item['quantity'];
                        $move['unit_price'] = $item['price'];
                        $move['total_price'] = $item['quantity'] * $item['price'];
                        $move['remarks'] = $item['remarks'];

                        TransProduct::query()->create($move);
//                        ProductMO::query()->where('id',$data->product_id)->increment('received_qty',$item['receive']);
                    }
                }
            }

            TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','IR')
                ->where('fiscal_year',$fiscal_year->fiscal_year)
                ->increment('last_trans_id');

            // End of Receive


        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error','Production Failed To Save '.$error);
        }

        DB::commit();

        return redirect()->action('Inventory\Production\ProductionFromFactoryCO@index')->with('success','Production submitted for approval');

    }
}
