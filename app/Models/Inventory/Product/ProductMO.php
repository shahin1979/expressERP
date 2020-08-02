<?php

namespace App\Models\Inventory\Product;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductMO extends Model
{
    use LogsActivity;

    protected $table = 'products';
    protected $guarded = ['id', 'created_at','updated_at','deleted_at'];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated','deleted'];
    protected static $logOnlyDirty = true;


    protected $fillable = [
        'company_id',
        'name',
        'product_code',
        'brand_id',
        'category_id',
        'subcategory_id',
        'multi_unit',
        'unit_name',
        'second_unit',
        'third_unit',
        'variants',
        'size_id',
        'color_id',
        'sku',
        'model_id',
        'tax_id',
        'godown_id',
        'rack_id',
        'initial_price',
        'buy_price',
        'wholesale_price',
        'retail_price',
        'unit_price',
        'reorder_point',
        'opening_qty',
        'opening_qty_unit_two',
        'opening_qty_unit_three',
        'opening_value',
        'on_hand',
        'on_hand_unit_two',
        'on_hand_unit_three',
        'expiry_date',
        'committed',
        'in_comming',
        'max_online_stock',
        'min_online_order',
        'purchase_qty',
        'purchase_qty_unit_two',
        'purchase_qty_unit_three',
        'sell_qty',
        'sell_qty_unit_two',
        'sell_qty_unit_three',
        'salvage_qty',
        'salvage_qty_unit_two',
        'salvage_qty_unit_three',
        'received_qty',
        'received_qty_unit_two',
        'received_qty_unit_three',
        'consumption_qty',
        'consumption_qty_unit_two',
        'consumption_qty_unit_three',
        'return_qty',
        'return_qty_unit_two',
        'return_qty_unit_three',
        'shipping',
        'discount',
        'description_short',
        'description_long',
        'stuff_included',
        'warranty_period',
        'image',
        'image_large',
        'sellable',
        'purchasable',
        'b2bpublish',
        'free',
        'taxable',
        'status',
        'locale',
        'user_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function rate()
    {
        return $this->hasMany(SalesRateHistory::class,'product_id','id');
    }

    public function getExpiryDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

}
