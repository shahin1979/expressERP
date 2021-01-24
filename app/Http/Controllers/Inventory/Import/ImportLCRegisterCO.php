<?php

namespace App\Http\Controllers\Inventory\Import;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Setup\Bank;
use App\Models\Common\Country;
use App\Models\Company\CompanyProperty;
use App\Models\Company\Relationship;
use App\Models\Company\TransCode;
use App\Models\Inventory\Import\ImportLCRegister;
use App\Models\Inventory\Movement\Purchase;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ItemTax;
use App\Models\Inventory\Product\ProductMO;
use App\Models\Inventory\Product\ProductUniqueId;
use App\Traits\CommonTrait;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportLCRegisterCO extends Controller
{
    use TransactionsTrait, CommonTrait;

    public function index()
    {
        $this->menu_log($this->company_id,51105);

        $suppliers = Relationship::query()->pluck('name','id');
        $currencies = Country::query()->whereNotIn('currency_short',['USD'])
            ->orderBy('currency_short')
            ->pluck('currency_short','currency_short')
            ->prepend('USD : Us Dollar','USD');

        $banks = Bank::query()->where('bank_type','C')->pluck('bank_name','id');
        $e_banks = Bank::query()->where('bank_type','E')->pluck('bank_name','id');

        return view('inventory.import.import-register-index',compact('suppliers','currencies','banks'));
    }

    public function autocomplete(Request $request)
    {
        $term = $request['term'];
        $company = CompanyProperty::query()->where('company_id',$this->company_id)->first();

        $items = ProductMO::query()->select('id as item_id', 'name','unit_price','unit_name','tax_id')
            ->where('company_id',$this->company_id)
            ->where('category_id',$company->rm_cg_id)
            ->where('name', 'LIKE', '%'.$term.'%')->get();

        return response()->json($items);
    }

    public function totalItem(Request $request)
    {
        $input_items = $request['item'];
        $json = new \stdClass;

        $sub_total = 0;
        $tax_total = 0;
        $item_tax_total = 0;

        $items = array();
        $taxes = array();

        if ($input_items) {
            foreach ($input_items as $key => $item) {
                $item_tax_total= 0;
                $item_sub_total = ($item['price'] * $item['pound']); //Money function gets last two digit as decimal


                if (!empty($item['tax'])) {

                    $tax = ItemTax::query()->where('id', $item['tax'])->first();
                    if($tax->calculating_mode == 'P')
                    {
                        $item_tax_total = (($item['price'] * $item['pound']) / 100) * $tax->rate;
                    }

                    if($tax->calculating_mode == 'F')
                    {
                        $item_tax_total = $item['pound']*$tax->rate;
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

    public function store(Request $request)
    {
        DB::beginTransaction();

        try{
            $fiscal_year = $this->get_fiscal_data_from_current_date($this->company_id);

            $tr_code =  TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','PR')
                ->where('fiscal_year',$fiscal_year->fiscal_year)
                ->lockForUpdate()->first();

            $pur_no = $tr_code->last_trans_id;

            $request['company_id'] = $this->company_id;
//            $request['import_lc_no'] = $pur_no;
            $request['invoice_no']=$pur_no;
            $request['lc_date'] = Carbon::createFromFormat('d-m-Y', $request['lc_date'])->format('Y-m-d');
            $request['open_date'] = Carbon::now();
            $request['contract_amt'] = $request['fc_amount'];
            $request['discount_amt'] = $request['discount'];
            $request['bdt_amt'] = $request['bdt_amount'];
//            $request['purchase_type'] = $request['relationship_id'] == 0 ?  'CP' : 'LP';
            $request['status'] = 'CR';
            $request['user_id'] = $this->user_id;

            $inserted = ImportLCRegister::query()->create($request->all()); //Insert Data Into Requisition Table

//            ProductUniqueId::query()->where('company_id',$this->company_id)
//                ->where('user_id',$this->user_id)
//                ->where('temp_id',$request['temp_ref_no'])
//                ->update(['purchase_ref_id'=>$inserted->id,'temp_id'=>null,
//                    'stock_status'=>false,
//                    'data_validity'=>true]);

            if ($request['item']) {
                foreach ($request['item'] as $item) {
                    if ($item['pound'] > 0)
                    {
                        $json = ['pound' => $item['pound'], 'kilogram' => $item['kilogram'], 'bale' => $item['bale']];

//                        dd($json);

                        $purchase_item['company_id'] = $this->company_id;
                        $purchase_item['ref_no'] = $pur_no;
                        $purchase_item['ref_id'] = $inserted->id;
                        $purchase_item['ref_type'] = 'P'; //Purchase
                        $purchase_item['relationship_id'] = $request['supplier_id'];
                        $purchase_item['tr_date']= $request['open_date'];
                        $purchase_item['product_id'] = $item['item_id'];
                        $purchase_item['quantity'] = $item['pound'];
                        $purchase_item['multi_unit'] = json_encode($json,true) ;
                        $purchase_item['purchased'] = $item['pound'];
                        $purchase_item['unit_price'] = $item['price'];
//                        $purchase_item['tax_id'] = $item['tax'];
//                        $purchase_item['tax_total'] = $item['tax_amt'];
                        $purchase_item['total_price'] = $item['pound']*$item['price'];

                        TransProduct::query()->create($purchase_item);
                    }
                }
            }

            TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','PR')
                ->where('fiscal_year',$fiscal_year->fiscal_year)
                ->increment('last_trans_id');

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error','Import LC Failed To Save '.$error);
        }

        DB::commit();

        return redirect()->action('Inventory\Import\ImportLCRegisterCO@index')->with('success','Import Data Saved For Authorisation');
    }
}
