<?php

namespace App\Models\Security;

use App\Models\Common\MenuItem;
use Illuminate\Database\Eloquent\Model;

class UserPrivilege extends Model
{
    protected $guarded = ['ID', 'CREATED_AT','UPDATED_AT'];
//    protected $dates = ['FPSTART'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'COMPANY_ID',
        'MODULE_ID',
        'USER_ID',
        'MENU_ID',
        'VIEW',
        'ADD',
        'EDIT',
        'DELETE',
        'PRIVILEGE',
        'APPROVER_ID',
    ];

    public function menus()
    {
        return $this->belongsTo(MenuItem::class,'menu_id','id');
    }

}
