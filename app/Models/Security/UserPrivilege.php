<?php

namespace App\Models\Security;

use App\Models\Common\MenuItem;
use Illuminate\Database\Eloquent\Model;

class UserPrivilege extends Model
{
    protected $guarded = ['id', 'created_at','updated_at'];
//    protected $dates = ['FPSTART'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'module_id',
        'user_id',
        'menu_id',
        'view',
        'add',
        'edit',
        'delete',
        'print',
        'privilege',
        'approver_id',
    ];

    public function menus()
    {
        return $this->belongsTo(MenuItem::class,'menu_id','id');
    }

}
