<?php

namespace App\Models\Company;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FiscalPeriod extends Model
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
        'FISCALYEAR',
        'YEAR',
        'FPNO',
        'MONTHSL',
        'MONTHNAME',
        'STARTDATE',
        'ENDDATE',
        'STATUS',
        'DEPRECIATION',
    ];


    public function getEndDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getStartDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
}
