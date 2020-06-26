<?php

namespace App\Models\Inventory\Movement;

use App\Models\Accounts\Ledger\CostCenter;
use App\Models\Company\Relationship;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Delivery extends Model
{
    use LogsActivity;

    protected $table= 'deliveries';

    protected $guarded = ['id', 'created_at','updated_at','deleted_at'];
    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated','deleted'];
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'company_id',
        'challan_no',
        'ref_no',
        'relationship_id',
        'delivery_type',
        'delivery_date',
        'approve_by',
        'approve_date',
        'description',
        'status',
        'stock_status',
        'account_post',
        'account_voucher',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'extra_field',
        'old_challan',
    ];

    public function items()
    {
        return $this->hasMany(TransProduct::class,'ref_no','challan_no');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Relationship::class,'relationship_id','id');
    }

    public function costcenter()
    {
        return $this->belongsTo(CostCenter::class,'relationship_id','id');
    }
}
