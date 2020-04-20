<?php

namespace App\Models\Inventory\Movement;

use App\Models\Company\Relationship;
use App\Models\Human\Admin\Location;
use App\Models\Inventory\Product\ProductMO;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductHistory extends Model
{
    use LogsActivity;

    protected $table= 'product_histories';

    protected $guarded = ['id', 'created_at','updated_at','deleted_at'];
    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated','deleted'];
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'company_id',
        'ref_no',
        'ref_id',
        'tr_date',
        'ref_type',
        'product_id',
        'quantity_in',
        'quantity_out',
        'unit_price',
        'total_price',
        'multi_unit',
        'relationship_id',
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

    public function invoice()
    {
        return $this->belongsTo(Sale::class,'ref_no','id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class,'relationship_id','id');
    }

    public function supplier()
    {
        return $this->belongsTo(Relationship::class,'relationship_id','id');
    }
}
