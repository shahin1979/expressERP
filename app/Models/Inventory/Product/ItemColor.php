<?php

namespace App\Models\Inventory\Product;

use App\Models\Common\UserActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;
use Yajra\DataTables\DataTables;

class ItemColor extends Model
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
        'description',
        'status',
        'locale',
        'user_id',
    ];
}
