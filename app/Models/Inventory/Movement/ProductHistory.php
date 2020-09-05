<?php

namespace App\Models\Inventory\Movement;

use App\Models\Accounts\Ledger\CostCenter;
use App\Models\Company\Relationship;
use App\Models\Human\Admin\Location;
use App\Models\Inventory\Product\ProductMO;
use App\Models\Inventory\Product\ProductUniqueId;
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

    protected $appends = ['balance'];

    protected $fillable = [
        'company_id',
        'ref_no',
        'ref_id',
        'tr_date',
        'ref_type',
        'contra_ref',
        'product_id',
        'quantity_in',
        'quantity_out',
        'unit_price',
        'total_price',
        'tr_weight',
        'gross_weight',
        'multi_unit',
        'lot_no',
        'bale_no',
        'vehicle_no',
        'container',
        'seal_no',
        'cbm',
        'relationship_id',
        'remarks',
        'status',
        'acc_post',
        'stock_out',
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

    public function customer()
    {
        return $this->belongsTo(Relationship::class,'relationship_id','id');
    }
    public function cost()
    {
        return $this->belongsTo(CostCenter::class,'relationship_id','id');
    }

    public function serial()
    {
        return $this->hasOne(ProductUniqueId::class,'history_ref_id','id');
    }


    public function getBalanceAttribute()
    {
        return $this->quantity_in - $this->quantity_out;
    }
}
