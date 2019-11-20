<?php

namespace App\Models\Accounts\Trans;

use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Common\TransType;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = ['ID', 'CREATED_AT','UPDATED_AT'];

    protected $fillable = [
        'COMPANY_ID',
        'PERIOD',
        'TR_CODE',
        'TRANS_TYPE_ID',
        'PROJECT_ID',
        'FP_NO',
        'REF_NO',
        'CHEQUE_NO',
        'COST_CENTER',
        'TRANS_ID',
        'TRANS_GROUP_ID',
        'TRANS_DATE',
        'VOUCHER_NO',
        'ACC_NO',
        'DR_AMT',
        'CR_AMT',
        'TRANS_AMT',
        'CONTRA_ACC',
        'CURRENCY',
        'FC_AMT',
        'EXCHANGE_RATE',
        'FISCAL_YEAR',
        'TRANS_DESC1',
        'TRANS_DESC2',
        'REMOTE_DESC',
        'POST_FLAG',
        'POST_DATE',
        'AUTHORIZER_ID',
        'JV_FLAG',
        'EXPORT_FLAG',
        'USER_ID',
    ];

    public function getTransDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function accNo()
    {
        return $this->belongsTo(GeneralLedger::class,'acc_no','acc_no');
    }

    public function type()
    {
        return $this->belongsTo(TransType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
