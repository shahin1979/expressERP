<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LogActivityCO extends Controller
{
    public function index()
    {
        $activity = Activity::all()->last();
//        $activity = Activity::where('id',202)->first();

        dd($activity);
    }
}
