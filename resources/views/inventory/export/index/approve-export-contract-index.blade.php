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
                <th>Unit Price</th>
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
                ajax: 'getExportContractData',
                columns: [
                    {data: 'export_contract_no', name: 'export_contracts.export_contract_no'},
                    {data: 'contract_date', name: 'export_contracts.contract_date'},
                    {data: 'customer.name', name: 'customer.name'},
                    {data: 'product', name: 'product'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'price', name: 'price'},
                    {data: 'contract_amt',name: 'contract_amt'},
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
                        $.alert({
                            title: 'Alert!',
                            content: myObj.message + ' ' + myObj.error,
                        });
                    },

                }).always(function (data) {
                    $('#contracts-table').DataTable().draw(true);
                })

            });



            $(this).on('click', '.btn-reject', function (e) {
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
                        $.alert({
                            title: 'Alert!',
                            content: myObj.message + ' ' + myObj.error,
                        });
                    },

                }).always(function (data) {
                    $('#contracts-table').DataTable().draw(true);
                })

            });

        });

    </script>

@endpush
