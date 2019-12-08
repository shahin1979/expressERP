<?php

namespace App\Models\Inventory\Product;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    use LogsActivity;

    protected $guarded = ['ID', 'CREATED_AT','UPDATED_AT'];
    protected static $logAttributes = ['*'];
    protected static $recordEvents = ['updated','deleted'];
    protected static $logOnlyDirty = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'COMPANY_ID',
        'NAME',
        'ALIAS',
        'HAS_SUB',
        'ACC_NO',
        'LOCALE',
        'INVENTORY_AMT',
        'ACCOUNT_BAL',
        'USER_ID',
        'STATUS',
    ];

}
