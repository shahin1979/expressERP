<?php

namespace App\Http\Controllers;

use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Common\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $activity = Activity::all()->last();

        $accounts = GeneralLedger::query()
            ->where('is_group',true)
            ->where('company_id',Auth::user()->company_id)->get();


//        $user_menus = UserMenu::query()->where('user_id',$this->user_id)->get();

//        dd($user_menus);


//        echo($activity->description); //returns 'deleted'
//        echo($activity->changes);

        return view('home',compact('activity','accounts'));
    }
}
