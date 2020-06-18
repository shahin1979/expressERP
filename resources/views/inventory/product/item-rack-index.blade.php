@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Store Rack</li>
        </ol>
    </nav>

    <div class="container-fluid">

        <div class="row" id="top-head">

            <div class="col-md-4">
                <div class="pull-left">
                    <button type="button" class="btn btn-rack-add btn-primary"><i class="fa fa-plus"></i>New Rack</button>
                </div>
                <div class="pull-right">
                    <button type="button" class="btn btn-print btn-info"><i class="fa fa-print"></i>Print</button>
                </div>
            </div>
        </div>


        <div class="row col-md-12 dataTables_wrapper" style="overflow-x:auto;">
            <table class="table table-bordered table-hover table-responsive" id="racks-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Name</th>
                    <th>Store</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>


        {!! Form::open(['url'=>'product/itemRackIndex','method'=>'POST']) !!}

        <div id="new-rack" class="col-md-8">

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
                    <td><label for="name" class="control-label">rack Name</label></td>
                    <td><input id="name" type="text" class="form-control" name="name" value="" required autofocus></td>
                </tr>

                <tr>
                    <td><label for="godown_id" class="control-label">Store</label></td>
                    <td>{!! Form::select('godown_id',$stores,null,array('id'=>'godown_id','class'=>'form-control')) !!}</td>
                </tr>

                <tr>
                    <td><label for="description" class="control-label">Description</label></td>
                    <td>{!! Form::textarea('description',null,['id'=>'description','size' => '20x3','class'=>'field']) !!}</td>
                </tr>

{{--                <tr>--}}
{{--                    <td><label for="description" class="control-label">Description</label></td>--}}
{{--                    <td><input id="description" type="text" class="form-control" name="description" value=""></td>--}}
{{--                </tr>--}}

                </tbody>

                <tfoot>
                <tr>
                    <td colspan="4"><button type="submit" id="btn-rack-save" class="btn btn-primary btn-rack-save">Submit</button></td>
                </tr>
                </tfoot>

            </table>
        </div>
        {!! Form::close() !!}

        {{--        <form action="#"  method="post" accept-charset="utf-8">--}}
        <div id="edit-rack" class="col-md-8">

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
                    <td><label for="name-for-edit" class="control-label">Name</label></td>
                    <td><input id="name-for-edit" type="text" class="form-control" name="name-for-edit" value="" required autofocus></td>
                </tr>

                <tr>
                    <td><label for="store-for-edit" class="control-label">Store</label></td>
                    <td>{!! Form::select('store-for-edit',$stores,null,array('id'=>'store-for-edit','class'=>'form-control')) !!}</td>
                </tr>

                <tr>
                    <td><label for="description-for-edit" class="control-label">Description</label></td>
                    <td>{!! Form::textarea('description-for-edit',null,['id'=>'description-for-edit','size' => '20x3','class'=>'field']) !!}</td>
                </tr>

{{--                <tr>--}}
{{--                    <td><label for="description-for-edit" class="control-label">Description</label></td>--}}
{{--                    <td><input id="description-for-edit" type="text" class="form-control" name="description" value=""></td>--}}
{{--                </tr>--}}


                </tbody>
                <input id="id-for-update" type="hidden" name="id-for-update"/>
                <tfoot>
                <tr>
                    <td colspan="4"><button type="submit" id="btn-rack-update" class="btn btn-primary btn-rack-update">Submit</button></td>
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

            $('#new-rack').hide();
            $('#edit-rack').hide();

        });

        $(function() {
            var table= $('#racks-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'getRackDBData',
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'godown.name', name: 'godown.name' },
                    { data: 'description', name: 'description' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
                ]
            });

            $(this).on('click', '.btn-rack-edit', function (e) {

                $('#name-for-edit').val($(this).data('name'));
                $('#store-for-edit').val($(this).data('store'));
                $('#description-for-edit').val($(this).data('description'));
                $('#id-for-update').val($(this).data('rowid'));

                $('#edit-rack').show();
                $('#racks-table').parents('div.dataTables_wrapper').first().hide();
                $('#top-head').hide();
            });


            $(this).on('click', '.btn-rack-delete', function (e) {

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
                    $('#racks-table').DataTable().draw(true);
                })
            });


        });



        $(document).on('click', '.btn-rack-add', function (e) {
            $('#new-rack').show();
            $('#racks-table').parents('div.dataTables_wrapper').first().hide();
            $('#top-head').hide();
        });


        $(document).on('click', '.btn-back', function (e) {
            $('#edit-rack').hide();
            $('#new-rack').hide();
            $('#top-head').show();
            $('#racks-table').parents('div.dataTables_wrapper').first().show();
        });


        $(document).on('click', '.btn-rack-update', function (e) {
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = 'itemRack/update/' + $('#id-for-update').val();

            // confirm then
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',

                data: {method: '_POST', submit: true, name:$('#name-for-edit').val(),
                    godown_id:$('#store-for-edit').val(),
                    description:$('#description-for-edit').val(),
                },

                error: function (request, status, error) {
                    alert(request.responseText);
                },

                success: function (data) {
                    $('#edit-rack').hide();
                    $('#racks-table').DataTable().draw(false);
                    $('#top-head').show();
                    $('#racks-table').parents('div.dataTables_wrapper').first().show();

                }

            });
        });

    </script>
@endpush


