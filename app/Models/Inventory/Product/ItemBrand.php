<?php

namespace App\Models\Inventory\Product;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ItemBrand extends Model
{
    use LogsActivity;

    protected $guarded = ['id', 'created_at','updated_at'];

    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated','deleted'];
    protected static $logOnlyDirty = true;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'name',
        'manufacturer',
        'image_path',
        'status',
        'origin',
        'locale',
        'user_id',
    ];
}
