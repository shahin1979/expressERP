<?php

namespace App\Http\Controllers\Inventory\Requisition;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Human\Admin\Location;
use App\Models\Inventory\Product\ProductMO;
use Illuminate\Http\Request;

class CreateRequisitionCO extends Controller
{
    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>54005,'user_id'=>$this->user_id
            ]);

        $locations = Location::query()->where('company_id',$this->company_id)
                    ->where('location_type','F')
            ->orderBy('name')->pluck('name','id');

        return view('inventory.requisition.create-requisition-index',compact('locations'));
    }

    public function autocomplete(Request $request)
    {
        $term = $request['term'];

        $items = ProductMO::select('id as item_id', 'name','tax_id','unit_price')
            ->where('company_id',$this->company_id)
            ->where('name', 'LIKE', '%'.$term.'%')->get();

        return response()->json($items);
    }

    public function store(Request $request)
    {
        dd($request->all());
    }
}
