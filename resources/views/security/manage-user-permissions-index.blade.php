@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <link href="{!! asset('assets/bootstrap4-toggle-3.6.1/css/bootstrap4-toggle.min.css') !!}" rel="stylesheet">
    <script src="{!! asset('assets/bootstrap4-toggle-3.6.1/js/bootstrap4-toggle.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="black-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">User permission</li>
        </ol>
    </nav>


    <div class="row justify-content-center dataTables_wrapper" id="dt-user">
        <div class="col-md-6" style="overflow-x:auto;">
            <table class="table table-bordered table-hover table-responsive" id="users-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    {!! Form::open(array('id'=>'transForm','url'=>'security/permission/store','method','post')) !!}

    <div id="accordion">


        <table class="table table-bordered table-primary">
            <thead>
            <tr>
                <th colspan="4" style="text-align: center">Set Permissions</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>User Name: </td>
                <td id="user-name"></td>
                <td>Email</td>
                <td id="user-email"></td>
            </tr>
            </tbody>
        </table>



        @foreach($modules as $i=>$module)

        <div class="card" >
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <button class="btn btn-link" onclick="return false;" data-toggle="collapse" data-target="#collapse-{!! $i !!}" aria-expanded="true" aria-controls="collapseOne">
                        <i class="fa fa-plus" aria-hidden="true"><span style="font-weight: bold; font-size: 20px">{!! $module->module->module_name !!}</span> </i>
                    </button>
                </h5>
            </div>

            <div id="collapse-{!! $i !!}" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">Click</th>
                                    <th>Menu</th>
                                    <th>View</th>
                                    <th>Add</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                    <th>Print</th>
                                </tr>

                            </thead>
                            <tbody>
                                @foreach($menus as $row)
                                    @if($module->module_id == $row->module_id)
                                        <tr class="{!! $row->menu_type == 'SM' ? 'table-success' : 'table-secondary' !!}">
                                            <td>{!! $row->id !!}</td>
                                            <td>{!! $row->name !!}</td>

                                            @if($row->menu_type == 'SM')
                                                <td><input type="checkbox" name="view[]" value="{!! $row->id !!}" class="view-chk-{!! $row->id !!}"  id="view-chk-{!! $row->id !!}" data-toggle="toggle" data-onstyle="primary"></td>
                                                <td width="150px"><input type="checkbox" value="{!! $row->id !!}" id="add-chk-{!! $row->id !!}" name="add[]" data-toggle="toggle" data-onstyle="primary"></td>
                                                <td width="150px"><input type="checkbox" value="{!! $row->id !!}" id="edit-chk-{!! $row->id !!}" name="edit[]" data-toggle="toggle" data-onstyle="primary"></td>
                                                <td width="150px"><input type="checkbox" value="{!! $row->id !!}" id="delete-chk-{!! $row->id !!}" name="delete[]" data-toggle="toggle" data-onstyle="primary"></td>
                                                <td width="150px"><input type="checkbox" value="{!! $row->id !!}" id="print-chk-{!! $row->id !!}" name="print[]" data-toggle="toggle" data-onstyle="primary"></td>
                                            @else
                                                 <td>View</td>
                                                 <td>Add</td>
                                                 <td>Edit</td>
                                                 <td>Delete</td>
                                                <td>Print</td>
                                             @endif

                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>

                        </table>

                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <input type="hidden" name="user_id" id="user_id"/>

        <div class="col-md-6">
            {!! Form::submit('SUBMIT',['class'=>'btn btn-primary button-control']) !!}
        </div>

    </div>



    {!! Form::close() !!}

@endsection

@push('scripts')

    <script>

        $('#accordion').hide();
        // $(document).ready(function(){
        //     $('#accordion').hide();
        // });

        $(function() {
            var table= $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'usersData',
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email', searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}

                ],
                order: [[ 1, "asc" ]]
            });
        });

        $('#users-table').on('click', '.btn-permission', function (e) {

            document.getElementById('user-name').innerHTML =$(this).data('name');
            document.getElementById('user-email').innerHTML =$(this).data('email');
            document.getElementById('user_id').value = $(this).data('id') ;

            $(this).parents('div.dataTables_wrapper').first().hide();

            // alert($(this).data('id'));

            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = $(this).data('remote');

            // confirm then
            $.ajax({
                url: url,
                type: "GET",
                dataType: "JSON",

                data: {method: '_GET', submit: true,},

                error: function (request, status, error) {
                    alert(request.responseText);
                },

                success: function (data) {


                    $.each(data, function (i, item) {

                        item.view == true ? $('#view-chk-' + item.menu_id).bootstrapToggle('on') : '';
                        item.view == true ? $("#view-chk-" + item.menu_id).prop("checked", true) : '';


                        item.add == true ? $('#add-chk-' + item.menu_id).bootstrapToggle('on') : '';
                        item.add == true ? $("#add-chk-" + item.menu_id).prop("checked", true) : '';

                        item.edit == true ? $('#edit-chk-' + item.menu_id).bootstrapToggle('on') : '';
                        item.edit == true ? $("#edit-chk-" + item.menu_id).prop("checked", true) : '';

                        item.delete == true ? $('#delete-chk-' + item.menu_id).bootstrapToggle('on') : '';
                        item.delete == true ? $("#delete-chk-" + item.menu_id).prop("checked", true) : '';

                    });

                    $('#accordion').show();
                }

            });
        });

        $('#transForm').on('click', '.btn-link', function (e) {
            e.preventDefault();
        });

    </script>

@endpush
