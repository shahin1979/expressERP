<?php

namespace App\Models\Accounts\Trans;

use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Common\TransType;
use App\Models\Company\TransCode;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Transaction extends Model
{
    protected $guarded = ['id', 'created_at','updated_at'];

    protected $fillable = [
        'company_id',
        'period',
        'tr_code',
        'trans_type_id',
        'project_id',
        'fp_no',
        'ref_no',
        'cheque_no',
        'cost_center',
        'trans_id',
        'trans_group_id',
        'trans_date',
        'voucher_no',
        'acc_no',
        'dr_amt',
        'cr_amt',
        'trans_amt',
        'contra_acc',
        'currency',
        'fc_amt',
        'exchange_rate',
        'fiscal_year',
        'trans_desc1',
        'trans_desc2',
        'remote_desc',
        'post_flag',
        'post_date',
        'authorizer_id',
        'jv_flag',
        'tr_state',
        'export_flag',
        'user_id',
    ];

    public function getTransDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function account()
    {
        return $this->belongsTo(GeneralLedger::class,'acc_no','acc_no')->where('company_id',Session::get('comp_id'));
    }

    public function type()
    {
        return $this->belongsTo(TransType::class);
    }

    public function code()
    {
        return $this->belongsTo(TransCode::class,'tr_code','trans_code');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
