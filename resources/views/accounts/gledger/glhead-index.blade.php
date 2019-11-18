@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Head Ledger</li>
        </ol>
    </nav>

@include('accounts.gledger.modals.new-gl-head')

    <div class="row">
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

@endsection

@push('scripts')

    <script>
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
                    { data: 'curr_bal', name: 'curr_bal'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}

                ],
                order: [[ 2, "asc" ]]
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

                data: {method: '_POST', submit: true, LEDGER_CODE:$('#ledger_code').val(),
                    ACC_NAME:$('#acc_name').val(),
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

    </script>

@endpush
