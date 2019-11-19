<?php

namespace App\Models\Projects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = ['ID', 'CREATED_AT','UPDATED_AT'];

    protected $fillable = [
        'COMPANY_ID',
        'PROJECT_CODE',
        'PROJECT_TYPE',
        'PROJECT_NAME',
        'PROJECT_DESC',
        'PROJECT_REF',
        'START_DATE',
        'END_DATE',
        'STATUS',
        'BUDGET',
        'EXPENSE',
        'INCOME',
        'USER_ID',
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
