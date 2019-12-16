<?php

namespace App\Models\Company;

use App\Models\Common\AppModule;
use Illuminate\Database\Eloquent\Model;

class CompanyModule extends Model
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
        'status',
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
