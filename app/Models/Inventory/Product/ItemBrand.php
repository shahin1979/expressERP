<?php

namespace App\Models\Inventory\Product;

use Illuminate\Database\Eloquent\Model;

class ItemBrand extends Model
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
        'manufacturer',
        'image_path',
        'status',
        'locale',
        'user_id',
    ];
}
