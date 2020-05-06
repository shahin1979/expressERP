<?php

namespace App\Models\Accounts\Common;

use Illuminate\Database\Eloquent\Model;

class AccountTypeDetail extends Model
{

    protected $guarded = ['id','created_at'];

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_type_id',
        'type_code',
        'description'
    ];

    public function parent()
    {
        return $this->belongsTo(AccountType::class,'account_type_id','id');
//        return $this->belongsTo('App\Models\Accounts\Common\AccountTypeDetail');
    }


}
