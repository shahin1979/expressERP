<?php

namespace App\Models\Inventory\Movement;

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
        'requisition_for',
        'authorized_by',
        'description',
        'status',
        'user_id',
    ];
}
