<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class CompanyProperty extends Model
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
        'project',
        'inventory',
        'auto_ledger',
        'auto_delivery',
        'cash',
        'bank',
        'sales',
        'purchase',
        'capital',
        'currency',
        'fp_start',
        'trans_min_date',
        'company_logo',
        'company_logo2',
        'posted',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
