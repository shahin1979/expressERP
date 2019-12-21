@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Locations</li>
        </ol>
    </nav>

    <div class="row" id="top-head">
        <div class="col-md-3">
            <div class="pull-left">
                <button type="button" class="btn btn-location-add btn-success"><i class="fa fa-plus"></i>New Location</button>
            </div>
        </div>
        <div class="col-md-3">
            <div class="pull-right">
                <button type="button" class="btn btn-print-location btn-success"><i class="fa fa-print"></i>Print</button>
            </div>
        </div>
    </div>

    <div class="row col-md-12 dataTables_wrapper" style="overflow-x:auto;">
        <table class="table table-bordered table-hover table-responsive" id="location-table">
            <thead style="background-color: #b0b0b0">
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>


    {!! Form::open(['url'=>'#','method'=>'POST','id'=>'add-location']) !!}
    <div id="new-location" class="col-md-8">
        <table width="50%" class="table table-bordered table-striped table-hover">
            <tbody>
            <tr>
                <td><label for="name" class="control-label">Name</label></td>
                <td><input id="name" type="text" class="form-control" name="name" value="" required autofocus></td>
            </tr>
            <tr>
                <td><label for="group_name">Has Sub Category ?</label></td>
                <td>{!! Form::select('location_type',['F'=>'Factory','D'=>'Duty Location'],null,array('id'=>'location_type','class'=>'form-control')) !!}</td>
            </tr>
            </tbody>

            <tfoot>
            <tr>
                <td colspan="4"><button type="submit" id="btn-location-save" class="btn btn-primary btn-location-save">Submit</button></td>
            </tr>
            </tfoot>

        </table>
    </div>
    {!! Form::close() !!}


    {!! Form::open(['url'=>'location/categoryIndex','method'=>'POST']) !!}
    <div id="edit-location" class="col-md-8">
        <table width="50%" class="table table-bordered table-striped table-hover">
            <tbody>
            <tr>
                <td><label for="name" class="control-label">Category Name</label></td>
                <td><input id="name" type="text" class="form-control" name="name" value="" required autofocus></td>
            </tr>
            <tr>
                <td><label for="group_name">Has Sub Category ?</label></td>
                <td><input type="checkbox" name="sub_category" data-toggle="toggle" data-onstyle="primary"></td>
            </tr>
            </tbody>

            <tfoot>
            <tr>
                <td colspan="4"><button type="submit" id="btn-category-save" class="btn btn-primary btn-category-save">Submit</button></td>
            </tr>
            </tfoot>

        </table>
    </div>
    {!! Form::close() !!}



@endsection

@push('scripts')

    <script>

        $(document).ready(function(){

            $('#new-location').hide();
            $('#edit-location').hide();

        });

        $(function() {
            var table= $('#location-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'locationDBData',
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'location_type', name: 'location_type' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
                ]
            });
        });

        $(document).on('click', '.btn-location-add', function (e) {
            $('#new-location').show();
            $('#location-table').parents('div.dataTables_wrapper').first().hide();
            $('#top-head').hide();
        });


        // add new project

        $(document).on('click', '.btn-location-save', function (e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = 'locationIndex';
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',

                data: $('#add-location').serialize(),

                error: function (request, status, error) {
                    alert(request.responseText);
                },

                success: function (data) {

                    alert(data.success);
                    $('#new-location').hide();
                    $('#location-table').parents('div.dataTables_wrapper').first().show();
                    $('#top-head').show();
                    $('#location-table').DataTable().draw(false);
                }
            });
        });

    </script>

@endpush
