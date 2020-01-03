<?php

namespace App\Providers;

use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Common\MenuItem;
use App\Models\Common\TransType;
use App\Models\Common\UserActivity;
use App\Models\Company\Company;
use App\Models\Company\CompanyModule;
use App\Models\Company\CompanyProperty;
use App\Models\Company\FiscalPeriod;
use App\Models\Projects\Project;
use App\Models\Security\UserPrivilege;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('menu_items')) {
            $menus = MenuItem::query()->where('status',true)->orderBy('id')->get();
            View::share('menus', $menus);
        }


        View::composer('*', function ($view) {

            if (Schema::hasTable('companies')) {
                $users_company = CompanyProperty::query()->where('company_id', session('comp_id'))->with('company')->first();
                $view->with('users_company',$users_company);
            }

            if (Schema::hasTable('fiscal_periods')) {
                $min_date = FiscalPeriod::query()->where('company_id', session('comp_id'))->where('status',true)->where('fp_no',1)->value('start_date');
                $max_date = FiscalPeriod::query()->where('company_id', session('comp_id'))->where('status',true)->where('fp_no',5)->value('end_date');
                $view->with('min_date',$min_date)->with('max_date',$max_date);
            }
        });

        View::composer('home', function ($view) {

            if (Schema::hasTable('company_modules')) {
                $comp_menus = CompanyModule::query()->where('company_id', session('comp_id'))->get();
            }

            $role_id = Auth::user()->role_id;

            //Get Auth user menus
            $user_menus = UserPrivilege::query()->where('company_id', session('comp_id'))
                ->where('user_id',Auth::id())
                ->where(function ($query) {
                    $query->where('add',true)
                        ->orWhere('edit',true)
                        ->orWhere('view',true)
                        ->orWhere('delete',true);
                })->with('menus')
                ->get();

            $user_permissions = collect();

            foreach ($user_menus as $row)
            {
                $line = MenuItem::query()->where('id',$row->menu_id)
                    ->where('status',true)
                    ->first();

                $user_permissions->push($line);
            }

            $unique_module = $user_menus->unique('module_id');
            $unique_mm = $user_menus->unique('menus.menu_prefix');


            foreach ($unique_module as $row)
            {
                $line = MenuItem::query()->where('module_id',$row->module_id)
                    ->where('menu_type','CM')->where('status',true)
                    ->first();

                $user_permissions->push($line);
            }

            foreach ($unique_mm as $row)
            {
                $line = MenuItem::query()->where('menu_prefix',$row->menus->menu_prefix)
                    ->where('menu_type','MM')->where('status',true)
                    ->first();

                $user_permissions->push($line);
            }

            $user_permissions = $user_permissions->sortBy('id');
//            $user_permissions = collect($user_permissions);

            $user_activities = UserActivity::query()->where('user_id',Auth::id())
                ->orderBy('updated_at','DESC')
                ->take(10)->get();

            //////////////////////////////////////////////

            $view->with('comp_menus',$comp_menus)->with('user_permissions',$user_permissions)->with('role_id',$role_id)
            ->with('user_activities',$user_activities);

        });


        View::composer('accounts.trans.*', function ($view) {


            $projects = Project::query()->where('company_id',session('comp_id'))
                ->whereNotIn('status',['C'])->pluck('project_name','id');

            $trans_types = TransType::query()->pluck('name','id');

            $groupList = GeneralLedger::query()->where('company_id',session('comp_id'))
                ->where('is_group',true)->orderBy('acc_name','ASC')
                ->pluck('acc_name','ledger_code');

            $accountList = GeneralLedger::query()->where('company_id',session('comp_id'))
                ->where('is_group',false)->orderBy('acc_name','ASC')
                ->pluck('acc_name','acc_no');

            $view->with('trans_types',$trans_types)->with('projects',$projects)
            ->with('groupList',$groupList)->with('accountList',$accountList);
        });


// Inventory Blades

        View::composer('inventory.*', function ($view) {
            if (Schema::hasTable('company_modules')) {
                $comp_modules = CompanyModule::query()->where('company_id', session('comp_id'))->get();

                $view->with('comp_modules',$comp_modules);
            }

        });

    }
}
