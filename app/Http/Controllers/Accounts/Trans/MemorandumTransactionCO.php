<?php

namespace App\Http\Controllers\Accounts\Trans;

use App\Http\Controllers\Controller;
use App\Models\Common\UserActivity;
use App\Traits\TransactionsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MemorandumTransactionCO extends Controller
{
    use TransactionsTrait;

    public function index()
    {
        UserActivity::query()->updateOrCreate(
            ['company_id'=>$this->company_id,'menu_id'=>44020,'user_id'=>$this->user_id
            ],['updated_at'=>Carbon::now()
        ]);

        return view('accounts.trans.memorandum-voucher-index');
    }
}
