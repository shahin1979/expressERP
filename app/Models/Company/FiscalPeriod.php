<?php

namespace App\Models\Company;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FiscalPeriod extends Model
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
        'fiscal_year',
        'year',
        'fp_no',
        'month_serial',
        'month_name',
        'start_date',
        'end_date',
        'status',
        'depreciation',
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
