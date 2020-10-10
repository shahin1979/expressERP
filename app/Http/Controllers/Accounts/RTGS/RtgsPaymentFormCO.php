<?php

namespace App\Http\Controllers\Accounts\RTGS;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Setup\Bank;
use App\Traits\CommonTrait;
use App\Traits\CompanyTrait;
use App\Traits\TransactionsTrait;
use Illuminate\Http\Request;

class RtgsPaymentFormCO extends Controller
{
    use TransactionsTrait, CompanyTrait, CommonTrait;

    public function index()
    {
        $this->menu_log($this->company_id,45005);

        $sender = Bank::query()->where('bank_type','M')->where('status',true)->get();
        $sender = $sender->pluck('bank_account','bank_acc_no');

        $beneficiary = Bank::query()->where('bank_type','S')->where('status',true)->get();
        $beneficiary=$beneficiary->pluck('title_account','bank_acc_no');

        return view('accounts.rtgs.rtgs-payment-form-index',compact('sender','beneficiary'));
    }
}
