<?php

namespace App\Models\Accounts\Previous;

use App\Models\Accounts\Common\AccountTypeDetail;
use Illuminate\Database\Eloquent\Model;

class GeneralLedgerBackup extends Model
{
    protected $table = "general_ledger_backups";

    protected $guarded = ['id', 'created_at','updated_at'];
    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated','deleted'];
    protected static $logOnlyDirty = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'fiscal_year',
        'ledger_code',
        'acc_no',
        'acc_name',
        'acc_type',
        'type_code',
        'acc_range',
        'is_group',
        'opn_dr',
        'opn_cr',
        'start_dr',
        'start_cr',
        'curr_bal', //current year posted + unposted
        'cyr_dr', // current year posted Trans
        'cyr_cr', // current year posted trans
        'dr_00', // current year posted + unposted
        'cr_00', // current year posted + unposted
        'pyr_dr',
        'pyr_cr',
        'dr_01',
        'cr_01',
        'dr_02',
        'cr_02',
        'dr_03',
        'cr_03',
        'dr_04',
        'cr_04',
        'dr_05',
        'cr_05',
        'dr_06',
        'cr_06',
        'dr_07',
        'cr_07',
        'dr_08',
        'cr_08',
        'dr_09',
        'cr_09',
        'dr_10',
        'cr_10',
        'dr_11',
        'cr_11',
        'dr_12',
        'cr_12',
        'cyr_bgt_pr',
        'cyr_bgt_tr',
        'cyr_bgt_ta',
        'lyr_bgt_pr',
        'lyr_bgt_tr',
        'bgt_01',
        'bgt_02',
        'bgt_03',
        'bgt_04',
        'bgt_05',
        'bgt_06',
        'bgt_07',
        'bgt_08',
        'bgt_09',
        'bgt_10',
        'bgt_11',
        'bgt_12',
        'fc_bgt',
        'fc_bal_dr',
        'fc_bal_cr',
        'currency',
        'opn_post',
        'user_id',
    ];

    public function details()
    {
        return $this->belongsTo(AccountTypeDetail::class,'type_code' ,'type_code');
//        return $this->belongsTo('App\Models\Accounts\Common\AccountTypeDetail');
    }

    public function parent() {
        return $this->belongsTo('App\Models\Accounts\Previous\GeneralLedgerBackup','ledger_code','ledger_code')->where('is_group',true);
    }

    public function getNameAccountAttribute()
    {
        return "{$this->acc_name} {$this->acc_no}";
    }
}
