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

        $contracts = ExportContract::query()->where('company_id',$this->company_id)
            ->where('status','DL')
            ->pluck('export_contract_no','id');

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
//        dd($request->all());

        DB::beginTransaction();
        try{

            $fiscal_year = $this->get_fiscal_year($request['invoice_date'],$this->company_id);
            $products = ProductMO::query()->where('company_id',$this->company_id)->where('status',true)->get();

            $challan = Delivery::query()->where('id',$request['challan_id'])
                ->with('items')
                ->first();

            $contract = ExportContract::query()->where('id',$request['contract_id'])->first();

            $tr_code =  TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','SL')
                ->where('fiscal_year',$fiscal_year)
                ->lockForUpdate()->first();

            $invoice_no = $tr_code->last_trans_id;

            $request['company_id'] = $this->company_id;
            $request['invoice_no'] = $invoice_no;
            $request['invoice_date'] = Carbon::createFromFormat('d-m-Y',$request['invoice_date'])->format('Y-m-d');
            $request['invoice_type'] = 'EX';
            $request['customer_id'] = $challan->relationship_id;
            $request['user_id'] = $this->user_id;
            $request['status'] = 'CR'; //Created
            $request['export_contract_id'] = $request['contract_id'];
            $request['fc_amt'] = $challan->items->sum('total_price');
            $request['currency'] = $contract->currency;
            $request['importer_bank_id'] = $request->has('importer_bank') ? $request['importer_bank'] : null;
            $request['exporter_bank_id'] = $request->has('exporter_bank') ? $request['exporter_bank'] : null;
            $request['color'] = $request->filled('color') ? 'COLOR: '.$request['color'] : null;
            $request['delivery_challan_id'] = $challan->id;

            $inserted = Sale::query()->create($request->all()); //Insert Data Into Sales Table

            if ($challan) {
                foreach ($challan->items as $item) {
                    if ($item->quantity > 0)
                    {
                        $sales_item['company_id'] = $this->company_id;
                        $sales_item['ref_no'] = $invoice_no;
                        $sales_item['ref_id'] = $inserted->id;
                        $sales_item['ref_type'] = 'S'; //Export Invoice
                        $sales_item['relationship_id'] = $item->relationship_id;
                        $sales_item['tr_date']= $request['invoice_date'];
                        $sales_item['product_id'] = $item->product_id;
                        $sales_item['name'] = $item->name;
                        $sales_item['quantity'] = $item->quantity;
                        $sales_item['sold'] = $item->quantity;
                        $sales_item['unit_price'] = $item->unit_price;
                        $sales_item['total_price'] = $item->total_price;

                        TransProduct::query()->create($sales_item);
                        ProductMO::query()->where('id',$item->product_id)->increment('committed',$item->quantity);
                    }

                    // Make the unique Ids as sold


                    ProductUniqueId::query()->where('company_id', $this->company_id)
                        ->where('product_id', $item->product_id)
                        ->where('delivery_ref_id', $challan->id)
                        ->update(
                            [
                                'sales_ref_id' => $inserted->id
                            ]);
                }
            }

            TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','SL')
                ->where('fiscal_year',$fiscal_year)
                ->increment('last_trans_id');

            ExportContract::query()->where('id',$contract->id)->update(['status'=>'IC']); // Make Status as Invoice Created

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error','Export Invoice Failed To Save '.$error);
        }

        DB::commit();

        return redirect()->action('Inventory\Export\ExportInvoiceCO@index')->with('success','Export Invoice Data Saved For Approval');

    }
}
