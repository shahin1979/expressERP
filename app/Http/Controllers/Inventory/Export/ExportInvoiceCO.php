<?php

namespace App\Http\Controllers\Inventory\Export;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Setup\Bank;
use App\Models\Company\TransCode;
use App\Models\Inventory\Export\ExportContract;
use App\Models\Inventory\Movement\Delivery;
use App\Models\Inventory\Movement\Sale;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ProductMO;
use App\Models\Inventory\Product\ProductUniqueId;
use App\Traits\CommonTrait;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExportInvoiceCO extends Controller
{
    use CommonTrait, TransactionsTrait;

    public function index(Request $request)
    {
        $this->menu_log($this->company_id,57020);

        $contracts = ExportContract::query()->where('company_id',$this->company_id)->pluck('export_contract_no','id');
        if(!empty($request['contract_id']))
        {
            $contract = ExportContract::query()->where('company_id',$this->company_id)
                ->where('id',$request['contract_id'])->with('items')->first();

//            $products = TransProduct::query()->where('company_id',$this->company_id)
//                ->where('ref_id',$contract->id)->where('ref_type','E')
//                ->get();

            $products = Delivery::query()->where('company_id',$this->company_id)
                ->where('delivery_type','EX')
                ->where('ref_no',$contract->invoice_no)
                ->with('items')->with('contract')->first();

            $imp_banks = Bank::query()->where('company_id',$this->company_id)
                ->where('bank_type','I')
                ->select(DB::raw("CONCAT(bank_name,' : ', branch_name) AS display_name"),'id')
                ->pluck('display_name','id');


            $exp_banks = Bank::query()->where('company_id',$this->company_id)
                ->where('bank_type','M')
                ->select(DB::raw("CONCAT(bank_name,' : ', branch_name) AS display_name"),'id')
                ->pluck('display_name','id');

            $fiscal = $this->get_fiscal_data_from_current_date($this->company_id);

            $tr_code =  TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','SL')
                ->where('fiscal_year',$fiscal->fiscal_year)->first();


            $invoice_no = $tr_code->last_trans_id;

//            dd($exp_banks);
            return view('inventory.export.index.export-invoice-index',compact('products','contract','imp_banks','exp_banks','invoice_no'));
        }

        return view('inventory.export.index.export-invoice-index',compact('contracts'));
    }

    public function store(Request $request)
    {
        dd($request->all());

        DB::beginTransaction();
        try{

            $fiscal_year = $this->get_fiscal_year($request['invoice_date'],$this->company_id);
            $products = ProductMO::query()->where('company_id',$this->company_id)->where('status',true)->get();

            $tr_code =  TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','SL')
                ->where('fiscal_year',$fiscal_year)
                ->lockForUpdate()->first();

            $invoice_no = $tr_code->last_trans_id;

            $request['company_id'] = $this->company_id;
            $request['invoice_no'] = $invoice_no;
            $request['invoice_date'] = Carbon::createFromFormat('d-m-Y',$request['invoice_date'])->format('Y-m-d');
            $request['due_amt'] = $request['invoice_amt'] - $request['discount_amt'] - $request['paid_amt'];
            $request['invoice_type'] = $request['customer_id'] == 1 ? 'CA' : 'CR';
//            $request['customer_id']
//            $request['paid_amt'] =
//            $request['discount_amt'] =
            $request['user_id'] = $this->user_id;
            $request['status'] = 'CR'; //Created

            $inserted = Sale::query()->create($request->all()); //Insert Data Into Sales Table

            if ($request['item']) {
                foreach ($request['item'] as $item) {
                    if ($item['quantity'] > 0)
                    {
                        $sales_item['company_id'] = $this->company_id;
                        $sales_item['ref_no'] = $invoice_no;
                        $sales_item['ref_id'] = $inserted->id;
                        $sales_item['ref_type'] = 'S'; //Sales
                        $sales_item['relationship_id'] = $request['customer_id'];
                        $sales_item['tr_date']= $request['invoice_date'];
                        $sales_item['product_id'] = $item['item_id'];
                        $sales_item['name'] = $products->where('id',$item['item_id'])->first()->name;
                        $sales_item['quantity'] = $item['quantity'];
                        $sales_item['sold'] = $item['quantity'];
                        $sales_item['unit_price'] = $item['price'];
                        $sales_item['tax_id'] = $item['tax'];
                        $sales_item['tax_total'] = $item['tax_amt'];
                        $sales_item['total_price'] = $item['quantity']*$item['price'] + $item['tax_amt'];

                        TransProduct::query()->create($sales_item);
                        ProductMO::query()->where('id',$item['item_id'])->increment('committed',$item['quantity']);


                    }

                    // Make the unique Ids as sold

                    if ($this->productsAreCached()) {

                        $unique_ids = $this->getCachedProducts();
                        $this->emptyCache();

                        foreach ($unique_ids as $key=>$unique) {
                            foreach ($unique as $id)

                                ProductUniqueId::query()->where('company_id', $this->company_id)
                                    ->where('product_id', $item['item_id'])
                                    ->where('unique_id', $id['unique_id'])
                                    ->update(
                                        [
                                            'sales_ref_id' => $inserted->id,
                                            'stock_status' => false,
                                            'status' => 'S']);
                        }
                    }
                }
            }

            TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','SL')
                ->where('fiscal_year',$fiscal_year)
                ->increment('last_trans_id');


        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error','Invoice Failed To Save '.$error);
        }

        DB::commit();

        return redirect()->action('Inventory\Sales\SaleInvoiceCO@index')->with('success','Invoice Data Saved For Approval');

    }
}
