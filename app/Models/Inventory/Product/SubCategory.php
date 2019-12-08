<?php

namespace App\Models\Inventory\Product;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SubCategory extends Model
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
        'CATEGORY_ID',
        'NAME',
        'ALIAS',
        'ACC_NO',
        'STATUS',
        'LOCALE',
        'USER_ID',
    ];

    public function group()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
}
