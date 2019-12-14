@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>
    <link href="{!! asset('assets/bootstrap4-toggle-3.6.1/css/bootstrap4-toggle.min.css') !!}" rel="stylesheet">
    <script src="{!! asset('assets/bootstrap4-toggle-3.6.1/js/bootstrap4-toggle.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Item Taxes</li>
        </ol>
    </nav>

    <div class="container-fluid">

        <div class="row" id="top-head">

            <div class="col-md-4">
                <div class="pull-left">
                    <button type="button" class="btn btn-tax-add btn-primary"><i class="fa fa-plus"></i>New Tax</button>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pull-right">
                    <button type="button" class="btn btn-print btn-primary"><i class="fa fa-print"></i>Print</button>
                </div>
            </div>
        </div>


        <div class="row col-md-12 dataTables_wrapper" style="overflow-x:auto;">
            <table class="table table-bordered table-hover table-responsive" id="taxes-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Name</th>
                    <th>Applicable On</th>
                    <th>Rate</th>
                    <th>Calculating Mode</th>
                    <th>Acc No</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>

        {!! Form::open(['url'=>'product/itemTaxIndex','method'=>'POST']) !!}
        <div id="new-tax" class="col-md-8">
            <table width="50%" class="table table-bordered table-striped table-hover">
                <tbody>
                <tr>
                    <td><label for="name" class="control-label">Tax Name</label></td>
                    <td><input id="name" type="text" class="form-control" name="name" value="" required autofocus></td>
                </tr>

                <tr>
                    <td><label for="applicable_on">Applicable On</label></td>
                    <td>{!! Form::select('applicable_on',['S'=>'Sales','P'=>'Purchase','B'=>'Both'],null,array('id'=>'applicable_on','class'=>'form-control')) !!}</td>
                </tr>

                <tr>
                    <td><label for="rate" class="control-label">Rate</label></td>
                    <td><input id="rate" type="text" class="form-control" name="rate" value="" required></td>
                </tr>

                <tr>
                    <td><label for="calculating_mode" class="control-label">Calculating Mode</label></td>
                    <td>{!! Form::select('calculating_mode',['F'=>'Fixed Amount','P'=>'Percentage'],null,array('id'=>'calculating_mode','class'=>'form-control')) !!}</td>
                </tr>

                <tr>
                    <td><label for="rate" class="control-label">Description</label></td>
                    <td><textarea class="form-control" name="description" cols="50" rows="2" id="description" placeholder="Description"></textarea></td>
                </tr>

                @if($comp_modules->contains('module_id',4))
                    <tr>
                        <td><label for="group_name">GL Account No</label></td>
                        <td><input id="acc_no" type="text" class="form-control" name="acc_no" value=""></td>
                    </tr>
                @endif

                </tbody>

                <tfoot>
                <tr>
                    <td colspan="4"><button type="submit" id="btn-tax-save" class="btn btn-primary btn-tax-save">Submit</button></td>
                </tr>
                </tfoot>

            </table>
        </div>
        {!! Form::close() !!}

        {{--        <form action="#"  method="post" accept-charset="utf-8">--}}
        <div id="edit-tax" class="col-md-8">
            <table width="50%" class="table table-bordered table-striped table-hover">
                <tbody>
                <tr>
                    <td><label for="name" class="control-label">Tax Name</label></td>
                    <td><input id="name-for-edit" type="text" class="form-control" name="name" value="" required autofocus></td>
                </tr>

                <tr>
                    <td><label for="applicable_on">Applicable On</label></td>
                    <td>{!! Form::select('applicable_on',['S'=>'Sales','P'=>'Purchase','B'=>'Both'],null,array('id'=>'applicable-on-edit','class'=>'form-control')) !!}</td>
                </tr>

                <tr>
                    <td><label for="rate" class="control-label">Rate</label></td>
                    <td><input id="edit-rate" type="text" class="form-control" name="rate" value="" required></td>
                </tr>

                <tr>
                    <td><label for="calculating_mode" class="control-label">Calculating Mode</label></td>
                    <td>{!! Form::select('calculating_mode',['F'=>'Fixed Amount','P'=>'Percentage'],null,array('id'=>'edit-calculating-mode','class'=>'form-control')) !!}</td>
                </tr>

                <tr>
                    <td><label for="rate" class="control-label">Description</label></td>
                    <td><textarea class="form-control" name="description" cols="50" rows="2" id="edit-description" placeholder="Description"></textarea></td>
                </tr>

                @if($comp_modules->contains('module_id',4))
                    <tr>
                        <td><label for="acc_no">GL Account No</label></td>
                        <td><input id="edit-acc-no" type="text" class="form-control" name="acc_no" value=""></td>
                    </tr>
                @endif

                <tr>
                    <td><label for="status">Status</label></td>
                    <td><input type="checkbox" id="status-for-edit" name="status" data-toggle="toggle" data-onstyle="primary" data-on="Active" data-off="Disable"></td>
                </tr>

                <input id="id-for-update" type="hidden" name="id-for-update"/>
                </tbody>

                <tfoot>
                <tr>
                    <td colspan="4"><button type="submit" id="btn-tax-update" class="btn btn-primary btn-tax-update">Submit</button></td>
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

            $('#new-tax').hide();
            $('#edit-tax').hide();

        });

        $(function() {
            var table= $('#taxes-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'getTaxDBData',
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'applicable_on', name: 'applicable_on' },
                    { data: 'rate', name: 'rate' },
                    { data: 'calculating_mode', name: 'calculating_mode' },
                    { data: 'acc_no', name: 'acc_no' },
                    { data: 'description', name: 'description' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
                ]
            });

            $(this).on('click', '.btn-tax-edit', function (e) {

                $('#name-for-edit').val($(this).data('name'));
                $('#applicable-on-edit').val($(this).data('applicable'));
                $('#edit-acc-no').val($(this).data('ledger'));
                $('#edit-rate').val($(this).data('rate'));
                $('#edit-calculating-mode').val($(this).data('calculating'));
                $('#edit-description').val($(this).data('description'));
                $('#id-for-update').val($(this).data('rowid'));
                $(this).data('status') == true ? $('#status-for-edit').bootstrapToggle('on') : '';
                $(this).data('status') == true ? $("#status-for-edit").prop("checked", true) : '';

                $('#edit-tax').show();
                $('#taxes-table').parents('div.dataTables_wrapper').first().hide();
                $('#top-head').hide();
            });


            $(this).on('click', '.btn-category-delete', function (e) {

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
                    $('#taxes-table').DataTable().draw(true);
                })
            });


        });



        $(document).on('click', '.btn-tax-add', function (e) {
            $('#new-tax').show();
            $('#taxes-table').parents('div.dataTables_wrapper').first().hide();
            $('#top-head').hide();
        });

        $(document).on('click', '.btn-tax-update', function (e) {
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = 'itemTax/update/' + $('#id-for-update').val();

            // confirm then
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',

                data: {method: '_POST', submit: true, acc_no:$('#edit-acc-no').val(),
                    name:$('#name-for-edit').val(), applicable_on:$('#applicable-on-edit').val(),
                    rate:$('#edit-rate').val(),description:$('#edit-description').val(),
                    calculating_mode:$('#edit-calculating-mode').val(),
                    status:$('#status-for-edit').is(":checked")
                },

                error: function (request, status, error) {
                    alert(request.responseText);
                },

                success: function (data) {
                    $('#edit-tax').hide();
                    $('#taxes-table').DataTable().draw(false);
                    $('#top-head').show();
                    $('#taxes-table').parents('div.dataTables_wrapper').first().show();

                }

            });
        });

    </script>

@endpush
