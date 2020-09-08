<?php

namespace App\Http\Controllers\Inventory\Export;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Inventory\Movement\Delivery;
use App\Models\Inventory\Movement\ProductHistory;
use App\Traits\CommonTrait;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class UpdateTransportVehicleCO extends Controller
{
    use CommonTrait;

    public function index(Request $request)
    {
        $this->menu_log($this->company_id,58035);

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
                })->get();

            $lots = $products->unique('lot_no');

            return view('inventory.export.index.update-transport-vehicle-index', compact('products','delivery','lots'));
        }
        return view('inventory.export.index.update-transport-vehicle-index',compact('selections'));
    }

    public function store(Request $request, $lotNo)
    {
        DB::beginTransaction();

        try {

            ProductHistory::query()->where('company_id',$this->company_id)
                ->where('lot_no',$lotNo)->update(['vehicle_no'=>$request['vehicle']]);

            Activity::query()->insert([
                'log_name'=>'Update',
                'description'=>'Update Truck No : '.$request['vehicle'].' for Lot No : '.$lotNo,
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

        return response()->json(['success'=>'Vehicle No Updated'],200);
    }
}
