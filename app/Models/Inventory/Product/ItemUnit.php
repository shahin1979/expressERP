<?php

namespace App\Models\Inventory\Product;

use Illuminate\Database\Eloquent\Model;

class ItemUnit extends Model
{
    protected $guarded = ['id', 'created_at','updated_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'name',
        'formal_name',
        'no_of_decimal_places',
        'status',
        'locale',
        'user_id',
    ];

}
