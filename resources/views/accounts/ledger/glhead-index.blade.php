@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Head Ledger</li>
        </ol>
    </nav>

@include('accounts.ledger.modals.new-gl-head')

    <div class="row" id="top-head">
        <div class="col-md-6">
            <div class="pull-left">
                <button type="button" class="btn btn-department btn-success" data-toggle="modal" data-target="#modal-new-gh-head"><i class="fa fa-plus"></i>New Head</button>
            </div>
        </div>
        <div class="col-md-6">
            <div class="pull-right">
                <button type="button" class="btn btn-department btn-success" data-toggle="modal" data-target="#modal-new-department"><i class="fa fa-print"></i>Print</button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12" style="overflow-x:auto;">
            <table class="table table-bordered table-hover table-responsive" id="head-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Ledger Code</th>
                    <th>Ledger Group</th>
                    <th>Account No</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Type Details</th>
                    <th>Balance</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>


    <div id="edit-ledger" class="col-md-6">
        <table class="table table-bordered table-striped table-hover">
            <tbody>
            <tr>
                <td><label for="group_name">Ledger Name</label></td>
                <td><input type="text" name="acc_name_for_edit" id="acc_name_for_edit" class="form-control" autocomplete="off" required></td>
                <input id="id_for_update" type="hidden" name="id_for_update"/>
            </tr>
            </tbody>

            <tfoot>
            <tr>
                <td colspan="2"><button type="submit" id="btn-ledger-update" class="btn btn-primary btn-ledger-update">Submit</button></td>
            </tr>
            </tfoot>

        </table>
    </div>

@endsection

@push('scripts')

    <script>
        $(document).ready(function(){

            $('#edit-ledger').hide();

        });

        $(function() {
            var table= $('#head-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'GLAccountHeadData',
                columns: [
                    { data: 'ledger_code', name: 'ledger_code' },
                    { data: 'parent.acc_name', name: 'parent.acc_name', searchable: false },
                    { data: 'acc_no', name: 'acc_no' },
                    { data: 'acc_name', name: 'acc_name' },
                    { data: 'acc_type', name: 'acc_type' },
                    { data: 'details.description', name: 'details.description'},
                    { data: 'curr_bal', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2 ), name: 'curr_bal'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}

                ],
                order: [[ 2, "asc" ]]
            });



            $(this).on('click', '.btn-ledger-edit', function (e) {
                e.preventDefault();

                $('#acc_name_for_edit').val($(this).data('name'));
                $('#id_for_update').val($(this).data('rowid'));

                $('#edit-ledger').show();
                $('#head-table').parents('div.dataTables_wrapper').first().hide();
                $('#top-head').hide();

            });

            $(this).on('click', '.btn-delete', function (e) {
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
                    $('#head-table').DataTable().draw(true);
                })
            });

        });

        $(document).on('click', '.btn-new-head', function (e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = 'save';

            // confirm then
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',

                data: {method: '_POST', submit: true, ledger_code:$('#ledger_code').val(),
                    acc_name:$('#acc_name').val(),
                },

                error: function (request, status, error) {
                    alert(request.responseText);
                },

                success: function (data) {

                    alert(data.success + data.acc_name);
                    $('#modal-new-gh-head').modal('hide');
                    $('#head-table').DataTable().draw(false);

                }

            });
        });


        $(document).on('click', '.btn-ledger-update', function (e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = 'update/' + $('#id_for_update').val();

            // confirm then
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',

                data: {method: '_POST', submit: true, acc_name:$('#acc_name_for_edit').val(),
                },

                error: function (request, status, error) {
                    alert(request.responseText);
                },

                success: function (data) {

                    alert(data.success + data.acc_name);

                    $('#head-table').DataTable().draw(false);
                    $('#edit-ledger').hide();
                    $('#head-table').parents('div.dataTables_wrapper').first().show();
                    $('#top-head').show();

                }

            });
        });


    </script>

@endpush
