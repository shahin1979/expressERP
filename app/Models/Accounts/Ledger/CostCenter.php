<?php

namespace App\Models\Accounts\Ledger;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class CostCenter extends Model
{
    protected $table = "cost_centers";

    protected $guarded = ['id', 'created_at','updated_at'];

    protected $fillable = [
        'company_id',
        'fiscal_year',
        'name',
        'current_year_budget',
        'budget_01',
        'budget_02',
        'budget_03',
        'budget_04',
        'budget_05',
        'budget_06',
        'budget_07',
        'budget_08',
        'budget_09',
        'budget_10',
        'budget_11',
        'budget_12',
        'status',
        'user_id',
        'old_id'
    ];
}
