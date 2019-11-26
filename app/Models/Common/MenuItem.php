<?php

namespace App\Models\Common;

use App\Models\Company\CompanyModule;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    public function module()
    {
        return $this->belongsTo(CompanyModule::class,'module_id','module_id');
    }
}
