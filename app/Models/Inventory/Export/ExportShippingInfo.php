<?php

namespace App\Models\Inventory\Export;

use Illuminate\Database\Eloquent\Model;

class ExportShippingInfo extends Model
{
    protected $table = "export_shipping_info";

    protected $guarded = ['id', 'created_at','updated_at'];

    protected $fillable = [
        'company_id',
        'invoice_id',
        'challan_id',
        'vessel_no',
        'shipping_date',
        'shipping_ref',
        'packing',
        'user_id',
    ];
}
