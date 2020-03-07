<?php

namespace App\Http\Controllers;

use App\Models\Company\CompanyModule;
use App\Models\Company\CompanyProperty;
use App\Models\Company\FiscalPeriod;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $company_id;
    public $user_id;
    public $fiscal_year;

//    public $company_modules;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->company_id = Auth::user()->company_id;
            $this->user_id = Auth::id();

            $this->fiscal_year = FiscalPeriod::query()
                ->where('company_id',Auth::user()->company_id)
                ->where('fp_no',1)->where('status',true)
                ->value('fiscal_year');

//            $this->company_modules = CompanyModule::query()->where('company_id',Auth::user()->company_id)->get();

            return $next($request);
        });

//        $this->comp_code = Auth::user()->compCode;
    }
}
