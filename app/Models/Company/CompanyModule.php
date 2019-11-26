<?php

namespace App\Models\Company;

use App\Models\Common\AppModule;
use Illuminate\Database\Eloquent\Model;

class CompanyModule extends Model
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
        'STATUS',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function module()
    {
        return $this->belongsTo(AppModule::class,'module_id','id');
    }
}
