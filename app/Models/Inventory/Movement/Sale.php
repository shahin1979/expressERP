<?php

namespace App\Models\Inventory\Movement;

use App\Models\Company\Relationship;
use App\Models\Inventory\Export\ExportContract;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Sale extends Model
{
    use LogsActivity;

    protected $table= 'sales';

    protected $guarded = ['id', 'created_at','updated_at','deleted_at'];
    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated','deleted'];
    protected static $logOnlyDirty = true;

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
        'export_contract_id',
        'fc_amt',
        'currency',
        'exchange_rate',
        'importer_bank_id',
        'exporter_bank_id',
        'loading_port',
        'destination_port',
        'applicants_bin',
        'color',
        'delivery_terms',
        'description',
        'status',
        'stock_status',
        'direct_delivery',
        'delivery_status',
        'delivery_challan_id',
        'shipment_status',
        'authorized_by',
        'authorized_date',
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

    public function customer()
    {
        return $this->belongsTo(Relationship::class,'customer_id','id');
    }

    public function contract()
    {
        return $this->belongsTo(ExportContract::class,'export_contract_id','id');
    }
}
