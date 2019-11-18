<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Model;

class EmpPersonal extends Model
{
    protected $guarded = ['ID','CREATED_AT','UPDATED_AT'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'COMPANY_ID',
        'RELIGION_ID',
        'EMPLOYEE_ID',
        'FIRST_NAME',
        'MIDDLE_NAME',
        'LAST_NAME',
        'FULL_NAME',
        'PHOTO',
        'SIGNATURE',
        'EMAIL',
        'PR_ADDRESS',
        'PR_DISTRICT',
        'PR_POLICE_STATION',
        'PR_POST_CODE',
        'PM_ADDRESS',
        'PM_DISTRICT',
        'PM_POLICE_STATION',
        'PM_POST_CODE',
        'M_ADDRESS',
        'M_DISTRICT',
        'M_POLICE_STATION',
        'M_POST_CODE',
        'PHONE',
        'MOBILE',
        'BIOGRAPHY',
        'FATHER_NAME',
        'MOTHER_NAME',
        'MARITAL_STATUS',
        'SPOUSE_NAME',
        'DOB',
        'GENDER',
        'BLOOD_GROUP',
        'LAST_EDUCATION',
        'PROF_SPECIALITY',
        'NATIONAL_ID',
        'STATUS',
        'USER_ID',
    ];

    public $timestamps = false;
}
