<?php

namespace App\Models\Inventory\Movement;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    protected $table= 'requisitions';

    protected $guarded = ['id', 'created_at','updated_at','deleted_at'];

    protected $fillable = [
        'company_id',
        'ref_no',
        'req_type',
        'req_date',
        'authorized_by',
        'description',
        'status',
        'user_id',
    ];

    public function items()
    {
        return $this->hasMany(TransProduct::class,'ref_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class,'authorized_by','id');
    }
}
