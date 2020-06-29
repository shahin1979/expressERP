<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class CostType extends Model
{
    protected $guarded = ['id', 'created_at','updated_at'];
//    protected $dates = ['FPSTART'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'name',
        'description',
        'gl_head',
        'user_id',
    ];
}
