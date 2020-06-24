<?php

namespace App\Models\Human\Admin;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $guarded = ['id','created_at','updated_at','deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'location_type',
        'name',
        'in_charge',
        'description',
        'old_id',
        'status',
        'user_id',
    ];
}
