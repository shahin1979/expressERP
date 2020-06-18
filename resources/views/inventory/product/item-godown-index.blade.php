@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Store</li>
        </ol>
    </nav>

    <div class="container-fluid">

        <div class="row" id="top-head">

            <div class="col-md-4">
                <div class="pull-left">
                    <button type="button" class="btn btn-godown-add btn-primary"><i class="fa fa-plus"></i>New Store</button>
                </div>
                <div class="pull-right">
                    <button type="button" class="btn btn-print btn-info"><i class="fa fa-print"></i>Print</button>
                </div>
            </div>
        </div>


        <div class="row col-md-12 dataTables_wrapper" style="overflow-x:auto;">
            <table class="table table-bordered table-hover table-responsive" id="godowns-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>


        {!! Form::open(['url'=>'product/itemGodownIndex','method'=>'POST']) !!}

        <div id="new-godown" class="col-md-8">

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
                    <td><label for="name" class="control-label">godown Name</label></td>
                    <td><input id="name" type="text" class="form-control" name="name" value="" required autofocus></td>
                </tr>

                <tr>
                    <td><label for="address" class="control-label">Address</label></td>
                    <td>{!! Form::textarea('address',null,['id'=>'address','size' => '20x3','class'=>'field']) !!}</td>
                </tr>

                <tr>
                    <td><label for="description" class="control-label">Description</label></td>
                    <td>{!! Form::textarea('description',null,['id'=>'description','size' => '20x3','class'=>'field']) !!}</td>
                </tr>

                </tbody>

                <tfoot>
                <tr>
                    <td colspan="4"><button type="submit" id="btn-godown-save" class="btn btn-primary btn-godown-save">Submit</button></td>
                </tr>
                </tfoot>

            </table>
        </div>
        {!! Form::close() !!}

        {{--        <form action="#"  method="post" accept-charset="utf-8">--}}
        <div id="edit-godown" class="col-md-8">

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
                    <td><label for="address-for-edit" class="control-label">Address</label></td>
                    <td>{!! Form::textarea('address-for-edit',null,['id'=>'address-for-edit','size' => '20x3','class'=>'field']) !!}</td>
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
                    <td colspan="4"><button type="submit" id="btn-godown-update" class="btn btn-primary btn-godown-update">Submit</button></td>
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

            $('#new-godown').hide();
            $('#edit-godown').hide();

        });

        $(function() {
            var table= $('#godowns-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'getGodownDBData',
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'address', name: 'address' },
                    { data: 'description', name: 'description' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
                ]
            });

            $(this).on('click', '.btn-godown-edit', function (e) {

                $('#name-for-edit').val($(this).data('name'));
                $('#address-for-edit').val($(this).data('address'));
                $('#description-for-edit').val($(this).data('description'));
                $('#id-for-update').val($(this).data('rowid'));

                $('#edit-godown').show();
                $('#godowns-table').parents('div.dataTables_wrapper').first().hide();
                $('#top-head').hide();
            });


            $(this).on('click', '.btn-godown-delete', function (e) {

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
                    $('#godowns-table').DataTable().draw(true);
                })
            });


        });



        $(document).on('click', '.btn-godown-add', function (e) {
            $('#new-godown').show();
            $('#godowns-table').parents('div.dataTables_wrapper').first().hide();
            $('#top-head').hide();
        });


        $(document).on('click', '.btn-back', function (e) {
            $('#edit-godown').hide();
            $('#new-godown').hide();
            $('#top-head').show();
            $('#godowns-table').parents('div.dataTables_wrapper').first().show();
        });


        $(document).on('click', '.btn-godown-update', function (e) {
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = 'itemGodown/update/' + $('#id-for-update').val();

            // confirm then
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',

                data: {method: '_POST', submit: true, name:$('#name-for-edit').val(),
                    address:$('#address-for-edit').val(),
                    description:$('#description-for-edit').val(),
                },

                error: function (request, status, error) {
                    alert(request.responseText);
                },

                success: function (data) {
                    $('#edit-godown').hide();
                    $('#godowns-table').DataTable().draw(false);
                    $('#top-head').show();
                    $('#godowns-table').parents('div.dataTables_wrapper').first().show();

                }

            });
        });

    </script>
@endpush


