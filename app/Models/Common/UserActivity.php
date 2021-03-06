<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    protected $guarded = ['id', 'created_at'];

    protected $fillable = [
        'company_id',
        'menu_id',
        'user_id',
        'updated_at'
    ];



    public function menus()
    {
        return $this->belongsTo(MenuItem::class,'menu_id','id');
    }
}
