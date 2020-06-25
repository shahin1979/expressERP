<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\Common\AppModule;
use App\Models\Common\MenuItem;
use App\Models\Company\CompanyModule;
use App\Models\Security\UserPrivilege;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
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
        $users = User::query()->where('company_id',$this->company_id)->with('privilege');

        return DataTables::of($users)
            ->addColumn('action', function ($users) {

                return '<div class="btn-group btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="getData/'.$users->id.'"
                    data-id="'. $users->id . '"
                    data-name="'. $users->name . '"
                    data-email="'. $users->email . '"
                    type="button" class="btn btn-permission btn-sm btn-primary"><i class="fa fa-open">Permissions</i></button>
                    ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function userData($id)
    {
        $data = UserPrivilege::query()->where('user_id',$id)->get();

//        dd($data);

        return response()->json($data);
    }

    public function store(Request $request)
    {

        DB::beginTransaction();

        try {

            UserPrivilege::query()->where('user_id', $request['user_id'])
                ->where('company_id',$this->company_id)
                ->update(['view' => false,'add' => false, 'edit' => false, 'delete' =>false,'print'=>false]);


            if($request->has('view'))
            {
                foreach ($request['view'] as $view)
                {

                    $module_id = MenuItem::query()->where('id',$view)->value('module_id');

                    UserPrivilege::query()->updateOrCreate(
                        ['company_id'=>$this->company_id,'user_id'=>$request['user_id'],'menu_id'=>$view],
                        [
                            'module_id'=>$module_id,
                            'view'=>true,
                            'approver_id'=>$this->user_id
                            ]);
                }
            }

            if($request->has('add'))
            {
                foreach ($request['add'] as $add)
                {
                    $module_id = MenuItem::query()->where('id',$add)->value('module_id');

                    UserPrivilege::query()->updateOrCreate(
                        ['company_id'=>$this->company_id,'user_id'=>$request['user_id'],'menu_id'=>$add],
                        [
                            'module_id'=>$module_id,
                            'add'=>true,
                            'approver_id'=>$this->user_id]);
                }
            }

            if($request->has('edit'))
            {
                foreach ($request['edit'] as $edit)
                {
                    $module_id = MenuItem::query()->where('id',$edit)->value('module_id');

                    UserPrivilege::query()->updateOrCreate(
                        ['company_id'=>$this->company_id,'user_id'=>$request['user_id'],'menu_id'=>$edit],
                        [
                            'module_id'=>$module_id,
                            'edit'=>true,
                            'approver_id'=>$this->user_id]);
                }
            }

            if($request->has('delete'))
            {
                foreach ($request['delete'] as $delete)
                {
                    $module_id = MenuItem::query()->where('id',$delete)->value('module_id');

                    UserPrivilege::query()->updateOrCreate(
                        ['company_id'=>$this->company_id,'user_id'=>$request['user_id'],'menu_id'=>$delete],
                        [
                            'module_id'=>$module_id,
                            'delete'=>true,
                            'approver_id'=>$this->user_id]);
                }
            }


            if($request->has('print'))
            {
                foreach ($request['print'] as $print)
                {
                    $module_id = MenuItem::query()->where('id',$print)->value('module_id');

                    UserPrivilege::query()->updateOrCreate(
                        ['company_id'=>$this->company_id,'user_id'=>$request['user_id'],'menu_id'=>$print],
                        [
                            'module_id'=>$module_id,
                            'print'=>true,
                            'approver_id'=>$this->user_id]);
                }
            }


        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->action('Security\ManageUserPermissionCO@index')->with('error','Not Saved '.$error);
        }

        DB::commit();

        return redirect()->action('Security\ManageUserPermissionCO@index')->with('success','Successfully Granted');
    }
}
