<?php

namespace App\Models\Inventory\Product;

use App\Models\Inventory\Movement\Purchase;
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
        'stock_status', // 1 available 0 not abailable
        'status',
        'data_validity',
        'user_id'
    ];

    public function item()
    {
        return $this->belongsTo(ProductMO::class,'product_id','id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class,'purchase_ref_id','id');
    }

}
