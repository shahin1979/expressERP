<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\Common\AppModule;
use App\Models\Company\CompanyModule;
use Illuminate\Http\Request;

class ManageUserPermissionCO extends Controller
{
    public function index(Request $request)
    {
        $modules = CompanyModule::query()->where('company_id',$this->company_id)
            ->with('module')->get();

        return view('security.manage-user-permissions-index',compact('modules'));

    }
}
