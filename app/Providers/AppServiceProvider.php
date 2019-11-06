<?php

namespace App\Providers;

use App\Models\Common\MenuItem;
use App\Models\Company\Company;
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
        $menus = MenuItem::query()->where('status',true)->get();
        View::share('menus', $menus);

    }
}
