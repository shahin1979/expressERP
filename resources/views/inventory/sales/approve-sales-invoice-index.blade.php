@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Approve Invoice</li>
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

    <div class="row" id="top-head">
        <div class="col-md-4">
            <div class="pull-left">
                <button type="button" class="btn btn-back btn-primary"><i class="fa fa-print"></i>Back</button>
            </div>
        </div>
    </div>


{{--    <form id="ajax-items">--}}
        <div class="row" id="edit-section">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Invoice Details</h5>
                        <table id="invoice-main" class="table table-striped invoice-main">

                        </table>
                    </div>
                </div>
            </div>

            <input name="invoice" type="hidden" id="invoice" value="">

            <div class="col-sm-8" >
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Product Info</h5>
                        <table id="invoice-items" class="table table-striped table-info table-bordered invoice-items">
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th>Unit Price</th>
                                <th style="text-align: right">Quantity</th>
                                <th style="text-align: right">Tax</th>
                                <th style="text-align: right">Sub Total</th>
                            </tr>
                            </thead>

                            <tbody>

                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="5" align="right" id="grand-total">0</td>
                                </tr>
                            <tr>
                                <td colspan="3"><button type="submit" name="action" value="approve" id="action" class="btn btn-primary btn-approve">Approve</button></td>
                                <td colspan="2"><button type="submit" name="action" value="reject" id="action" class="btn btn-danger btn-approve pull-right">Reject</button></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>


            <div class="col-sm-8" >
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title" style="background-color: #8ac1ef">GL Transaction</h5>
                    <table id="invoice-ledger" class="table table-striped table-bordered">
                        <thead>
                        <tr style="background-color: rgba(58,135,173,0.8)">
                            <th>Gl Head</th>
                            <th style="text-align: right">Debit</th>
                            <th style="text-align: right">Credit</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>

                    </table>
                </div>
            </div>
            </div>


            @if($users_company->auto_delivery === true)
            <div class="col-sm-8" >
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title" style="background-color: #8ac1ef">Inventory Out Transaction</h5>
                        <table id="delivery-ledger" class="table table-striped table-bordered">
                            <thead>
                            <tr style="background-color: rgba(58,135,173,0.8)">
                                <th>Gl Head</th>
                                <th style="text-align: right">Debit</th>
                                <th style="text-align: right">Credit</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
            @endif

        </div>
{{--    </form>--}}
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
                ajax: 'getApproveInvoice',
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


            $(this).on('click', '.btn-view', function (e) {
                e.preventDefault();
                var url = $(this).data('remote');

                $('#invoice').val($(this).data('invoice'));
                // $('#grand-total').html($(this).data('amount'));
                // $('#customer_id').html($(this).data('customer'));
                // $('#dr_amt').html($(this).data('amount'));
                // $('#cr_amt').html($(this).data('amount'));

                $(".invoice-info").remove();

                var reqHTML = '';

                reqHTML = '<tr class="invoice-info">' +
                    '<td align="left">Invoice No</td><td align="left">' + $(this).data('invoice') + '</td></tr>' +
                    '<tr class="invoice-info"><td align="left">Date</td><td align="left">' + $(this).data('date') + '</td>/tr>' +
                    '<tr class="invoice-info"><td align="left">Customer</td><td align="left">' + $(this).data('customer') + '</td></tr>' +
                    '<tr class="invoice-info"><td align="left">Invoice Amount</td><td align="left">' + $(this).data('amount') + '</td>' +
                    '<tr class="invoice-info"><td align="left">Discount Amount</td><td align="left">' + $(this).data('discount') + '</td>' +
                    '<tr class="invoice-info"><td align="left">Paid Amount</td><td align="left">' + $(this).data('paid') + '</td>' +
                    '<tr class="invoice-info"><td align="left" style="font-weight: bold; color:darkred ">Due Amount</td><td align="left" style="font-weight: bold">' + $(this).data('due') + '</td>' +
                    '</tr>' ;

                $('#invoice-main').append(reqHTML);


                //Ajax Load data from ajax
                $.ajax({
                    url : url,
                    type: "GET",
                    dataType: "JSON",

                    success: function(data)
                    {
                        $(".invoice_items").remove();
//
                        var trHTML = '';
                        $.each(data.sales, function (i, item) {

                            trHTML += '<tr class="invoice_items">' +
                                '<td align="right">' + item.item.name +'</td>' +
                                '<td align="right">' + item.unit_price +'</td>' +
                                '<td align="right">' + item.quantity +'</td>' +
                                '<td align="right">' + item.tax_total +'</td>' +
                                '<td align="right">' + item.total_price +'</td></tr>';
                        });
//
                        $('#invoice-items').append(trHTML);

                        // Show Sales Voucher
                        $(".invoice_trans").remove();
//
                        var trnHTML = '';
                        $.each(data.transactions, function (i, item) {

                            trnHTML += '<tr class="invoice_trans">' +
                                '<td>' + item.acc_name +'</td>' +
                                '<td align="right">' + item.debit_amt +'</td>' +
                                '<td align="right">' + item.credit_amt +'</td></tr>';
                        });
                        $('#invoice-ledger').append(trnHTML);

                        //Show Stock Out Voucher
                        $(".delivery_trans").remove();
                        var trdHTML = '';
                        $.each(data.deliveries, function (i, item) {

                            trdHTML += '<tr class="delivery_trans">' +
                                '<td>' + item.acc_name +'</td>' +
                                '<td align="right">' + item.debit_amt +'</td>' +
                                '<td align="right">' + item.credit_amt +'</td></tr>';
                        });
                        $('#delivery-ledger').append(trdHTML);


                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error get data from ajax');
                    }
                });

                $('#edit-section').show();
                $('#top-head').show();
                $('#invoice-table').parents('div.dataTables_wrapper').first().hide();

            });

            $(this).on('click', '.btn-back', function (e) {
                $('#edit-section').hide();
                $(".invoice-info").remove();
                $('#top-head').hide();
                $('#invoice-table').parents('div.dataTables_wrapper').first().show();
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
                        var myObj = JSON.parse(request.responseText);
                        alert(myObj.message + ' ' + myObj.error);
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
