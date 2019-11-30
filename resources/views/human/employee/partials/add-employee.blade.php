<div class="employee-add" id="employee-add">

    {!! Form::open(array('id'=>'transForm','url'=>'employee/personal/save','method','post')) !!}

    <table class="table table-bordered table-hover">
        <tbody>
        <tr>
            <td style="text-align: right">Title</td>
            <td><input type="text" name="title" id="title" class="form-control" autofocus value="" /></td>
            <td style="text-align: right">Name</td>
            <td><input type="text" name="name" id="name" class="form-control" required value="" /></td>

        </tr>

        <tr class="table-success">
            <td style="text-align: right">Employee ID</td>
            <td><input type="text" name="employee_id" id="employee_id" class="form-control" value="" /></td>
            <td style="text-align: right">Gender</td>
            <td>{!! Form::select('gender',['M' => 'Male', 'F' => 'Female','O'=>'Others'],'M',array('id'=>'gender','class'=>'form-control')) !!}</td>
        </tr>

        <tr>
            <td style="text-align: right">Father's Name</td>
            <td><input type="text" name="father_name" id="father_name" class="form-control" required value="" /></td>
            <td style="text-align: right">Mother's Name</td>
            <td><input type="text" name="mother_name" id="mother_name" class="form-control" value="" /></td>

        </tr>

        <tr>
            <td style="text-align: right">Marital Status</td>
            <td><input type="checkbox" id="marital_status" name="marital_status" data-toggle="toggle" data-onstyle="primary" data-on="Yes" data-off="No"></td>
            <td style="text-align: right">Spouse Name</td>
            <td><input type="text" name="spouse_name" id="spouse_name" class="form-control" value="" /></td>

        </tr>

        <tr>
            <td colspan="4">
                <table class="table table-bordered table-primary">
                    <thead>
                    <tr>
                        <th colspan="4">Permanent Address</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Address Line 1</td>
                        <td><input type="text" name="pm_address1" id="pm_address1" class="form-control" required value="" /></td>
                        <td>Address Line 2</td>
                        <td><input type="text" name="pm_address2" id="pm_address2" class="form-control" value="" /></td>
                    </tr>
                    <tr>
                        <td>District</td>
                        <td>{!! Form::select('pm_district',$districts,null,array('id'=>'pm_district','class'=>'form-control')) !!}</td>
                        <td>Post Code</td>
                        <td>{!! Form::select('pm_post_code',$posts,null,array('id'=>'pm_post_code','class'=>'form-control')) !!}</td>
                    </tr>
                    </tbody>

                </table>
            </td>
        </tr>

        <tr>
            <td colspan="4">
                <table class="table table-bordered table-success">
                    <thead>
                    <tr>
                        <th colspan="2">Present Address</th>
                        <th>Clisk if same as Permanent Address</th>
                        <th><input type="checkbox" id="same_address" name="same_address" data-toggle="toggle" data-onstyle="primary" data-on="Yes" data-off="No"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Address Line 1</td>
                        <td><input type="text" name="pr_address1" id="pr_address1" class="form-control" required value="" /></td>
                        <td>Address Line 2</td>
                        <td><input type="text" name="pr_address2" id="pr_address2" class="form-control" value="" /></td>
                    </tr>
                    <tr>
                        <td>District</td>
                        <td>{!! Form::select('pr_district',$districts,null,array('id'=>'pr_district','class'=>'form-control')) !!}</td>
                        <td>Post Code</td>
                        <td>{!! Form::select('pr_post_code',$posts,null,array('id'=>'pr_post_code','class'=>'form-control')) !!}</td>
                    </tr>
                    </tbody>

                </table>
            </td>
        </tr>

        <tr>
            <td colspan="4">
                <table class="table table-bordered table-success">
                    <thead>
                    <tr>
                        <th colspan="2">Mailing / Office Address</th>
                        <th>Clisk if same as Present Address</th>
                        <th><input type="checkbox" id="mail_address" name="mail_address" data-toggle="toggle" data-onstyle="primary" data-on="Yes" data-off="No"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Address Line 1</td>
                        <td><input type="text" name="m_address1" id="m_address1" class="form-control" value="" /></td>
                        <td>Address Line 2</td>
                        <td><input type="text" name="m_address2" id="m_address2" class="form-control" value="" /></td>
                    </tr>
                    <tr>
                        <td>District</td>
                        <td>{!! Form::select('m_district',$districts,null,array('id'=>'m_district','class'=>'form-control')) !!}</td>
                        <td>Post Code</td>
                        <td>{!! Form::select('m_post_code',$posts,null,array('id'=>'m_post_code','class'=>'form-control')) !!}</td>
                    </tr>
                    </tbody>

                </table>
            </td>
        </tr>

        <tr>
            <td style="text-align: right">Mobile</td>
            <td><input type="text" name="mobile" id="mobile" class="form-control" value="" /></td>
            <td style="text-align: right">E Mail</td>
            <td><input type="email" name="email" id="email" class="form-control" value="" /></td>
        </tr>

        <tr>
            <td style="text-align: right">Birth Date</td>
            <td><input type="text" name="dob" id="dob" class="form-control" value="{!! \Carbon\Carbon::now()->format('d-m-Y') !!}" /></td>
            <td style="text-align: right">Blood Group</td>
            <td><input type="text" name="blood_group" id="blood_group" class="form-control" value="" /></td>
        </tr>

        <tr class="table-success">
            <td style="text-align: right">National ID</td>
            <td><input type="text" name="national_id" id="national_id" class="form-control" value="" /></td>
            <td style="text-align: right">Last Education</td>
            <td><input type="text" name="last_education" id="last_education" class="form-control" value="" /></td>
        </tr>

        <tr class="table-success">
            <td style="text-align: right">Religion</td>
            <td>{!! Form::select('religion_id',$religions,null,array('id'=>'religion_id','class'=>'form-control')) !!}</td>
            <td style="text-align: right">Speciality</td>
            <td><input type="text" name="prof_speciality" id="prof_speciality" class="form-control" value="" /></td>
        </tr>

        <tr class="table-success">
            <td style="text-align: right">Short Biography</td>
            <td colspan="3"><textarea class="form-control" name="biography" cols="50" rows="2" id="biography" placeholder="Short Biography"></textarea></td>
        </tr>


        </tbody>

        <tfoot>
        <tr>
            <td colspan="4"><button type="submit" id="btn-personal" class="btn btn-primary btn-register btn-personal">Submit</button></td>
        </tr>
        </tfoot>

    </table>
    {!! Form::close() !!}
</div>

