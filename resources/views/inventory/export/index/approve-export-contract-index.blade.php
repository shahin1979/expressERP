@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Approve Export Contracts</li>
        </ol>
    </nav>

    <div class="row col-md-12 dataTables_wrapper" style="overflow-x:auto;">
        <table class="table table-bordered table-hover table-responsive" id="contracts-table">
            <thead style="background-color: #b0b0b0">
            <tr>
                <th>Contract No</th>
                <th>Contract Date</th>
                <th>Importer</th>
                <th>product</th>
                <th>Quantity</th>
                <th>Amount</th>
                <th>Created By</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>




@endsection

@push('scripts')

    <script>

        $(function() {
            var table = $('#contracts-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'contractDataForApprove',
                columns: [
                    {data: 'ref_no', name: 'requisitions.ref_no'},
                    {data: 'req_date', name: 'requisitions.req_date'},
                    {data: 'req_type', name: 'requisitions.req_type', orderable: false, searchable: false},
                    {data: 'req_for', name: 'req_for'},
                    {data: 'product', name: 'product'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'user.name', name: 'user.name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
                ],
                order: [[ 1, "desc" ]],
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


            $(this).on('click', '.btn-approve', function (e) {
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
                    type: 'POST',
                    dataType: 'json',
                    data: {method: '_POST', submit: true},

                    error: function (request, status, error) {
                        alert(request.responseText);
                    },

                }).always(function (data) {
                    $('#requisition-table').DataTable().draw(true);
                })

            });

        });

    </script>

@endpush
