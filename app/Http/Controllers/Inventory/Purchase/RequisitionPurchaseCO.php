<?php

namespace App\Http\Controllers\Inventory\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Company\Relationship;
use App\Models\Company\TransCode;
use App\Models\Inventory\Movement\Purchase;
use App\Models\Inventory\Movement\Requisition;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ItemTax;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class RequisitionPurchaseCO extends Controller
{
    use TransactionsTrait;

    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>53015,'user_id'=>$this->user_id],
            ['updated_at'=>Carbon::now()
            ]);

        return view('inventory.purchase.requisition-purchase-index');
    }

    public function getReqPurchaseData()
    {
        $query = Requisition::query()->where('company_id',$this->company_id)
            ->where('status',2)
            ->with(['items'=>function($q){
                $q->where('company_id',$this->company_id);
            }])
            ->with('user')->select('requisitions.*');

        return Datatables::eloquent($query)
            ->addColumn('product', function (Requisition $requisition) {
                return $requisition->items->map(function($items) {
                    return '<span style="color: #a71d2a; font-weight: bold">'. $items->item->name .'</span>';
                })->implode('<br>');
            })

            ->addColumn('quantity', function (Requisition $requisition) {
                return $requisition->items->map(function($items) {
                    return $items->quantity .' :: '.$items->purchased;
                })->implode('<br>');
            })

            ->addColumn('req_for', function (Requisition $requisition) {
                return $requisition->items->map(function($items) {
                    return $items->location->name;
                })->implode('<br>');
            })

            ->editColumn('req_type',function ($requisition) { return $requisition->req_type == 'P' ? 'Purchase' : 'Consumption';})
            ->addColumn('action', function ($requisition) {

                $type = $requisition->req_type == 'P' ? 'Purchase' : 'Consumption';

                return '
                    <button  data-remote="purchaseIndex/' . $requisition->id . '" type="button" class="btn btn-purchase btn-xs btn-primary"></i>Purchase</button>
                    ';
            })
            ->rawColumns(['product','quantity','req_type','req_for','action'])
            ->make(true);
    }

    public function purchase($id)
    {
        $suppliers = Relationship::query()->where('company_id',$this->company_id)
            ->whereIn('relation_type',['LS','SP'])
            ->orderBy('name')
            ->pluck('name','id');


        $taxes = ItemTax::query()->where('company_id',$this->company_id)
            ->orderBy('name')
            ->pluck('name','id');


        $requisitions = Requisition::query()->where('id',$id)->with('items')->first();

//        dd($requisitions)
        return view('inventory.purchase.requisition-purchase-submit',compact('requisitions','suppliers','taxes'));
    }

    public function itemSum(Request $request)
    {
        $input_items = $request['item'];

//        $zero = $numberFormatter->getSymbol(\NumberFormatter::ZERO_DIGIT_SYMBOL);


        $json = new \stdClass;

        $sub_total = 0;
        $tax_total = 0;
        $item_tax_total = 0;

        $items = array();
        $taxes = array();

        if ($input_items) {
            foreach ($input_items as $key => $item) {
                $item_tax_total= 0;
                $item_sub_total = ($item['price'] * $item['quantity']); //Money function gets last two digit as decimal

                if (!empty($item['tax'])) {
                    $tax = ItemTax::query()->where('id', $item['tax'])->first();


                    if($tax->calculating_mode == 'P')
                    {
                        $item_tax_total = (($item['price'] * $item['quantity']) / 100) * $tax->rate;
                    }

                    if($tax->calculating_mode == 'F')
                    {
                        $item_tax_total = $item['quantity']*$tax->rate;
                    }

                }
//
                $sub_total += $item_sub_total;
                $tax_total += $item_tax_total;

                $total = $item_sub_total + $item_tax_total;

                $items[$key] = $total;
                $taxes[$key] = $item_tax_total;

            }
        }

        $json->items = $items;
        $json->taxes = $taxes;
        $json->sub_total = $sub_total;
        $json->tax_total = $tax_total;

        $grand_total = $sub_total + $tax_total;

        $json->grand_total = $grand_total;

        return response()->json($json);
    }

    public function reqPurchaseStore(Request $request)
    {

        DB::beginTransaction();

        try{

            $fiscal_year = $this->get_fiscal_year($request['pi_date'],$this->company_id);

            $tr_code =  TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','PR')
                ->where('fiscal_year',$fiscal_year)
                ->lockForUpdate()->first();

            $pur_no = $tr_code->last_trans_id;

            $request['company_id'] = $this->company_id;
            $request['ref_no'] = $pur_no;
            $request['contra_ref'] = $request['req_no'];
            $request['invoice_date'] = Carbon::createFromFormat('d-m-Y', $request['pi_date'])->format('Y-m-d');
            $request['due_amt'] = $request['invoice_amt'] - $request['paid_amt'] - $request['discount'];
            $request['discount_amt'] = $request['discount'];
            $request['po_date'] = Carbon::createFromFormat('d-m-Y', $request['pi_date'])->format('Y-m-d');
            $request['purchase_type'] = 'LS';
            $request['status'] = 'CR';
            $request['user_id'] = $this->user_id;

            $requisitions = TransProduct::query()->where('company_id',$this->company_id)
                ->where('ref_no',$request['req_no'])->get();

            $rest = 0; // variable to calculate requisition quantity - purchased quantity


//            dd('here');

            $inserted = Purchase::query()->create($request->all()); //Insert Data Into Requisition Table

            if ($request['item']) {
                foreach ($request['item'] as $item) {
                    if ($item['quantity'] > 0)
                    {
                    $purchase_item['company_id'] = $this->company_id;
                    $purchase_item['ref_no'] = $pur_no;
                    $purchase_item['ref_id'] = $inserted->id;
                    $purchase_item['ref_type'] = 'P'; //Purchase
                    $purchase_item['relationship_id'] = $item['supplier_id'];
                    $purchase_item['tr_date']= Carbon::createFromFormat('d-m-Y', $request['pi_date'])->format('Y-m-d');;
                    $purchase_item['product_id'] = $item['item_id'];
                    $purchase_item['quantity'] = $item['quantity'];
                    $purchase_item['purchased'] = $item['quantity'];
                    $purchase_item['unit_price'] = $item['price'];
                    $purchase_item['tax_id'] = $item['tax'];
                    $purchase_item['tax_total'] = $item['tax_amt'];
                    $purchase_item['total_price'] = $item['quantity']*$item['price'] + $item['tax_amt'];

                    TransProduct::query()->create($purchase_item);

                    $requisitions->where('product_id',$item['item_id'])->first()->increment('purchased',$item['quantity']);
                    $rest = $rest + $requisitions->where('product_id',$item['item_id'])->first()->quantity - $requisitions->where('product_id',$item['item_id'])->first()->purchased;
                    }
                }
            }

            TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','PR')
                ->where('fiscal_year',$fiscal_year)
                ->increment('last_trans_id');

            // Check quantity left to be purchased

            if($rest == 0)
            {
                Requisition::query()->where('company_id',$this->company_id)
                    ->where('ref_no',$request['req_no'])->update(['status'=>3]);
            }


        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error','Purchase Failed To Save '.$error);
        }

        DB::commit();

        return redirect()->action('Inventory\Purchase\RequisitionPurchaseCO@index')->with('success','Purchase Data Saved For Authorisation');

    }
}
