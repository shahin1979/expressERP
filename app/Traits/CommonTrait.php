<?php


namespace App\Traits;


use App\Models\Common\UserActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait CommonTrait
{
    public function menu_log($company_id, $menu_id)
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$company_id,'menu_id'=>$menu_id,'user_id'=>Auth::id()],
            ['updated_at'=>Carbon::now()
            ]);
    }
}
