@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Receive Items</li>
        </ol>
    </nav>

    <div class="row col-md-12 dataTables_wrapper" style="overflow-x:auto;">
        <table class="table table-bordered table-hover table-responsive" id="items-table">
            <thead style="background-color: #b0b0b0">
            <tr>
                <th>PO No</th>
                <th>Date</th>
                <th>Supplier</th>
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
                <button type="button" class="btn btn-back btn-primary"><i class="fa fa-backward"></i>Back</button>
            </div>
        </div>
    </div>


    <form id="ajax-items">
        <div class="row" id="edit-section">
            <div class="col-sm-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Purchase Info</h5>
                        <table id="invoice-main" class="table table-striped invoice-main">

                        </table>
                    </div>
                </div>
            </div>

            <input name="purchase_order" type="hidden" id="purchase_order" value="">
            <input name="is_return" type="hidden" id="is_return" value="">

            <div class="col-sm-7" >
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Product Info</h5>
                        <table id="invoice-items" class="table table-striped table-info table-bordered">
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th>Supplier</th>
                                <th style="text-align: right">Quantity</th>
                                <th style="text-align: right">Receive</th>
                                <th style="text-align: right">Return</th>
                            </tr>
                            </thead>

                            <tbody>

                            </tbody>

                            <tfoot>
                            <tr>
                                <td colspan="3"><button type="submit" id="btn-receive-items" class="btn btn-primary receive-items">Submit</button></td>
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

            $('#edit-section').hide();
            $('#top-head').hide();

        });


        $(function() {
            var table= $('#items-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'receiveItemsData',
                columns: [
                    { data: 'ref_no', name: 'purchases.ref_no' },
                    { data: 'po_date', name: 'purchases.po_date' },
                    { data: 'supplier', name: 'supplier' },
                    { data: 'product', name: 'product' },
                    { data: 'quantity', name: 'quantity' },
                    { data: 'unit_price', name: 'unit_price' },
                    { data: 'invoice_amt', name: 'purchases.invoice_amt' },
                    // { data: 'user.name', name: 'user.name' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
                ]
            });


            $(this).on('click', '.btn-receive', function (e) {
                e.preventDefault();
                var url = $(this).data('remote');

                $('#purchase_order').val($(this).data('order'));

                $(".invoice-info").remove();

                var reqHTML = '';

                reqHTML = '<tr class="invoice-info">' +
                    '<td align="left">Invoice No</td><td align="left">' + $(this).data('order') + '</td></tr>' +
                    '<tr class="invoice-info"><td align="left">Date</td><td align="left">' + $(this).data('date') + '</td>/tr>' +
                    '<tr class="invoice-info"><td align="left">Amount</td><td align="left">' + $(this).data('amount') + '</td>/tr>' ;

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
                        $.each(data, function (i, item) {

                            trHTML += '<tr class="invoice_items">' +
                                '<td align="right">' + item.item.name +'</td>' +
                                '<td align="right">' + item.supplier.name +'</td>' +
                                '<td align="right">' + item.quantity +'</td>' +
                                '<td align="right"><input name="item[' + i + '][receive]" class="form-control inp-receive text-right" type="text" id="receive" value="'+ item.quantity +'"></td>' +
                                '<td align="right"><input name="item[' + i + '][return]" class="form-control inp-return text-right" type="text" id="return" value="'+ 0 +'"></td>' +
                                '<td><input name="item[' + i +'][id]" type="hidden" id="id" value="'+ item.id +'"></td></tr>';
                        });
//
                        $('#invoice-items').append(trHTML);


                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error get data from ajax');
                    }
                });

                $('#edit-section').show();
                $('#top-head').show();
                $('#items-table').parents('div.dataTables_wrapper').first().hide();

            });

            $(this).on('click', '.btn-back', function (e) {
                $('#edit-section').hide();
                $(".invoice-info").remove();
                $('#top-head').hide();
                $('#items-table').parents('div.dataTables_wrapper').first().show();
            });




            $('#ajax-items').submit(function(e) {
                // Stop the browser from submitting the form.
                e.preventDefault();

                // Check Quantity > 0
                var inps = document.getElementsByClassName('inp-receive');
                var qty = 0;
                for (var i = 0; i <inps.length; i++) {
                    var inp=inps[i];
                    qty = qty + parseInt(inp.value)
                }

                if(qty <=0){
                    alert('Invalid Quantity : '+ qty);
                    return false;
                }

                var rtns = document.getElementsByClassName('inp-return');
                var r_qty = 0;

                for (var j = 0; j <rtns.length; j++) {
                    var rtn = rtns[j];
                    r_qty = r_qty + parseInt(rtn.value)
                }

                if(r_qty > 0){
                    $('#is_return').val(r_qty)
                }


                // End Check

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var url = 'receivePurchaseItems';

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
                        $('#edit-section').hide();
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
