<?php

namespace App\Models\Inventory\Movement;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table= 'sales';

    protected $guarded = ['id', 'created_at','updated_at','deleted_at'];

    protected $fillable = [
        'company_id',
        'invoice_no',
        'customer_id',
        'invoice_type',
        'invoice_date',
        'invoice_amt',
        'paid_amt',
        'discount_type',
        'discount',
        'discount_amt',
        'due_amt',
        'authorized_by',
        'authorized_date',
        'description',
        'status',
        'stock_status',
        'direct_delivery',
        'delivery_status',
        'account_post',
        'account_voucher',
        'user_id',
        'extra_field',
        'old_invoice_no',
    ];

    public function items()
    {
        return $this->hasMany(TransProduct::class,'ref_no','invoice_no');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
