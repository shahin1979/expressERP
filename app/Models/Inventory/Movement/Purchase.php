<?php

namespace App\Models\Inventory\Movement;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table= 'purchases';

    protected $guarded = ['id', 'created_at','updated_at','deleted_at'];

    protected $fillable = [
        'company_id',
        'ref_no',
        'contra_ref',
        'invoice_no',
        'purchase_type',
        'po_date',
        'invoice_date',
        'invoice_amt',
        'paid_amt',
        'discount_type',
        'discount',
        'discount_amt',
        'due_amt',
        'authorized_by',
        'description',
        'status',
        'stock_status',
        'user_id',
        'extra_field',
    ];

    public function items()
    {
        return $this->hasMany(TransProduct::class,'ref_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
