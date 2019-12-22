<?php

namespace App\Http\Controllers\Human\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Models\Human\Admin\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class LocationsCO extends Controller
{
    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>31075,'user_id'=>$this->user_id
            ]);

        return view('human.admin.location-index');
    }

    public function getLocationDTData()
    {
        $locations = Location::query()->where('company_id',$this->company_id)->get();

        return DataTables::of($locations)
            ->addColumn('action', function ($locations) {

                return '<div class="btn-unit btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="view/'.$locations->id.'"  type="button" class="btn btn-view btn-sm btn-success"><i class="fa fa-book-open">View</i></button>
                    <button data-remote="edit/' . $locations->id . '" data-rowid="'. $locations->id . '"
                        data-name="'. $locations->name . '"
                        data-type="'. $locations->location_type . '"
                        type="button" class="btn btn-sm btn-product-edit btn-primary pull-center"><i class="fa fa-edit" >Edit</i></button>
                    <button data-remote="location/delete/'.$locations->id.'"  type="button" class="btn btn-unit-delete btn-sm btn-danger"><i class="fa fa-trash">Delete</i></button>
                    </div>

                    ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        DB::begintransaction();

        try {

            $ids = Location::query()->create([
                'company_id' => $this->company_id,
                'name'=>$request['name'],
                'location_type'=>$request['location_type'],
                'status' => true,
                'user_id' => $this->user_id
            ]);

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return response()->json(['error' => $error], 404);
        }

        DB::commit();

        return response()->json(['success' => 'New Location Added '], 200);
    }
}
