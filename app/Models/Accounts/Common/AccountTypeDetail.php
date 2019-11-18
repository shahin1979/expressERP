<?php

namespace App\Models\Accounts\Common;

use Illuminate\Database\Eloquent\Model;

class AccountTypeDetail extends Model
{

    protected $guarded = ['ID','CREATED_AT'];

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

    public $timestamps = false;
}
