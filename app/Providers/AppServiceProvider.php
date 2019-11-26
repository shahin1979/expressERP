<?php

namespace App\Providers;

use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Common\MenuItem;
use App\Models\Common\TransType;
use App\Models\Company\Company;
use App\Models\Company\CompanyModule;
use App\Models\Company\CompanyProperty;
use App\Models\Company\FiscalPeriod;
use App\Models\Projects\Project;
use Illuminate\Support\Facades\Auth;
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
        $menus = MenuItem::query()->where('status',true)->orderBy('id')->get();
//        $company = CompanyProperty::query()->where('company_id',session('comp_id'))->first();
        View::share('menus', $menus);
//        View::share('company', $company);

//        View::composer('*', function($view) {
////            if (Auth::check()) {
//                $company = CompanyProperty::query()->where('company_id', 1)->first();
//                'company', $company;
////            }
//        });

        View::composer('*', function ($view) {
            $company = CompanyProperty::query()->where('company_id', session('comp_id'))->with('company')->first();
            $min_date = FiscalPeriod::query()->where('company_id', session('comp_id'))->where('status',true)->where('fpno',1)->value('startdate');
            $max_date = FiscalPeriod::query()->where('company_id', session('comp_id'))->where('status',true)->where('fpno',5)->value('enddate');
            $view->with('company',$company)->with('min_date',$min_date)->with('max_date',$max_date);
        });

        View::composer('home', function ($view) {
            $comp_menus = CompanyModule::query()->where('company_id', session('comp_id'))->get();
            $view->with('comp_menus',$comp_menus);
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

    }
}
