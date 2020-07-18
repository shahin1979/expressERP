<?php

namespace App\Models\Inventory\Export;

use App\Models\Company\Relationship;
use App\Models\Inventory\Movement\TransProduct;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ExportContract extends Model
{
    protected $table = "export_contracts";

    protected $guarded = ['id', 'created_at','updated_at'];

    protected $fillable = [
        'company_id',
        'invoice_no',
        'customer_id',
        'export_contract_no',
        'contract_date',
        'signing_date',
        'expiry_date',
        'loading_port',
        'dest_port',
        'shipment_time',
        'tolerance_limit',
        'importer_bank_id',
        'exporter_bank_id',
        'contract_amt',
        'currency',
        'exchange_rate',
        'description',
        'status',
        'approve_date',
        'approved_by',
        'payment',
        'user_id',
        'old_no',
        'extra_field',
    ];

    public function customer()
    {
        return $this->belongsTo(Relationship::class,'customer_id','id');
    }

    public function items()
    {
        return $this->hasMany(TransProduct::class,'ref_no','invoice_no');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
