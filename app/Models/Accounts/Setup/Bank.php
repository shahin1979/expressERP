<?php

namespace App\Models\Accounts\Setup;

use App\Models\Accounts\Ledger\GeneralLedger;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = "banks";

    protected $guarded = ['id', 'created_at','updated_at'];

    protected $fillable = [
        'company_id',
        'bank_type',
        'bank_code',
        'bank_name',
        'branch_name',
        'bank_acc_name',
        'bank_acc_no',
        'related_gl_id',
        'address',
        'swift_code',
        'mobile_no',
        'email',
        'status',
        'user_id',
        'extra_field'
    ];

    public function account()
    {
        return $this->belongsTo(GeneralLedger::class,'related_gl_id','id')->withDefault();
    }
}