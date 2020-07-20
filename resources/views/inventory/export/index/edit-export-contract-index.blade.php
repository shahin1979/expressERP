@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Edit Export Contracts</li>
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

    <div class="row" id="top-head">
        <div class="col-md-4">
            <div class="pull-left">
                <button type="button" class="btn btn-back btn-primary"><i class="fa fa-print"></i>Back</button>
            </div>
        </div>
    </div>

    <div class="alert alert-warning text-right" role="alert">
        <span class="blink_me">Your Activity is being recorded</span>
    </div>

    <form id="ajax-items">
        <div class="row" id="section-edit">
            <div class="col-sm-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Contract Info</h5>
                        <table id="contract-main" class="table table-striped contract-main">

                        </table>
                    </div>
                </div>
            </div>

            <input name="invoice_no" type="hidden" id="invoice_no" value="">

            <div class="col-sm-7" >
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Product Info</h5>
                        <table id="contract-items" class="table table-striped table-info table-bordered contract-items">
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th>Unit Price</th>
                                <th style="text-align: right">Quantity</th>
                            </tr>
                            </thead>

                            <tbody>

                            </tbody>

                            <tfoot>
                            <tr>
                                <td colspan="3"><button type="submit" id="btn-update-contract" class="btn btn-primary update-contract">Update</button></td>
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

            $('#section-edit').hide();
            $('#top-head').hide();

        });

        $(function() {
            var table = $('#contracts-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'getEditContractData',
                columns: [
                    {data: 'export_contract_no', name: 'export_contracts.export_contract_no'},
                    {data: 'contract_date', name: 'export_contracts.contract_date'},
                    {data: 'customer.name', name: 'customer.name'},
                    {data: 'product', name: 'product'},
                    {data: 'quantity', name: 'quantity'},
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


            $(this).on('click', '.btn-edit', function (e) {
                e.preventDefault();
                var url = $(this).data('remote');

                $('#invoice_no').val($(this).data('invoice'));

                $(".invoice-info").remove();

                var reqHTML = '';

                reqHTML = '<tr class="invoice-info">' +
                    '<td align="left">Contract No</td><td align="left">' + $(this).data('contract') + '</td></tr>' +
                    '<tr class="invoice-info"><td align="left">Date</td><td align="left">' + $(this).data('date') + '</td>/tr>' +
                    '<tr class="invoice-info"><td align="left">Customer</td><td align="left">' + $(this).data('customer') + '</td></tr>' +
                    '<tr class="invoice-info"><td align="left">Invoice Amount</td><td align="left">' + $(this).data('amount') + '</td>/tr>' ;

                $('#contract-main').append(reqHTML);


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
                                '<td align="right"><input name="item[' + i + '][price]" class="form-control text-right" type="text" id="price" value="'+ item.unit_price +'"></td>' +
                                '<td align="right"><input name="item[' + i + '][quantity]" class="form-control text-right" type="text" id="quantity" value="'+ item.quantity +'"></td>' +
                                '<td><input name="item[' + i +'][id]" type="hidden" id="id" value="'+ item.id +'"></td></tr>';
                        });
//
                        $('#contract-items').append(trHTML);


                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error get data from ajax');
                    }
                });

                $('#section-edit').show();
                $('#top-head').show();
                $('#contracts-table').parents('div.dataTables_wrapper').first().hide();

            });


            $('#ajax-items').submit(function(e) {
                // Stop the browser from submitting the form.
                e.preventDefault();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var url = 'updateExportContract';

                // confirm then
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: $('#ajax-items').serialize(),

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
                            content: 'Export Contract Updated',
                        });
                        $('#section-edit').hide();
                        $('#contracts-table').parents('div.dataTables_wrapper').first().show();
                        $('#contracts-table').DataTable().draw(true);
                    },
                });
            });


        });

        $(document).on('click', '.btn-back', function (e) {
            $('#section-edit').hide();
            $('#top-head').hide();
            $('#contracts-table').parents('div.dataTables_wrapper').first().show();
        });

        $(function (){
            $(document).on("focus", "input:text", function() {
                $(this).select();
            });
        });

        (function blink() {
            $('.blink_me').fadeOut(500).fadeIn(500, blink);
        })();


        // function blink() {
        //     $('.blink_me').fadeOut(500).fadeIn(500, blink);
        // }

    </script>

@endpush
