<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class TransCode extends Model
{

    protected $guarded = ['ID', 'CREATED_AT','UPDATED_AT'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'COMPANY_ID',
        'TRANS_CODE',
        'TRANS_NAME',
        'LAST_TRANS_ID',
    ];


}
