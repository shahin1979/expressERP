<?php

namespace App\Models\Human\Employee;

use Illuminate\Database\Eloquent\Model;

class EmpPersonal extends Model
{
    protected $guarded = ['id','created_at','updated_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'religion_id',
        'employee_id',
        'title',
        'name',
        'first_name',
        'middle_name',
        'last_name',
        'photo',
        'signature',
        'email',
        'pr_address1',
        'pr_address2',
        'pr_district',
        'pr_police_station',
        'pr_post_code',
        'pm_address1',
        'pm_address2',
        'pm_district',
        'pm_police_station',
        'pm_post_code',
        'm_address1',
        'm_address2',
        'm_district',
        'm_police_station',
        'm_post_code',
        'phone',
        'mobile',
        'biography',
        'father_name',
        'mother_name',
        'marital_status',
        'spouse_name',
        'dob',
        'gender',
        'blood_group',
        'last_education',
        'prof_speciality',
        'national_id',
        'entry_status',
        'status',
        'user_id',
    ];

    public $timestamps = false;
}
