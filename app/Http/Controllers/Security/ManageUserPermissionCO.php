<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\Common\AppModule;
use App\Models\Common\MenuItem;
use App\Models\Company\CompanyModule;
use App\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ManageUserPermissionCO extends Controller
{
    public function index(Request $request)
    {
        $modules = CompanyModule::query()->where('company_id',$this->company_id)->get();

        $menus = MenuItem::query()->where('menu_type','<>','CM')->orderBy('id')->get();

        return view('security.manage-user-permissions-index',compact('modules','menus'));

    }

    public function usersDTData()
    {
        $users = User::query()->where('company_id',$this->company_id);

        return DataTables::of($users)
            ->addColumn('action', function ($users) {

                return '<div class="btn-group btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="permissions/'.$users->id.'" 
                    data-id="'. $users->id . '"
                    data-name="'. $users->name . '"
                    data-email="'. $users->email . '"
                    type="button" class="btn btn-permission btn-sm btn-primary"><i class="fa fa-open">Permissions</i></button>
                    ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
