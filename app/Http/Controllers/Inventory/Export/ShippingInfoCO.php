<?php

namespace App\Http\Controllers\Inventory\Export;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Export\ExportShippingFileUpload;
use App\Models\Inventory\Export\ExportShippingInfo;
use App\Models\Inventory\Movement\Delivery;
use App\Models\Inventory\Movement\ProductHistory;
use App\Models\Inventory\Movement\Sale;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class ShippingInfoCO extends Controller
{
    use CommonTrait;

    public function index(Request $request)
    {
        $this->menu_log($this->company_id,57045);

        $selections = Delivery::query()->where('company_id',$this->company_id)
            ->where('delivery_type','EX')
            ->pluck('challan_no','id');

        if($request->has('action'))
        {
            $delivery = Delivery::query()->where('company_id', $this->company_id)
                ->where('id', $request['challan_id'])->first();

            $products = ProductHistory::query()->where('company_id',$this->company_id)
                ->whereHas('serial',function ($query) use($delivery){
                    $query->where('delivery_ref_id',$delivery->id);
                })
                ->selectRaw('lot_no, vehicle_no, container, seal_no, cbm, count(bale_no) as bale_count, sum(quantity_in) as net_weight, sum(gross_weight) as gross_weight')
                ->groupBy('lot_no', 'vehicle_no', 'container', 'seal_no', 'cbm')
                ->get();

            $containers = ExportShippingFileUpload::query()->where('challan_id',$request['challan_id'])
                ->pluck('container','container');

            return view('inventory.export.index.update-shipment-info-index', compact('delivery','products','containers'));
        }

        return view('inventory.export.index.update-shipment-info-index',compact('selections'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $date = Carbon::createFromFormat('d-m-Y',$request['shipping_date'])->format('Y-m-d');

            Delivery::query()->where('company_id',$this->company_id)
                ->where('id',$request['challan_id'])
                ->update([
                    'shipping_date'=>$date,
                    'shipping_mark'=>$request['shipping_mark'],
                    'vessel_no'=>$request['vessel_no'],
                    'shipping_ref'=>$request['shipping_ref'],
                    'packing'=>$request['packing'],
                    ]);

            Sale::query()->where('company_id',$this->company_id)
                ->where('delivery_challan_id',$request['challan_id'])
                ->update(['shipment_status'=>true]);

            Activity::query()->insert([
                'log_name'=>'Update',
                'description'=>'Update Challan Shipping Info No : '.$request['challan_id'],
                'causer_id'=>$this->user_id,
                'causer_type'=>'App/Inventory/Movement/Delivery',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ]);


        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->withInput()->with('error',$error);
        }

        DB::commit();

        return redirect()->action('Inventory\Export\ShippingInfoCO@index',['challan_id'=>$request['challan_id'],'action'=>'action'])->with('success','Information Successfully Updated');
    }

    public function container(Request $request, $lot_no)
    {
        DB::beginTransaction();

        $container = ExportShippingFileUpload::query()
            ->where('company_id',$this->company_id)
            ->where('challan_id',$request['challan'])
            ->where('container',$request['container'])->first();

        try {

            ProductHistory::query()->where('company_id',$this->company_id)
                ->where('lot_no',$lot_no)
                ->update([
                    'container'=>$container->container,
                    'seal_no'=>$container->seal_no,
                    'cbm'=>$container->cbm
                    ]);

            Activity::query()->insert([
                'log_name'=>'Update',
                'description'=>'Update Shipping : for Lot No : '.$lot_no,
                'causer_id'=>$this->user_id,
                'causer_type'=>'App/Inventory/Movement/ProductHistory',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ]);


        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success'=>'Shipping Information Updated','container'=>$container],200);
    }
}
