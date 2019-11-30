<?php

namespace App\Models\Accounts\Common;

use Illuminate\Database\Eloquent\Model;

class AccountTypeDetail extends Model
{

    protected $guarded = ['ID','CREATED_AT'];

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ACCOUNT_TYPE_ID',
        'TYPE_CODE',
        'DESCRIPTION'
    ];

    public function parent()
    {
        return $this->belongsTo(AccountType::class,'account_type_id','id');
//        return $this->belongsTo('App\Models\Accounts\Common\AccountTypeDetail');
    }


}
