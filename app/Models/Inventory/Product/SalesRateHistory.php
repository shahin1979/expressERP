<?php

namespace App\Models\Inventory\Product;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SalesRateHistory extends Model
{
    use LogsActivity;

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
        'product_id',
        'start_date',
        'wholesale_price',
        'retail_price',
        'status',
        'user_id',
        'approve_id',
        'approve_date'
    ];

    public function item()
    {
        return $this->belongsTo(ProductMO::class,'product_id','id');
    }
}
