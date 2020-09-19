<?php

namespace App\Http\Controllers\Inventory\Production;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Movement\LastBaleNo;
use App\Models\Inventory\Product\ProductMO;
use App\Traits\CommonTrait;
use App\Traits\CompanyTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Psy\Util\Str;

class ProductionSetupCO extends Controller
{
    use CommonTrait, CompanyTrait;

    public function index()
    {
        $this->menu_log($this->company_id,54002);

        $production = LastBaleNo::query()->where('status',true)->get(); // current production
        $items = ProductMO::query()->where('company_id',$this->company_id)
            ->where('category_id',$this->get_default_finished_foods_category_id($this->company_id))
            ->pluck('name','id');


        return view('inventory.production.production-setup-index',compact('production','items'));
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        try {

            $current = LastBaleNo::query()->where('line_no',$request['line_no'])->first();
            if($request['lot_no'] < $current->lot_no)
            {
                $error = 'Invalid Lot No : '.$request['lot_no'];
                return response()->json(['error'=>'Failed to update : Error : '.$error],404);
            }

            if($request['bale_sl'] <= $current->bale_serial)
            {
                $error = 'Invalid Bale Serial : '.$request['bale_sl'];
                return response()->json(['error'=>'Failed to update : Error : '.$error],404);
            }

            $product = ProductMO::query()->where('id',$request['product_id'])->first();

            $bale_no = str_replace('D','',$product->model->name).
                    substr($product->subcategory->name,0,1).
                    Carbon::now()->format('y').$request['lot_no'].
                    str_pad($request['bale_sl'], 2, "0", STR_PAD_LEFT);
                ;

//            dd($bale_no);

            LastBaleNo::query()->where('line_no',$request['line_no'])
                ->update(['product_id'=>$request['product_id'],'tr_weight'=>$request['tr_weight'],
                    'lot_no'=>$request['lot_no'],
                    'bale_serial'=>$request['bale_sl'], 'bale_no'=>$bale_no]);


        }catch (\Exception $exception)
        {
            DB::rollBack();
            $error = $exception->getMessage();
            return response()->json(['error'=>'Failed to update : Error : '.$error],404);
        }

        DB::commit();

        $data = LastBaleNo::query()->with('item.model')->with('item.size') ->get();
        return response()->json(['success'=>'Production For Line '.$request['line_no'].' is updated','output'=>$data],200);
    }
}
