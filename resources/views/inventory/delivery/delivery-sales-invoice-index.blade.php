@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>
    <link href="{!! asset('assets/bootstrap4-toggle-3.6.1/css/bootstrap4-toggle.min.css') !!}" rel="stylesheet">
    <script src="{!! asset('assets/bootstrap4-toggle-3.6.1/js/bootstrap4-toggle.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Delivery Invoice</li>
        </ol>
    </nav>

    <div class="row col-md-12 dataTables_wrapper" style="overflow-x:auto;">
        <table class="table table-bordered table-hover table-responsive table-striped" id="items-table">
            <thead style="background-color: #b0b0b0">
            <tr class="table-success">
                <th>Invoice No</th>
                <th>Date</th>
                <th>Customer</th>
                <th>product</th>
                <th>Quantity</th>
                <th>Due Qty</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>

    <div class="row" id="top-head">
        <div class="col-md-4">
            <div class="pull-left">
                <button type="button" class="btn btn-back btn-primary"><i class="fa fa-backward"></i>Back</button>
            </div>
        </div>
    </div>


    <form id="ajax-items">
        <div class="row" id="view-section">
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Invoice Info</h5>
                        <table id="invoice-main" class="table table-striped invoice-main">

                        </table>
                    </div>
                </div>
            </div>

            <input name="invoice_no" type="hidden" id="invoice_no" value="">

            <div class="col-sm-9" >
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Product Info</h5>
                        <table id="invoice-items" class="table table-striped table-info table-bordered">
                            <thead>
                            <tr>
                                <th>Item</th>
                                <th style="text-align: right">Stock In<br/>Hand</th>
                                <th style="text-align: right">Sales<br/>Qty</th>
                                <th style="text-align: right">Delivery</th>
                            </tr>
                            </thead>

                            <tbody>

                            </tbody>

                            <tfoot>
                            <tr style="background-color: rgba(224,229,229,0.96)">
                                <td colspan="2">Full Delivery <input type="checkbox" checked  name="full_delivery" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="primary"></td>
                                <td colspan="2" style="text-align: right"><button type="submit" id="btn-delivery-post" class="btn btn-primary btn-delivery-post">Submit</button></td>
                            </tr>
                            </tfoot>

                        </table>

                    </div>
                </div>
            </div>

        </div>
    </form>
@endsection

@push('scripts')

    <script>

        $(document).ready(function(){

            $('#view-section').hide();
            $('#top-head').hide();

        });


        $(function() {
            var table= $('#items-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'getInvoiceItems',
                columns: [
                    { data: 'invoice_no', name: 'sales.invoice_no' },
                    { data: 'invoice_date', name: 'sales.invoice_date' },
                    { data:'customer', name: 'customer'},
                    { data: 'product', name: 'product' },
                    { data: 'quantity', className: 'dt-right', name: 'quantity' },
                    { data: 'due', className: 'dt-right', name: 'due' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
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


            $(this).on('click', '.btn-delivery-index', function (e) {
                e.preventDefault();

                $('#invoice_no').val($(this).data('invoice'));
                var url = $(this).data('remote');

                $(".invoice-info").remove();

                var reqHTML = '';

                reqHTML = '<tr class="invoice-info">' +
                    '<td align="left">Invoice No</td><td align="left">' + $(this).data('invoice') + '</td></tr>' +
                    '<tr class="invoice-info"><td align="left">Req Date</td><td align="left">' + $(this).data('date') + '</td>/tr>' +
                    '<tr class="invoice-info"><td align="left">Customer Name</td><td align="left">' + $(this).data('customer') + '</td></tr>' +
                    '<tr class="invoice-info"><td align="left">Create By</td><td align="left">' + $(this).data('user') + '</td></tr>';

                $('#invoice-main').append(reqHTML);


                //Ajax Load data from ajax
                $.ajax({
                    url : url,
                    type: "GET",
                    dataType: "JSON",

                    success: function(data)
                    {
                        $(".invoice-items").remove();
//
                        var trHTML = '';
                        $.each(data, function (i, item) {

                            trHTML += '<tr class="req_items">' +
                                '<td>' + item.item.name +'</td>' +
                                '<td align="right">' + item.item.on_hand +'</td>' +
                                '<td align="right">' + item.quantity +' ' + item.item.unit_name +'</td>' +
                                '<input name="item[' + i +'][id]" type="hidden" id="id" value="'+ item.id +'">' +
                                '<input name="item[' + i +'][product_id]" type="hidden" id="product_id" value="'+ item.product_id +'">' +
                                '<input name="item[' + i +'][unit_price]" type="hidden" id="unit_price" value="'+ item.unit_price +'">' +
                                '<input name="item[' + i +'][relationship_id]" type="hidden" id="relationship_id" value="'+ item.relationship_id +'">' +
                                '<td align="right"><input name="item[' + i + '][quantity]" class="form-control text-right" type="text" id="quantity" value="'+ item.quantity +'"></td>' +
                                '</tr>';
                        });
//
                        $('#invoice-items').append(trHTML);


                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error get data from ajax');
                    }
                });

                $('#view-section').show();
                $('#top-head').show();
                $('#items-table').parents('div.dataTables_wrapper').first().hide();

            });

            $(this).on('click', '.btn-back', function (e) {
                $('#view-section').hide();
                $('#top-head').hide();
                $('#items-table').parents('div.dataTables_wrapper').first().show();
            });




            $('#ajax-items').submit(function(e) {
                // Stop the browser from submitting the form.
                e.preventDefault();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var url = 'deliveryInvoiceItems/'+ $('#invoice_no').val();

                // confirm then
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: $('form').serialize(),

                    error: function (request, status, error) {
                        alert(request.responseText);
                    },

                    success: function (data) {

                        alert(data.success);
                        $('#view-section').hide();
                        $('#items-table').parents('div.dataTables_wrapper').first().show();
                        $('#items-table').DataTable().draw(true);
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
