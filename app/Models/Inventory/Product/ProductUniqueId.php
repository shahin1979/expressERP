<?php

namespace App\Models\Inventory\Product;

use Illuminate\Database\Eloquent\Model;

class ProductUniqueId extends Model
{
    protected $guarded = ['id', 'created_at','updated_at'];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated','deleted'];
    protected static $logOnlyDirty = true;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'purchase_ref_id',
        'receive_ref_id',
        'return_ref_id',
        'sales_ref_id',
        'delivery_ref_id',
        'temp_id',
        'product_id',
        'unique_id',
        'stock_status',
        'status',
        'user_id'
    ];
}
