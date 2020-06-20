<?php

namespace App\Models\Inventory\Movement;

use App\Models\Company\Relationship;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    protected $table= 'returns';

    protected $guarded = ['id', 'created_at','updated_at','deleted_at'];
    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated','deleted'];
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'company_id',
        'challan_no',
        'ref_no',
        'supplier_id',
        'return_type',
        'return_date',
        'approve_by',
        'approve_date',
        'description',
        'status',
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
}
