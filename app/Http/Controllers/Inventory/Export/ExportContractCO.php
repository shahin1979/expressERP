<?php

namespace App\Http\Controllers\Inventory\Export;

use App\Http\Controllers\Controller;
use App\Models\Common\Country;
use App\Models\Company\CompanyProperty;
use App\Models\Company\Relationship;
use App\Models\Company\TransCode;
use App\Models\Inventory\Export\ExportContract;
use App\Models\Inventory\Movement\Sale;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ProductMO;
use App\Models\Inventory\Product\ProductUniqueId;
use App\Traits\CommonTrait;
use App\Traits\CompanyTrait;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExportContractCO extends Controller
{
    use TransactionsTrait, CompanyTrait, CommonTrait;

    public function index()
    {
        $this->menu_log($this->company_id,70005);

        $customers = Relationship::query()->where('company_id',$this->company_id)
            ->whereIn('relation_type',['CS','SP'])
            ->whereNotIn('ledger_acc_no',['10112102'])
            ->orderBy('name')
            ->pluck('name','id');

        $currencies = Country::query()->whereNotIn('currency_short',['USD'])
            ->orderBy('currency_short')
            ->pluck('currency_short','currency_short')
            ->prepend('USD : Us Dollar','USD');

        return view('inventory.export.index.export-contract-index',compact('currencies','customers'));
    }

    public function autocomplete(Request $request)
    {
        $term = $request['term'];
        $company = CompanyProperty::query()->where('company_id',$this->company_id)->first();

        $items = ProductMO::query()->select('id as item_id', 'name','unit_price','unit_name','tax_id')
            ->where('company_id',$this->company_id)
            ->where('category_id',$company->fg_cg_id)
            ->where('name', 'LIKE', '%'.$term.'%')->get();

        return response()->json($items);
    }

    public function store(Request $request)
    {

        DB::beginTransaction();
        try{

            $company = CompanyProperty::query()->where('company_id',$this->company_id)->first();
            $year = $this->get_fiscal_data_from_current_date($this->company_id);
            $products = ProductMO::query()->where('company_id',$this->company_id)
                ->where('category_id',$company->fg_cg_id)
                ->where('status',true)->get();

            $tr_code =  TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','SL')
                ->where('fiscal_year',$year->fiscal_year)
                ->lockForUpdate()->first();

            $invoice_no = $tr_code->last_trans_id;

            $request['company_id'] = $this->company_id;
            $request['invoice_no'] = $invoice_no;
            $request['contract_date'] = Carbon::createFromFormat('d-m-Y',$request['contract_date'])->format('Y-m-d');
            $request['signing_date'] = Carbon::createFromFormat('d-m-Y',$request['signing_date'])->format('Y-m-d');
            $request['expiry_date'] = Carbon::createFromFormat('d-m-Y',$request['expiry_date'])->format('Y-m-d');
            $request['user_id'] = $this->user_id;
            $request['status'] = 'CR'; //Created

            $inserted = ExportContract::query()->create($request->all()); //Insert Data Into Sales Table

            if ($request['item']) {
                foreach ($request['item'] as $item) {
                    if ($item['quantity'] > 0)
                    {
                        $export_item['company_id'] = $this->company_id;
                        $export_item['ref_no'] = $invoice_no;
                        $export_item['ref_id'] = $inserted->id;
                        $export_item['ref_type'] = 'E'; //Export
                        $export_item['relationship_id'] = $request['customer_id'];
                        $export_item['tr_date']= Carbon::now()->format('Y-m-d');
                        $export_item['product_id'] = $item['item_id'];
                        $export_item['name'] = $products->where('id',$item['item_id'])->first()->name;
                        $export_item['quantity'] = $item['quantity'];
                        $export_item['unit_price'] = $item['price'];
                        $export_item['total_price'] = $item['quantity']*$item['price'];

                        TransProduct::query()->create($export_item);
                    }
                }
            }

            TransCode::query()->where('company_id',$this->company_id)
                ->where('trans_code','SL')
                ->where('fiscal_year',$year->fiscal_year)
                ->increment('last_trans_id');


        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error','Contract Failed To Save '.$error);
        }

        DB::commit();

        return redirect()->action('Inventory\Export\ExportContractCO@index')->with('success','Contract Data Saved For Approval');

    }
}
