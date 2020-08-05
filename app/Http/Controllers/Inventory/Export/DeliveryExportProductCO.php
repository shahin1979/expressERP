<?php

namespace App\Http\Controllers\Inventory\Export;

use App\Http\Controllers\Controller;
use App\Models\Company\TransCode;
use App\Models\Inventory\Export\ExportContract;
use App\Models\Inventory\Movement\Delivery;
use App\Models\Inventory\Movement\ProductHistory;
use App\Models\Inventory\Movement\TransProduct;
use App\Models\Inventory\Product\ProductUniqueId;
use App\Traits\CommonTrait;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class DeliveryExportProductCO extends Controller
{
    use CommonTrait, TransactionsTrait;

    public function index(Request $request)
    {
        $this->menu_log($this->company_id,57015);

        $contracts = ExportContract::query()->where('company_id',$this->company_id)
            ->where('status','AP')->pluck('export_contract_no','id');

        if(!empty($request['contract_id']))
        {
            $data = ExportContract::query()
                ->where('company_id',$this->company_id)
                ->where('id',$request['contract_id'])
                ->with('items')->first();

            $challan = Delivery::query()->where('company_id',$this->company_id)
                ->where('delivery_type','EX')
                ->where('ref_no',$data->invoice_no)->first();


            return view('inventory.export.index.delivery-export-product-index',compact('contracts','data','challan'));
        }

        return view('inventory.export.index.delivery-export-product-index',compact('contracts'));
    }

    public function getExportProdDT($id)
    {

        $contract = ExportContract::query()->where('id',$id)->first();

        $challan = Delivery::query()->where('company_id',$this->company_id)
            ->where('delivery_type','EX')
            ->where('ref_no',$contract->invoice_no)->first();


        $delivered = TransProduct::query()->where('company_id',$this->company_id)
            ->where('ref_type','D')
            ->where('ref_id',isset($challan) ? $challan->id : 99)->get();



        $invoice_quantity = TransProduct::query()->where('company_id',$this->company_id)
            ->where('ref_type','E')
            ->where('ref_no', isset($challan) ? $challan->ref_no : null)->sum('quantity');

        $balance = $invoice_quantity - $delivered->sum('quantity') ;


        $query = ProductUniqueId::query()->where('product_unique_ids.company_id',$this->company_id)
            ->where('product_unique_ids.status','D')
            ->where('delivery_ref_id',isset($challan) ? $challan->id : 99)
            ->with('history')->with('item')
            ->select('product_unique_ids.*');

        return DataTables::of($query)
            ->addColumn('action', function ($query) {
                return '
                <button data-remote="items/delete/'. $query->id . '" type="button" class="btn btn-delete btn-xs btn-danger pull-right" ><i class="fa fa-remove"></i>Del</button>
                ';
            })
            ->with('delivered',number_format($delivered->sum('quantity'),2))
            ->with('balance',number_format($balance,2))
            ->make(true);

    }



    public function getExportProductsDT($id)
    {

        $contract = ExportContract::query()->where('id',$id)->first();

        $challan = Delivery::query()->where('company_id',$this->company_id)
            ->where('delivery_type','EX')
            ->where('ref_no',$contract->invoice_no)->first();


        $delivered = TransProduct::query()->where('company_id',$this->company_id)
            ->where('ref_type','D')
            ->where('ref_id',isset($challan) ? $challan->id : 99)->with('item');



//        $invoice_quantity = TransProduct::query()->where('company_id',$this->company_id)
//            ->where('ref_type','E')
//            ->where('ref_no', isset($challan) ? $challan->ref_no : null)->sum('quantity');
//
//        $balance = $invoice_quantity - $delivered->sum('quantity') ;
//
//
//        $query = ProductUniqueId::query()->where('product_unique_ids.company_id',$this->company_id)
//            ->where('product_unique_ids.status','D')
//            ->where('delivery_ref_id',isset($challan) ? $challan->id : 99)
//            ->with('history')->with('item')
//            ->select('product_unique_ids.*');

        return DataTables::of($delivered)
            ->make(true);

    }


    public function store(Request $request)
    {

//        dd($request->all());

        DB::beginTransaction();

        try {

            $contract = ExportContract::query()->where('id',$request['contract_id'])->with('items')->first();

            if($request['challan_no'] == 99) // default value
            {
                $year = $this->get_fiscal_data_from_current_date($this->company_id);

                $tr_code =  TransCode::query()->where('company_id',$this->company_id)
                    ->where('trans_code','DC')
                    ->where('fiscal_year',$year->fiscal_year)
                    ->lockForUpdate()->first();

                $challan_no = $tr_code->last_trans_id;

                $data['company_id'] = $this->company_id;
                $data['challan_no'] = $challan_no;
                $data['ref_no'] = $contract->invoice_no;
                $data['delivery_type'] = 'EX';
                $data['direct_delivery']= false;
                $data['description'] = 'Export Delivery';
                $data['delivery_date'] = Carbon::now()->format('Y-m-d');
                $data['user_id'] = $this->user_id;
                $data['relationship_id'] = $contract->customer_id;
                $data['status'] = 'CR'; //Created

                $inserted = Delivery::query()->create($data); //Insert Data Into Deliveries Table
                $request['challan_no'] = $inserted->challan_no;

                TransCode::query()->where('company_id',$this->company_id)
                    ->where('trans_code','DC')
                    ->where('fiscal_year',$year->fiscal_year)
                    ->increment('last_trans_id');

                foreach ($contract->items as $item) {
                    $delivery_item=[];
                    $delivery_item['company_id'] = $this->company_id;
                    $delivery_item['ref_no'] = $challan_no;
                    $delivery_item['ref_id'] = $inserted->id;
                    $delivery_item['ref_type'] = 'D'; //Export Delivery
                    $delivery_item['relationship_id'] = $contract->customer_id; // Importer ID
                    $delivery_item['tr_date']= Carbon::now()->format('Y-m-d');
                    $delivery_item['product_id'] = $item->product_id;
                    $delivery_item['name'] = $item->item->name;
                    $delivery_item['quantity'] = 0;
                    $delivery_item['unit_price'] = $item->unit_price;
                    $delivery_item['total_price'] = $item->total_price;
                    $delivery_item['remarks'] = $request['Temp Export Delivery'];

                    TransProduct::query()->create($delivery_item);
                }
            }

            $challan = Delivery::query()->where('company_id',$this->company_id)
                ->where('delivery_type','EX')
                ->where('challan_no',$request['challan_no'])->first();

            switch($request['d_mode'])
            {
                case 'B' :

                    $product = ProductHistory::query()->where('company_id', $this->company_id)
                        ->where('bale_no', $request['bale_no'])
                        ->where('stock_out',false)
                        ->where('ref_type', 'F')
                        ->with('item')
                        ->first();

                    if ($product) {
                        TransProduct::query()->where('company_id', $this->company_id)
                            ->where('ref_id', $challan->id)
                            ->where('ref_no', $challan->challan_no)
                            ->where('ref_type', 'D')
                            ->where('product_id', $product->product_id)
                            ->increment('quantity', $product->quantity_in);

                        ProductHistory::query()->where('company_id',$this->company_id)
                            ->where('id',$product->id)
                            ->update(['stock_out'=>true,'vehicle_no'=>$request['vehicle_no']]);

                        ProductUniqueId::query()->where('history_ref_id',$product->id)
                            ->update(['delivery_ref_id'=>$challan->id,'stock_status'=>false,'status'=>'D']);

                    }else
                    {
                        return response()->json(['error' => 'Bale Not Available for Delivery'], 400);
                    }
                    break;

                case 'L':

                    $products = ProductHistory::query()->where('company_id', $this->company_id)
                        ->where('lot_no',$request['bale_no'])
                        ->where('stock_out',false)
                        ->where('ref_type', 'F')
                        ->with('item')
                        ->get();

                    if(!empty($products))
                    {
//                        $temp = TransProduct::query()->where('company_id', $this->company_id)
//                            ->where('ref_no',$contract->invoice_no)
//                            ->where('ref_type','D')
//                            ->get();

                        foreach ($products as $row)
                        {
//                            if(!$temp->contains('bale_no',$row->bale_no))
//                            {

                                TransProduct::query()->where('company_id', $this->company_id)
                                    ->where('ref_id', $challan->id)
                                    ->where('ref_no', $challan->challan_no)
                                    ->where('ref_type', 'D')
                                    ->where('product_id', $row->product_id)
                                    ->increment('quantity', $row->quantity_in);

                                ProductHistory::query()->where('company_id',$this->company_id)
                                    ->where('id',$row->id)
                                    ->update(['stock_out'=>true,'vehicle_no'=>$request['vehicle_no']]);

                                ProductUniqueId::query()->where('history_ref_id',$row->id)
                                    ->update(['delivery_ref_id'=>$challan->id,'stock_status'=>false,'status'=>'D']);


//                                $delivery_item=[];
//                                $delivery_item['company_id'] = $this->company_id;
//                                $delivery_item['ref_no'] = $challan->challan_no;
//                                $delivery_item['ref_id'] = $challan->id;
//                                $delivery_item['ref_type'] = 'D'; //Export Delivery
//                                $delivery_item['relationship_id'] = $contract->customer_id; // Importer ID
//                                $delivery_item['tr_date']= Carbon::now()->format('Y-m-d');
//                                $delivery_item['product_id'] = $row->product_id;
//                                $delivery_item['name'] = $row->item->name;
//                                $delivery_item['quantity'] = $row->quantity_in;
//                                $delivery_item['bale_no'] = $row->bale_no;
//                                $delivery_item['lot_no'] = $row->lot_no;
//                                $delivery_item['tr_weight'] = $row->tr_weight;
//                                $delivery_item['gross_weight'] = $row->gross_weight;
//                                $delivery_item['vehicle_no'] = $request['vehicle_no'];
//                                $delivery_item['remarks'] = $request['Export Delivery'];
//
//                                TransProduct::query()->create($delivery_item);
//
//                                ProductHistory::query()->where('company_id',$this->company_id)
//                                    ->where('product_id',$row->product_id)
//                                    ->where('bale_no',$row->bale_no)
//                                    ->where('ref_type', 'F')->update(['stock_out'=>true]);

//                            }
                        }
                    }else
                    {
                        return response()->json(['error' => 'Lot Not Available for Delivery'], 400);
                    }
                    break;

                default:
                    return response()->json(['error' => 'Please Select By Bale or By Lot'], 400);
            }

        }catch(\Exception $e)
        {
            DB::rollback();
            return response()->json(['error' => $e->getMessage(),], 400);
        }

        DB::commit();
        return response()->json(['success'=>$request['bale_no'].' is in the delivery queue','challan_no'=>$request['challan_no']],200);
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {

            $uniqueID = ProductUniqueId::query()->where('id',$id)->first();
            $history = ProductHistory::query()->where('id',$uniqueID->history_ref_id)->first();
            $delivery = Delivery::query()->where('id',$uniqueID->delivery_ref_id)->first();

            ProductHistory::query()->where('id',$uniqueID->history_ref_id)
                ->update(['stock_out'=>false,'vehicle_no'=>null]);

            ProductUniqueId::query()->where('id',$id)->update(['delivery_ref_id'=>null,'stock_status'=>true,'status'=>'R']);

//            dd($history);

            TransProduct::query()->where('company_id',$this->company_id)
                ->where('ref_id',$delivery->id)
                ->where('ref_type','D')
                ->where('product_id',$history->product_id)
                ->decrement('quantity',$history->quantity_in);

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();
        return response()->json(['success'=>'Successfully Deleted'],200);
    }
}
