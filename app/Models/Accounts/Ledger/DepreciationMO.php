<?php

namespace App\Models\Accounts\Ledger;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class DepreciationMO extends Model
{
    protected $table = "depreciation";

    protected $guarded = ['ID','CREATED_AT','UPDATED_AT'];

    protected $fillable = [
        'COMPANY_ID',
        'ACC_NO',
        'FP_NO',
        'START_DATE',
        'END_DATE',
        'OPEN_BAL',
        'ADDITIONAL_BAL',
        'TOTAL_BAL',
        'DEP_RATE',
        'DEP_AMT',
        'CLOSING_BAL',
        'APPROVE_STATUS',
        'APPROVE_DATE',
        'CONTRA_ACC',
        'USER_ID',
        'AUTHORIZER_ID',
    ];

    public function account() {
        return $this->belongsTo(GeneralLedger::class,'acc_no','acc_no')->where('company_id',Session::get('comp_id'));
    }
}
