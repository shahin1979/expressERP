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
        'cash',
        'bank',
        'sales',
        'purchase',
        'capital',
        'currency',
        'fp_start',
        'posted',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}