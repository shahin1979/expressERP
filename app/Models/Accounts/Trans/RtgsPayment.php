<?php

namespace App\Models\Accounts\Trans;

use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Setup\Bank;
use Illuminate\Database\Eloquent\Model;

class RtgsPayment extends Model
{
    protected $table = 'rtgs_payments';

    protected $guarded = ['id', 'created_at','updated_at'];

    protected $fillable = [
        'trans_date',
        'voucher_no',
        'rtgs_acc_cr',
        'rtgs_acc_dr',
        'trans_amt',
        'reason',
        'gl_credit',
        'gl_debit',
        'status',
        'description',
        'user_id',
    ];

    public function rtgs_title_cr()
    {
        return $this->belongsTo(Bank::class,'rtgs_acc_cr','acc_no');
    }

    public function sender_gl()
    {
        return $this->belongsTo(GeneralLedger::class,'gl_credit','accNo');
    }

    public function rtgs_title_dr()
    {
        return $this->belongsTo(Bank::class,'rtgs_acc_dr','acc_no');
    }

    public function benificiary_gl()
    {
        return $this->belongsTo(GeneralLedger::class,'gl_debit','accNo');
    }

}
