<?php

namespace App\Models\Inventory\Movement;

use App\Models\Accounts\Ledger\CostCenter;
use App\Models\Company\Relationship;
use App\Models\Human\Admin\Location;
use App\Models\Inventory\Export\ExportContract;
use App\Models\Inventory\Product\ItemTax;
use App\Models\Inventory\Product\ProductMO;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class TransProduct extends Model
{
    use LogsActivity;

    protected $table= 'trans_products';

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
        'relationship_id',
        'product_id',
        'item_sid_no',
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
        'tr_weight',
        'gross_weight',
        'multi_unit',
        'lot_no',
        'bale_no',
        'vehicle_no',
        'remarks',
        'status',
        'deleted',
    ];

    public function item()
    {
        return $this->belongsTo(ProductMO::class,'product_id','id');
    }

    public function tax()
    {
        return $this->belongsTo(ItemTax::class,'tax_id','id');
    }


    public function requisition()
    {
        return $this->belongsTo(Requisition::class,'ref_id','id');
    }

    public function receive()
    {
        return $this->belongsTo(Receive::class,'ref_id','id');
    }

    public function returnItem()
    {
        return $this->belongsTo(ReturnItem::class,'ref_id','id');
    }

    public function invoice()
    {
        return $this->belongsTo(Sale::class,'ref_id','id');
    }

    public function contract()
    {
        return $this->belongsTo(ExportContract::class,'ref_id','id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class,'ref_id','id');
    }

    public function delivery()
    {
        return $this->belongsTo(Delivery::class,'ref_no','challan_no');
    }

//    public function location()
//    {
//        return $this->belongsTo(Location::class,'relationship_id','id');
//    }

    public function costcenter()
    {
        return $this->belongsTo(CostCenter::class,'relationship_id','id');
    }

    public function customer()
    {
        return $this->belongsTo(Relationship::class,'relationship_id','id');
    }

    public function supplier()
    {
        return $this->belongsTo(Relationship::class,'relationship_id','id');

//        return $this->ref_type;

    }

//    public function scopeRelation($query)
//    {
//        return $query
//            ->when($this->ref_type === 'F',function($q){
//                return $q->with('costcenter');
//            })
//            ->when($this->type === 'C',function($q){
//                return $q->with('supplier');
//            });
//    }

    public function getItemTotalAttribute($value)
    {
        return $this->quantity * $this->unit_price;
    }
}
