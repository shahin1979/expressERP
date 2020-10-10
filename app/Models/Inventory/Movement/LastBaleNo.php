<?php

namespace App\Models\Inventory\Movement;

use App\Models\Inventory\Product\ProductMO;
use Illuminate\Database\Eloquent\Model;

class LastBaleNo extends Model
{
    protected $guarded = ['id', 'created_at','updated_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'line_no',
        'product_id',
        'tr_weight',
        'lot_no',
        'bale_serial',
        'bale_no',
        'updated_by',
        'status',
    ];

    public function item()
    {
        return $this->belongsTo(ProductMO::class,'product_id','id');
    }
}
