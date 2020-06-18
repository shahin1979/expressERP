@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Item Brands</li>
        </ol>
    </nav>

    <div class="container-fluid">

        <div class="row" id="top-head">

            <div class="col-md-4">
                <div class="pull-left">
                    <button type="button" class="btn btn-brand-add btn-primary"><i class="fa fa-plus"></i>New Brand</button>
                </div>
                <div class="pull-right">
                    <button type="button" class="btn btn-print btn-info"><i class="fa fa-print"></i>Print</button>
                </div>
            </div>
{{--            <div class="col-md-2">--}}
{{--                <div class="pull-left">--}}
{{--                    <button type="button" class="btn btn-print btn-primary"><i class="fa fa-print"></i>Print</button>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>


        <div class="row col-md-12 dataTables_wrapper" style="overflow-x:auto;">
            <table class="table table-bordered table-hover table-responsive" id="brands-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Name</th>
                    <th>Manufacturer</th>
                    <th>Origin</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>


        {!! Form::open(['url'=>'product/itemBrandIndex','method'=>'POST']) !!}

        <div id="new-brand" class="col-md-8">

            <div class="row" id="top-head">
                <div class="col-md-4">
                    <div class="pull-left">
                        <button type="button" class="btn btn-back btn-primary"><i class="fa fa-backward"></i>Back</button>
                    </div>
                </div>
            </div>

            <table width="50%" class="table table-bordered table-striped table-hover">
                <tbody>
                    <tr>
                        <td><label for="name" class="control-label">Brand Name</label></td>
                        <td><input id="name" type="text" class="form-control" name="name" value="" required autofocus></td>
                    </tr>

                    <tr>
                        <td><label for="manufacturer" class="control-label">Manufacturer</label></td>
                        <td><input id="manufacturer" type="text" class="form-control" name="manufacturer" value=""></td>
                    </tr>

                    <tr>
                        <td><label for="origin" class="control-label">Origin</label></td>
                        <td>{!! Form::select('origin',['L'=>'Local','F'=>'Foreign','B'=>'Both'],null,array('id'=>'origin','class'=>'form-control')) !!}</td>
                    </tr>
                </tbody>

                <tfoot>
                <tr>
                    <td colspan="4"><button type="submit" id="btn-brand-save" class="btn btn-primary btn-unit-save">Submit</button></td>
                </tr>
                </tfoot>

            </table>
        </div>
        {!! Form::close() !!}

        {{--        <form action="#"  method="post" accept-charset="utf-8">--}}
        <div id="edit-brand" class="col-md-8">

            <div class="row">
                <div class="col-md-4">
                    <div class="pull-left">
                        <button type="submit" class="btn btn-back btn-primary"><i class="fa fa-backward"></i>Back</button>
                    </div>
                </div>
            </div>

            <table width="50%" class="table table-bordered table-striped table-hover">
                <tbody>
                <tr>
                    <td><label for="name" class="control-label">Brand Name</label></td>
                    <td><input id="name-for-edit" type="text" class="form-control" name="name-for-edit" value="" required autofocus></td>
                </tr>

                <tr>
                    <td><label for="manufacturer" class="control-label">Manufacturer Name</label></td>
                    <td><input id="manufacturer-for-edit" type="text" class="form-control" name="manufacturer-for-edit" value=""></td>
                </tr>

                <tr>
                    <td><label for="origin-for-edit" class="control-label">Origin</label></td>
                    <td>{!! Form::select('origin-for-edit',['L'=>'Local','F'=>'Foreign','B'=>'Both'],null,array('id'=>'origin-for-edit','class'=>'form-control')) !!}</td>
{{--                    <td><input id="origin-for-edit" type="text" class="form-control" name="origin-for-edit" value="" required></td>--}}
                </tr>

                </tbody>
                <input id="id-for-update" type="hidden" name="id-for-update"/>
                <tfoot>
                <tr>
                    <td colspan="4"><button type="submit" id="btn-brand-update" class="btn btn-primary btn-brand-update">Submit</button></td>
                </tr>
                </tfoot>

            </table>
        </div>
        {{--        </form>--}}

    </div>


@endsection

@push('scripts')

    <script>
        $(document).ready(function(){

            $('#new-brand').hide();
            $('#edit-brand').hide();

        });

        $(function() {
            var table= $('#brands-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'getBrandDBData',
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'manufacturer', name: 'manufacturer' },
                    { data: 'origin', name: 'origin' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
                ]
            });

            $(this).on('click', '.btn-brand-edit', function (e) {

                $('#name-for-edit').val($(this).data('name'));
                $('#manufacturer-for-edit').val($(this).data('manufacturer'));
                $('#origin-for-edit').val($(this).data('origin'));
                $('#id-for-update').val($(this).data('rowid'));

                $('#edit-brand').show();
                $('#brands-table').parents('div.dataTables_wrapper').first().hide();
                $('#top-head').hide();
            });


            $(this).on('click', '.btn-brand-delete', function (e) {

                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var url = $(this).data('remote');
                // confirm then
                $.ajax({
                    beforeSend: function (request) {
                        return confirm("Are you sure?");
                    },
                    url: url,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {method: '_DELETE', submit: true},

                    error: function (request, status, error) {
                        alert(request.responseText);
                    },

                }).always(function (data) {
                    $('#brands-table').DataTable().draw(true);
                })
            });


        });



        $(document).on('click', '.btn-brand-add', function (e) {
            $('#new-brand').show();
            $('#brands-table').parents('div.dataTables_wrapper').first().hide();
            $('#top-head').hide();
        });


        $(document).on('click', '.btn-back', function (e) {
            $('#edit-brand').hide();
            $('#new-brand').hide();
            $('#top-head').show();
            $('#brands-table').parents('div.dataTables_wrapper').first().show();
        });


        $(document).on('click', '.btn-brand-update', function (e) {
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = 'itemBrand/update/' + $('#id-for-update').val();

            // confirm then
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',

                data: {method: '_POST', submit: true, name:$('#name-for-edit').val(),
                    manufacturer:$('#manufacturer-for-edit').val(),
                    origin:$('#origin-for-edit').val(),
                },

                error: function (request, status, error) {
                    alert(request.responseText);
                },

                success: function (data) {
                    $('#edit-brand').hide();
                    $('#brands-table').DataTable().draw(false);
                    $('#top-head').show();
                    $('#brands-table').parents('div.dataTables_wrapper').first().show();

                }

            });
        });

    </script>
@endpush
