<?php

namespace App\Models\Inventory\Import;

use App\Models\Company\Relationship;
use App\Models\Inventory\Movement\TransProduct;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportLCRegister extends Model
{
    use HasFactory;

    protected $table = "import_lc_registers";

    protected $guarded = ['id', 'created_at','updated_at'];

    protected $fillable = [
        'company_id',
        'invoice_no',
        'supplier_id',
        'import_lc_no',
        'lc_date',
        'open_date',
        'expiry_date',
        'shipment_date',
        'importer_bank_id',
        'exporter_bank_id',
        'currency',
        'contract_amt',
        'exchange_rate',
        'bdt_amt',
        'description',
        'status',
        'approve_date',
        'approved_by',
        'payment',
        'user_id',
        'old_no',
        'extra_field',
    ];

    public function supplier()
    {
        return $this->belongsTo(Relationship::class,'supplier_id','id');
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
