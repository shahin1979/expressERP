<?php

namespace App\Models\Inventory\Movement;

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
}
