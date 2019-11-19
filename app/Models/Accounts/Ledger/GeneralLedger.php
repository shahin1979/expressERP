<?php

namespace App\Models\Accounts\Ledger;

use App\Models\Accounts\Common\AccountTypeDetail;
use Illuminate\Database\Eloquent\Model;

class GeneralLedger extends Model
{

    protected $table = "general_ledgers";

    protected $guarded = ['ID', 'CREATED_AT','UPDATED_AT'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'COMPANY_ID',
        'LEDGER_CODE',
        'ACC_NO',
        'ACC_NAME',
        'ACC_TYPE',
        'TYPE_CODE',
        'ACC_RANGE',
        'IS_GROUP',
        'OPN_DR',
        'OPN_CR',
        'START_DR',
        'START_CR',
        'CURR_BAL',
        'CYR_DR',
        'CYR_CR',
        'DR_00',
        'CR_00',
        'PYR_DR',
        'PYR_CR',
        'DR_01',
        'CR_01',
        'DR_02',
        'CR_02',
        'DR_03',
        'CR_03',
        'DR_04',
        'CR_04',
        'DR_05',
        'CR_05',
        'DR_06',
        'CR_06',
        'DR_07',
        'CR_07',
        'DR_08',
        'CR_08',
        'DR_09',
        'CR_09',
        'DR_10',
        'CR_10',
        'DR_11',
        'CR_11',
        'DR_12',
        'CR_12',
        'CYR_BGT_PR',
        'CYR_BGT_TR',
        'CYR_BGT_TA',
        'LYR_BGT_PR',
        'LYR_BGT_TR',
        'BGT_01',
        'BGT_02',
        'BGT_03',
        'BGT_04',
        'BGT_05',
        'BGT_06',
        'BGT_07',
        'BGT_08',
        'BGT_09',
        'BGT_10',
        'BGT_11',
        'BGT_12',
        'FC_BGT',
        'FC_BAL_DR',
        'FC_BAL_CR',
        'CURRENCY',
        'OPN_POST',
        'USER_ID',
    ];

    public function details()
    {
        return $this->belongsTo(AccountTypeDetail::class,'type_code' ,'type_code');
//        return $this->belongsTo('App\Models\Accounts\Common\AccountTypeDetail');
    }

    public function parent() {
        return $this->belongsTo('App\Models\Accounts\Ledger\GeneralLedger','ledger_code','ledger_code')->where('is_group',true);
    }

    public function getNameAccountAttribute()
    {
        return "{$this->acc_name} {$this->acc_no}";
    }
}
