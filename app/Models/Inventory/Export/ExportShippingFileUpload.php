<?php

namespace App\Models\Inventory\Export;

use Illuminate\Database\Eloquent\Model;

class ExportShippingFileUpload extends Model
{
    protected $table = "export_shipping_file_upload";

    protected $guarded = ['id', 'created_at','updated_at'];

    protected $fillable = [
        'company_id',
        'invoice_id',
        'challan_id',
        'forwarder',
        'vessel_no',
        'container',
        'size',
        'seal_no',
        'destination',
        'booking_no',
        'bl_no',
        'shipper',
        'cbm',
        'user_id',
    ];
}
