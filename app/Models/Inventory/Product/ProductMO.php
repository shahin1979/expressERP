<?php

namespace App\Models\Inventory\Product;

use Illuminate\Database\Eloquent\Model;

class ProductMO extends Model
{
    protected $table = 'products';
    protected $guarded = ['id', 'created_at','updated_at','deleted_at'];

    protected $fillable = [
        'company_id',
        'name',
        'product_code',
        'brand_id',
        'category_id',
        'subcategory_id',
        'multiple_unit',
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
        'opening_value',
        'on_hand',
        'expiry_date',
        'committed',
        'in_comming',
        'max_online_stock',
        'min_online_order',
        'purchase_qty',
        'sell_qty',
        'salvage_qty',
        'received_qty',
        'return_qty',
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

}
