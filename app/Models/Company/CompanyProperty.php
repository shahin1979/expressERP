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
        'cost_center',
        'inventory',
        'auto_ledger',
        'auto_delivery',
        'cash',
        'bank',
        'sales',
        'purchase',
        'capital',
        'default_cash',
        'default_purchase',
        'default_sales',
        'advance_sales',
        'discount_sales',
        'discount_purchase',
        'default_sales_tax',
        'default_purchase_tax',
        'consumable_on_hand',
        'consumable_expense',
        'rm_in_hand',
        'work_in_progress',
        'finished_goods',
        'cost_of_goods_sold',
        'depreciation_account',
        'depreciation_frequency',
        'additional_charges',
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
