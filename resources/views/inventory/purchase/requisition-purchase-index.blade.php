@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Purchase Requisition</li>
        </ol>
    </nav>

    <div class="row col-md-12 dataTables_wrapper" style="overflow-x:auto;">
        <table class="table table-bordered table-striped table-hover table-responsive" id="requisition-table">
            <thead style="background-color: #b0b0b0">
            <tr class="table-success">
                <th>Req No</th>
                <th>Req Date</th>
                <th>Product</th>
                <th>Quantity</th>
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
            var table = $('#requisition-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'reqDataForPurchase',
                columns: [
                    {data: 'ref_no', name: 'requisitions.ref_no'},
                    {data: 'req_date', name: 'requisitions.req_date'},
                    {data: 'product', name: 'product'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'user.name', name: 'user.name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
                ]
            });


            $(this).on('click', '.btn-purchase', function (e) {

                window.location.href = $(this).data('remote');

            });

        });

    </script>

@endpush
