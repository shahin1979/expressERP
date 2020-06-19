<?php

namespace App\Models\Inventory\Product;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SubCategory extends Model
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
        'category_id',
        'name',
        'alias',
        'acc_in_stock',
        'acc_out_stock',
        'status',
        'locale',
        'user_id',
    ];

    public function group()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
}
