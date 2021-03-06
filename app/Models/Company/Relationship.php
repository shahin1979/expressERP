<?php

namespace App\Models\Company;

use App\Models\Accounts\Ledger\GeneralLedger;
use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
    protected $guarded = ['id', 'created_at','updated_at','deleted_at'];

    protected $fillable = [
        'company_id',
        'relation_type',
        'name',
        'tax_number',
        'email',
        'ledger_acc_no',
        'street',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'phone_number',
        'fax_number',
        'website',
        'assigned',
        'default_price',
        'default_discount',
        'default_payment_term',
        'default_payment_method',
        'min_order_value',
        'status',
        'locale',
        'old_id',
        'user_id',
    ];

    public function acc_name()
    {
        return $this->belongsTo(GeneralLedger::class,'ledger_acc_no','acc_no');
    }
}
