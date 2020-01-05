<?php

namespace App\Models\Inventory\Movement;

use App\Models\Human\Admin\Location;
use App\Models\Inventory\Product\ProductMO;
use Illuminate\Database\Eloquent\Model;

class TransProduct extends Model
{
    protected $table= 'trans_products';

    protected $guarded = ['id', 'created_at','updated_at','deleted_at'];

    protected $fillable = [
        'company_id',
        'ref_no',
        'ref_id',
        'tr_date',
        'ref_type',
        'to_whom',
        'product_id',
        'name',
        'quantity',
        'unit_price',
        'tax_id',
        'tax_total',
        'total_price',
        'approved',
        'purchased',
        'sold',
        'received',
        'returned',
        'delivered',
        'remarks',
        'status',
    ];

    public function item()
    {
        return $this->belongsTo(ProductMO::class,'product_id','id');
    }

    public function requisition()
    {
        return $this->belongsTo(Requisition::class,'ref_id','id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class,'to_whom','id');
    }
}