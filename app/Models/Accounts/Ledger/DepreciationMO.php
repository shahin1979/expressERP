<?php

namespace App\Models\Accounts\Ledger;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class DepreciationMO extends Model
{
    protected $table = "depreciation";

    protected $guarded = ['id', 'created_at','updated_at'];

    protected $fillable = [
        'company_id',
        'acc_no',
        'fp_no',
        'start_date',
        'end_date',
        'open_bal',
        'additional_bal',
        'total_bal',
        'dep_rate',
        'dep_amt',
        'closing_bal',
        'approve_status',
        'approve_date',
        'contra_acc',
        'user_id',
        'authorizer_id',
    ];

    public function account() {
        return $this->belongsTo(GeneralLedger::class,'acc_no','acc_no')->where('company_id',Session::get('comp_id'));
    }
}
