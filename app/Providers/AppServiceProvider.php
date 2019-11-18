<?php

namespace App\Providers;

use App\Models\Common\MenuItem;
use App\Models\Company\Company;
use App\Models\Company\CompanyProperty;
use App\Models\Company\FiscalPeriod;
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
            $company = CompanyProperty::query()->where('company_id', session('comp_id'))->first();
            $min_date = FiscalPeriod::query()->where('company_id', session('comp_id'))->where('status',true)->where('fpno',1)->value('startdate');
            $max_date = FiscalPeriod::query()->where('company_id', session('comp_id'))->where('status',true)->where('fpno',5)->value('enddate');

            $view->with('company',$company)->with('min_date',$min_date)->with('max_date',$max_date);


        });

    }
}
