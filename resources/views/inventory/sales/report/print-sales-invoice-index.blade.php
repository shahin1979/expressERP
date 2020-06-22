@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Print Invoice</li>
        </ol>
    </nav>

    <div class="row col-md-12 dataTables_wrapper" style="overflow-x:auto;">
        <table class="table table-bordered table-hover table-responsive" id="invoice-table">
            <thead style="background-color: #b0b0b0">
            <tr>
                <th>Invoice No</th>
                <th>Date</th>
                <th>Customer</th>
                <th>product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Invoice Amt</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>





@endsection

@push('scripts')

    <script>

        $(document).ready(function(){

            $('#edit-section').hide();
            $('#top-head').hide();

        });


        $(function() {
            var table= $('#invoice-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'getPrintInvoice',
                columns: [
                    { data: 'invoice_no', name: 'sales.invoice_no' },
                    { data: 'invoice_date', name: 'sales.invoice_date' },
                    { data: 'customer.name', name: 'customer.name' },
                    { data: 'product', name: 'product' },
                    { data: 'quantity', name: 'quantity' },
                    { data: 'unit_price', name: 'unit_price' },
                    { data: 'invoice_amt', name: 'sales.invoice_amt' },
                    // { data: 'user.name', name: 'user.name' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
                ],
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


            $(this).on('click', '.btn-print', function (e) {
                e.preventDefault();
                var url = $(this).data('remote');
                window.location.href = $(this).data('remote');
            });






            $(this).on('click', '.btn-approve', function (e) {
                // Stop the browser from submitting the form.
                e.preventDefault();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var url = 'approveSalesInvoice/' + $('#invoice').val();

                // confirm then
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        method: '_POST', submit: true, action:$(this).val(),
                    },

                    error: function (request, status, error) {
                        alert(request.responseText);
                    },

                    success: function (data) {

                        alert(data.success);
                        $('#edit-section').hide();
                        $('#invoice-table').parents('div.dataTables_wrapper').first().show();
                        $('#invoice-table').DataTable().draw(true);
                    },
                });
            });
        });

        $(function (){
            $(document).on("focus", "input:text", function() {
                $(this).select();
            });
        });


    </script>

@endpush
