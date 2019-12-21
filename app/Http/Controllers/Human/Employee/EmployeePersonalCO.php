<?php

namespace App\Http\Controllers\Human\Employee;

use App\Http\Controllers\Controller;
use App\Models\Common\Bangladesh;
use App\Models\Human\Common\Religion;
use App\Models\Human\Employee\EmpPersonal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class EmployeePersonalCO extends Controller
{
    public function index()
    {
        $districts = Bangladesh::query()->where('lang','en')->distinct()->orderBy('district')->pluck('district as name','district');

        $posts = Bangladesh::query()->where('lang','en')
            ->select(DB::raw("concat(concat(post_code, ' : '), post_office) as post_office"), 'post_code')
            ->orderBy('post_code')->pluck('post_office','post_code');

        $religions = Religion::query()->pluck('name','id');

        return view('human.employee.employee-personal-index',compact('districts','posts','religions'));
    }

    public function getDTData()
    {
        $employees = EmpPersonal::query()->where('company_id',$this->company_id)
            ->get();

        //href="#modal-update-employee" data-target="#modal-update-employee" data-toggle="modal"

        return DataTables::of($employees)

            ->addColumn('action', function ($employees) {

                return '<div class="btn-group btn-group-sm" role="group" aria-label="Action Button">
                    <button data-remote="view/'.$employees->id.'"  type="button" class="btn btn-view btn-sm btn-secondary"><i class="fa fa-open">View</i></button>
                    <button data-remote="edit/'. $employees->id . '"
                        type="button" class="btn btn-sm btn-employee-edit btn-primary pull-center"><i class="fa fa-edit" >Edit</i></button>

                    <button data-id="'. $employees->id . '"
                        data-name="'. $employees->name . '"
                        data-empid="'. $employees->employee_id . '"
                       type="button" class="btn btn-photo-sign btn-sm btn-secondary"><i class="fa fa-open">Image</i></button>

                    <button data-remote="education/'.$employees->id.'" data-rowid="'. $employees->id . '"   type="button" class="btn btn-education btn-sm btn-info"><i class="fa fa-open">Education</i></button>
                    </div>
                    <br/>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Action Button">

                    <button data-remote="dependant/'.$employees->id.'" data-rowid="'. $employees->id . '"   type="button" class="btn btn-dependant btn-sm btn-amber"><i class="fa fa-open">Dependant</i></button>
                    <button data-remote="posting/'.$employees->id.'"  data-rowid="'. $employees->id . '" type="button" class="btn btn-posting btn-sm btn-info"><i class="fa fa-open">Posting</i></button>
                    <button data-remote="promotion/'.$employees->id.'"  data-rowid="'. $employees->id . '" type="button" class="btn btn-promotion btn-sm btn-default"><i class="fa fa-open">Promotion</i></button>
                    <button data-remote="idcard/'.$employees->id.'"  data-rowid="'. $employees->id . '" type="button" class="btn btn-idcard btn-sm btn-danger"><i class="fa fa-open">Card</i></button>
                    </div>
                    ';
            })

            ->addColumn('status', function ($employees) {

                return $employees->status == true ? 'Active' : 'Disabled';
            })



//            ->addColumn('designation', function ($employees) {
//
//
//                return isset($employees->professional->designation_id) ? $employees->professional->designation->name . '<br/> <span style="color: #0c5460">'.$employees->professional->department->name .'</span>' : '';
//            })

            ->editColumn('showimage', function ($employees) {
                if (!isset($employees->photo)) {
                    return "Photo";
                }
                return '<img src="' . asset($employees->photo) .
                    '" alt=" " style="height: 50px; width: 50px;" >';
            })

            ->editColumn('signature', function ($employees) {
                if (!isset($employees->photo)) {
                    return "Signature";
                }
                return '<img src="' . asset($employees->signature) .
                    '" alt=" " style="height: 50px; width: 50px;" >';
            })

            ->rawColumns(['action','status','showimage','signature'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request['company_id'] = $this->company_id;
        $request['user_id'] = $this->user_id;
        $request['status'] = true;
//        $request['religion_id'] = $request['religion_id'];
//        $request['name'] = $request['name'];
//        $request['EMPLOYEE_ID'] = $request['employee_id'];
        $request['marital_status'] = $request->has('marital_status') ? true : false;
//        $request['SPOUSE_NAME'] = $request['spouse_name'];
//        $request['FATHER_NAME'] = $request['father_name'];
//        $request['MOTHER_NAME'] = $request['mother_name'];
//        $request['PHONE'] = $request['phone'];
//        $request['MOBILE'] = $request['mobile'];
//        $request['PM_ADDRESS1'] = $request['pm_address1'];
//        $request['PM_ADDRESS2'] = $request['pm_address2'];
//        $request['PM_DISTRICT'] = $request['pm_district'];
//        $request['PM_POST_CODE'] = $request['pm_post_code'];
//        $request['PR_ADDRESS1'] = $request['pr_address1'];
//        $request['PR_ADDRESS2'] = $request['pr_address2'];
//        $request['PR_DISTRICT'] = $request['pr_district'];
//        $request['PR_POST_CODE'] = $request['pr_post_code'];
//        $request['M_ADDRESS1'] = $request['m_address1'];
//        $request['M_ADDRESS2'] = $request['m_address2'];
//        $request['M_DISTRICT'] = $request['m_district'];
//        $request['M_POST_CODE'] = $request['m_post_code'];
        $request['dob'] = Carbon::createFromFormat('d-m-Y',$request['dob'])->format('Y-m-d') ;
//        $request['BLOOD_GROUP'] = $request['blood_group'];
//        $request['GENDER'] = $request['gender'];
//        $request['LAST_EDUCATION'] = $request['last_education'];
//        $request['PROF_SPECIALITY'] = $request['prof_speciality'];
//        $request['BIOGRAPHY'] = $request['biography'];
//        $request['NATIONAL_ID'] = $request['national_id'];



        DB::begintransaction();

        try {

            EmpPersonal::query()->create($request->all());

        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error', $error);
        }

        DB::commit();

        return redirect()->action('Human\Employee\EmployeePersonalCO@index')->with('success','New Employee Added :  '.$request['name']);
    }

    public function uploadImage(Request $request)
    {
//        dd($request->all());

        DB::beginTransaction();

        try {

            if($request->hasfile('photo-image'))
            {
                $file = $request->file('photo-image');

                $name = $request['employee_personal_id_for_image'].'.'.$file->getClientOriginalExtension();
                $file->move(public_path().'/photo/', $name);

                EmpPersonal::query()->where('id',$request['employee_personal_id_for_image'])->update(['photo'=>'photo/'.$name]);
            }

            if($request->hasfile('sign-image'))
            {
                $file = $request->file('sign-image');

                $name = $request['employee_personal_id_for_image'].'.'.$file->getClientOriginalExtension();
                $file->move(public_path().'/sign/', $name);

                EmpPersonal::query()->where('id',$request['employee_personal_id_for_image'])->update(['signature'=>'sign/'.$name]);
            }



        }catch (\Exception $e)
        {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->back()->with('error','Not Saved '.$error);
        }

        DB::commit();

        return redirect()->action('Human\Employee\EmployeePersonalCO@index')->with('success','Image Successfully Updated');
    }
}
