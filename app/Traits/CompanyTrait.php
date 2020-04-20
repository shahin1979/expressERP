<?php


namespace App\Traits;


use App\Models\Company\CompanyProperty;

trait CompanyTrait
{
    public function get_sales_account($company_id)
    {
        $company = CompanyProperty::query()->where('company_id',$company_id)->first();

        return $company->default_sales;
    }

    public function get_advance_sales_account($company_id)
    {
        $company = CompanyProperty::query()->where('company_id',$company_id)->first();

        return $company->default_sales;
    }

}
