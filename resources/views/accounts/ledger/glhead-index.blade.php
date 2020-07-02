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
                <button type="button" class="btn btn-gl btn-success" {!! $permission->add == false ? 'disabled' : null !!}  data-toggle="modal" data-target="#modal-new-gh-head"><i class="fa fa-plus"></i>New Head</button>
            </div>
        </div>
        <div class="col-md-6">
            <div class="pull-right">
                <button type="button" class="btn btn-print-gl btn-success" {!! $permission->print == false ? 'disabled' : null !!}><i class="fa fa-print"></i>Print</button>
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
                    <th>Opening</th>
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
                <td><label for="acc_name_for_edit">Ledger Name</label></td>
                <td><input type="text" name="acc_name_for_edit" id="acc_name_for_edit" class="form-control" autocomplete="off"></td>
                <input id="id_for_update" type="hidden" name="id_for_update"/>
            </tr>

            <tr id="open_debit">
                <td><label for="opn_dr_for_edit">Opening Debit</label></td>
                <td><input type="text" name="opn_dr_for_edit" id="opn_dr_for_edit" class="form-control" autocomplete="off" required></td>
            </tr>

            <tr id="open_credit">
                <td><label for="opn_cr_for_edit">Opening Credit</label></td>
                <td><input type="text" name="opn_cr_for_edit" id="opn_cr_for_edit" class="form-control" autocomplete="off" required></td>
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
                    { data: 'opening', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2 ), name: 'opening'},
                    { data: 'curr_bal', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2 ), name: 'curr_bal'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}

                ],
                order: [[ 2, "asc" ]],

                rowCallback: function( row, data, index ) {
                    if(index%2 == 0){
                        $(row).removeClass('myodd myeven');
                        $(row).addClass('myodd');
                    }else{
                        $(row).removeClass('myodd myeven');
                        $(row).addClass('myeven');
                    }
                }
            });



            $(this).on('click', '.btn-ledger-edit', function (e) {
                e.preventDefault();



                if($(this).data('permission') == false) { $.alert({
                    title: 'Alert!',
                    content: 'You do not have permission!',
                });  return false }

                $('#acc_name_for_edit').val($(this).data('name'));
                $('#opn_cr_for_edit').val($(this).data('opencr'));
                $('#opn_dr_for_edit').val($(this).data('opendr'));
                $('#id_for_update').val($(this).data('rowid'));

                $('#edit-ledger').show();
                $('#head-table').parents('div.dataTables_wrapper').first().hide();
                $('#top-head').hide();

            });

            $(this).on('click', '.btn-delete', function (e) {
                e.preventDefault();

                if($(this).data('permission') == false) { alert('You do not have permission'); return false}

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
                    var myObj = JSON.parse(request.responseText);

                    $.alert({
                        title: 'Alert!',
                        content: myObj.message + ' ' + myObj.error,
                    });
                },

                success: function (data) {

                    $.alert({
                        title: 'Alert!',
                        content: data.success + data.acc_name,
                    });

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
                    opn_dr:$('#opn_dr_for_edit').val(),opn_cr:$('#opn_cr_for_edit').val(),
                },

                error: function (request, status, error) {
                    var myObj = JSON.parse(request.responseText);
                    $.alert({
                        title: 'Alert!',
                        content: myObj.message + ' ' + myObj.error,
                    });
                },

                success: function (data) {
                    $.alert({
                        title: 'Alert!',
                        content: data.success + data.acc_name,
                    });

                    $('#head-table').DataTable().draw(false);
                    $('#edit-ledger').hide();
                    $('#head-table').parents('div.dataTables_wrapper').first().show();
                    $('#top-head').show();

                }

            });
        });

        //Print Chart of Account

        $(document).on('click', '.btn-print-gl', function (e) {
            window.location = 'account/print'
        });


        $(function (){
            $(document).on("focus", "input:text", function() {
                $(this).select();
            });
        });

    </script>

@endpush
