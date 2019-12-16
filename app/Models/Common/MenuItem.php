<?php

namespace App\Models\Common;

use App\Models\Company\CompanyModule;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{

    protected $guarded = ['id', 'created_at','updated_at'];

    protected $fillable = [
        'company_id',
        'module_id',
        'nav_label',
        'div_class',
        'i_class',
        'menu_type',
        'menu_prefix',
        'name',
        'show',
        'url',
        'status',
    ];



    public function module()
    {
        return $this->belongsTo(CompanyModule::class,'module_id','module_id');
    }
}
