<?php

namespace App\Models\Projects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = ['ID', 'CREATED_AT','UPDATED_AT'];

    protected $fillable = [
        'company_id',
        'project_code',
        'project_type',
        'project_name',
        'project_desc',
        'project_ref',
        'start_date',
        'end_date',
        'closing_date',
        'status',
        'budget',
        'expense',
        'income',
        'user_id',
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
