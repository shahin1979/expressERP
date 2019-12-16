<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class TransCode extends Model
{

    protected $guarded = ['id', 'created_at','updated_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'trans_code',
        'trans_name',
        'last_trans_id',
    ];


}
