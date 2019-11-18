@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Group Ledger</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-10 col-md-offset-1" style="overflow-x:auto;">
            <table class="table table-bordered table-hover table-responsive" id="group-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Ledger Code</th>
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
            var table= $('#group-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'GLGroupData',
                columns: [
                    { data: 'ledger_code', name: 'ledger_code' },
                    { data: 'acc_no', name: 'acc_no' },
                    { data: 'acc_name', name: 'acc_name' },
                    { data: 'acc_type', name: 'acc_type' },
                    { data: 'details.description', name: 'details.description'},
                    { data: 'curr_bal', name: 'curr_bal'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}

                ]
            });
        });

    </script>

@endpush
