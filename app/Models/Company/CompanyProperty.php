<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class CompanyProperty extends Model
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
            'PROJECT',
            'INVENTORY',
            'AUTO_LEDGER',
            'CASH',
            'BANK',
            'SALES',
            'PURCHASE',
            'CAPITAL',
            'CURRENCY',
            'FPSTART',
            'POSTED',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
