@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>
    <link href="{!! asset('assets/bootstrap4-toggle-3.6.1/css/bootstrap4-toggle.min.css') !!}" rel="stylesheet">
    <script src="{!! asset('assets/bootstrap4-toggle-3.6.1/js/bootstrap4-toggle.min.js') !!}"></script>

    <link href="{!! asset('assets/css/bootstrap-imageupload.css') !!}" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="{!! asset('assets/js/bootstrap-imageupload.js') !!}"></script>


    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Add Employee</li>
        </ol>
    </nav>



    <div class="container-fluid">

        <div class="row">
            <div class="col-md-6 add-btn" id="add-btn">
                <div class="pull-left">
{{--                    <button type="button" class="btn btn-employee btn-success" data-toggle="modal" data-target="#modal-new-employee"><i class="fa fa-plus"></i>New Employee</button>--}}
                    <button type="button" class="btn btn-employee btn-success"><i class="fa fa-plus"></i>New Employee</button>
                </div>
            </div>

            <div class="col-md-6">
                <div class="pull-right btn-back" id="btn-back">
                    <a class="btn btn-primary" href="{!! URL::previous() !!}"> <i class="fa fa-list"></i> Back </a>
                </div>
            </div>
        </div>


        <div class="row justify-content-center dataTables_wrapper">
            <div class="col-md-12" style="overflow-x:auto;">
                <table class="table table-bordered table-hover table-striped" id="employees-table">
                    <thead style="background-color: #b0b0b0">
                    <tr>
                        <th></th>
                        <th>Photo</th>
                        <th>Signature</th>
                        <th>ID</th>
                        <th>Name</th>
{{--                        <th>Designation <br/><span style="color: #0c5460">Department</span></th>--}}
                        <th>Mobile</th>
                        {{--<th>Entered</th>--}}
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>

        @include('human.employee.partials.add-employee')
        @include('human.employee.partials.emp-image-upload')

    </div> <!--/.Container-->



@endsection

@push('scripts')
    <script>
        $(function (){
            $(document).on("focus", "input:text", function() {
                $(this).select();
            });
        });

        $(document).ready(function(){

            $('#employee-add').hide();
            $('#emp-image').hide();

            $( "#dob" ).datetimepicker({
                format:'d-m-Y',
                timepicker: false,
                closeOnDateSelect: true,
                scrollInput : false,
                inline:false
            });

        });

        $(function() {
            var table= $('#employees-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'employeeDataTable',
                columns: [
                    { data: 'id', name: 'id', visible: false },
                    { data: 'showimage', name: 'showimage'},
                    { data: 'signature', name: 'signature'},
                    { data: 'employee_id', name: 'employee_id', defaultContent: ""},
                    { data: 'name', name: 'name'},
                    // { data: 'designation', name: 'designation'},
                    { data: 'mobile', name: 'mobile' },
                    // { data: 'user.name', name: 'user.name' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
                ],
                order: [ [0, 'desc'] ]
            });

            $(this).on("click", ".btn-photo-sign", function (e) {
                e.preventDefault();

                document.getElementById('employee_personal_id_for_image').value = $(this).data('id');

                $('#emp_name_for_photo').html($(this).data('name'));
                $('#emp_id_for_photo').html($(this).data('empid'));
                $('#emp-image').show();
                $('#employees-table').parents('div.dataTables_wrapper').first().hide();

            });





            // $("body").on("click", ".btn-create", function (e) {
            //     e.preventDefault();
            //
            //     var url = $(this).data('remote');
            //     window.location.href = url;
            //
            // });

            $(this).on("click", ".btn-view", function (e) {
                e.preventDefault();

                var url = $(this).data('remote');
                window.location.href = url;

            });

            $(this).on("click", ".btn-employee-edit", function (e) {
                e.preventDefault();

                var url = $(this).data('remote');
                window.location.href = url;

            });


            $(this).on("click", ".btn-dependant", function (e) {
                e.preventDefault();

                var url = $(this).data('remote');
                window.location.href = url;

            });

            $(this).on("click", ".btn-education", function (e) {
                e.preventDefault();

                var url = $(this).data('remote');
                window.location.href = $(this).data('remote');

            });


            $(this).on("click", ".btn-posting", function (e) {
                e.preventDefault();

                var url = $(this).data('remote');
                window.location.href = $(this).data('remote');

            });

            $(this).on("click", ".btn-promotion", function (e) {
                e.preventDefault();

                var url = $(this).data('remote');
                window.location.href = $(this).data('remote');

            });

            $(this).on("click", ".btn-idcard", function (e) {
                e.preventDefault();

                var emp_id = $(this).data('rowid');

                document.getElementById('emp_id_card').value = emp_id;
                $('#modal-card-print').modal('show');

                // var url = $(this).data('remote');
                // window.location.href = $(this).data('remote');

            });

        });

       // DATA TABLE END


        $(function() {
            $('#same_address').change(function() {
                // $('#console-event').html('Toggle: ' + $(this).prop('checked'))

                if($(this).prop('checked'))
                {
                    document.getElementById('pr_address1').value = document.getElementById('pm_address1').value;
                    document.getElementById('pr_address2').value = document.getElementById('pm_address2').value;
                    document.getElementById('pr_district').value = document.getElementById('pm_district').value;
                    document.getElementById('pr_post_code').value = document.getElementById('pm_post_code').value;
                }

                if(!$(this).prop('checked'))
                {
                    document.getElementById('pr_address1').value = '';
                    document.getElementById('pr_address2').value = '';
                    document.getElementById('pr_district').value = 'Bagerhat';
                    document.getElementById('pr_post_code').value = 1000;
                }

            });

            $('#mail_address').change(function() {

                if($(this).prop('checked'))
                {
                    document.getElementById('m_address1').value = document.getElementById('pr_address1').value;
                    document.getElementById('m_address2').value = document.getElementById('pr_address2').value;
                    document.getElementById('m_district').value = document.getElementById('pr_district').value;
                    document.getElementById('m_post_code').value = document.getElementById('pr_post_code').value;
                }

                if(!$(this).prop('checked'))
                {
                    document.getElementById('m_address1').value = '';
                    document.getElementById('m_address2').value = '';
                    document.getElementById('m_district').value = 'Bagerhat';
                    document.getElementById('m_post_code').value = 1000;
                }

            })
        });


        $(document).on('click', '.btn-employee', function (e) {

            $('#employee-add').show();
            $('#employees-table').parents('div.dataTables_wrapper').first().hide();
            $('.add-btn').hide();

            document.getElementById("btn-back").classList.remove('pull-right');
            document.getElementById("btn-back").classList.add('pull-left');

        });

    </script>


@endpush
