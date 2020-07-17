<?php

namespace App\Models\Inventory\Export;

use App\Models\Company\Relationship;
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
        'signing_date',
        'expiry_date',
        'loading_port',
        'dest_port',
        'shipment_time',
        'tolerance_limit',
        'importer_bank_id',
        'exporter_bank_id',
        'fc_amt',
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
}
