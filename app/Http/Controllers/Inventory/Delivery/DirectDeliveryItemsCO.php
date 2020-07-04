<?php

namespace App\Http\Controllers\Inventory\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Ledger\CostCenter;
use App\Models\Common\UserActivity;
use App\Models\Company\TransCode;
use App\Models\Inventory\Movement\Delivery;
use App\Models\Inventory\Movement\Requisition;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ProductMO;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DirectDeliveryItemsCO extends Controller
{
    use TransactionsTrait;

    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>56001,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        $centers = CostCenter::query()->where('company_id',$this->company_id)->pluck('name','id');
        return view('inventory.delivery.direct-delivery-items-index',compact('centers'));
    }

    public function productList(Request $request)
    {
        $term = $request['term'];

        $items = ProductMO::query()->select('id as item_id', 'name','tax_id','unit_price','unit_name','on_hand')
            ->where('company_id',$this->company_id)
            ->where('name', 'LIKE', '%'.$term.'%')->get();

        return response()->json($items);
    }

    public function store(Request $request)
    {

        DB::beginTransaction();

        $product = ProductMO::query()->where('company_id',$this->company_id)->get();

        try{

            $fiscal_year = $this->get_fiscal_year($request['delivery_date'],$this->company_id);

            $tr_code =  TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','DC')
                ->where('fiscal_year',$fiscal_year)
                ->lockForUpdate()->first();

            $challan_no = $tr_code->last_trans_id;

            $data['company_id'] = $this->company_id;
            $data['challan_no'] = $challan_no;
            $data['ref_no'] = $request->filled('$request') ? $request['$request'] : int_random(1000000000,9999999999);
            $data['delivery_type'] = 'CM';
            $data['direct_delivery']= true;
            $data['description'] = $request['remarks'];
            $data['delivery_date'] = Carbon::now()->format('Y-m-d');
            $data['user_id'] = $this->user_id;
            $data['relationship_id']=$request['center_id'];
            $data['status'] = 'CR'; //Created

            $inserted = Delivery::query()->create($data); //Insert Data Into Deliveries Table

            if ($request['item']) {
                foreach ($request['item'] as $item) {
                    $delivery_item=[];
                    $delivery_item['company_id'] = $this->company_id;
                    $delivery_item['ref_no'] = $challan_no;
                    $delivery_item['ref_id'] = $inserted->id;
                    $delivery_item['ref_type'] = 'D'; //Requisition
                    $delivery_item['relationship_id'] = $request['center_id']; // Cost Center ID
                    $delivery_item['tr_date']= Carbon::now()->format('Y-m-d');
                    $delivery_item['product_id'] = $item['item_id'];
                    $delivery_item['name'] = $product->where('id',$item['item_id'])->first()->name;
                    $delivery_item['quantity'] = $item['quantity'];
                    $delivery_item['item_sid_no'] = $item['s_id'];

                    TransProduct::query()->create($delivery_item);
                }
            }

            TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','DC')
                ->where('fiscal_year',$fiscal_year)
                ->increment('last_trans_id');

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error','Delivery Failed To Save '.$error);
        }

        DB::commit();

        return redirect()->action('Inventory\Delivery\DirectDeliveryItemsCO@index')->with('success','Delivery Data Saved');

    }


}
