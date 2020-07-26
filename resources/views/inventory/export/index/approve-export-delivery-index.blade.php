@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Approve Export Delivery Products</li>
        </ol>
    </nav>

    @isset($deliveries)


            <div class="row col-md-12" style="overflow-x:auto;">
                <table class="table table-bordered table-hover table-responsive" id="delivery-table">
                    <thead style="background-color: #b0b0b0">
                    <tr>
                        <th>Contract No</th>
                        <th>Challan No</th>
                        <th>Customer</th>
                        <th>product</th>
                        <th>Quantity</th>
                        <th>Delivered By</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($deliveries as $row)
                        <tr>
                            <td>{!! $row->contract->export_contract_no !!}</td>
                            <td>{!! $row->challan_no !!}</td>
                            <td>{!! $row->customer->name !!}</td>
                            <td>
                            @foreach($items as $item)
                                @if($items->challan_no == $row->challan_no))
                                   {!! $item->product_id !!} <br/>

                                @endif

                            @endforeach
                            </td>
                            <td>{!! $row->challan_no !!}</td>
                            <td>{!! $row->challan_no !!}</td>
                            <td>{!! $row->challan_no !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
    @endisset
    <div class="row" id="top-head">
        <div class="col-md-4">
            <div class="pull-left">
                <button type="button" class="btn btn-back btn-primary"><i class="fa fa-backward"></i>Back</button>
            </div>
        </div>
    </div>


    {{--    <form id="ajax-items">--}}
    <div class="row" id="data-section">
        <div class="col-sm-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Delivery Challan Info</h5>
                    <table id="delivery-main" class="table table-striped delivery-main">

                    </table>
                </div>
            </div>
        </div>

        <input name="challan_no" type="hidden" id="challan_no" value="">

        <div class="col-sm-9" >
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Product Info</h5>
                    <table id="delivery-items" class="table table-striped table-info table-bordered delivery-items">
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th style="text-align: right">In<br/>Stock</th>
                            <th style="text-align: right">Delivery Qty</th>
                        </tr>
                        </thead>

                        <tbody>

                        </tbody>

                        <tfoot>
                        <tr style="background-color: rgba(224,229,229,0.96)">
                            <td colspan="2"><button type="submit" name="action" value="approve" id="action" class="btn btn-primary btn-approve">Approve</button></td>
                            <td colspan="2"><button type="submit" name="action" value="reject" id="action" class="btn btn-danger btn-approve pull-right">Reject</button></td>
                        </tr>
                        </tfoot>

                    </table>

                </div>
            </div>
        </div>


        <div class="col-sm-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Stock Inventory Voucher</h5>
                    <table id="trans-items" class="table table-striped table-info table-bordered">
                        <thead>
                        <tr>
                            <th>GL Head</th>
                            <th style="text-align: right">Debit Amount {!! $users_company->currency !!}</th>
                            <th style="text-align: right">Credit Amount {!! $users_company->currency !!}</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>


    {{--    </form>--}}





@endsection

@push('scripts')

    <script>

        $(document).ready(function(){

            $('#data-section').hide();
            $('#top-head').hide();

        });





            $(this).on('click', '.btn-delivery-details', function (e) {
                e.preventDefault();

                $('#challan_no').val($(this).data('challan'));
                var url = $(this).data('remote');

                $(".delivery-info").remove();
                $(".del_items").remove();

                var reqHTML = '';

                reqHTML = '<tr class="delivery-info">' +
                    '<tr class="delivery-info"><td align="left">Req/Invoice No</td><td align="left">' + $(this).data('requisition') + '</td></tr>' +
                    '<tr class="delivery-info"><td align="left">Against</td><td align="left">' + $(this).data('against') + '</td></tr>'+
                    '<tr class="delivery-info"><td align="left">Delivery Challan No</td><td align="left">' + $(this).data('challan') + '</td></tr>' +
                    '<tr class="delivery-info"><td align="left">Challan Date</td><td align="left">' + $(this).data('date') + '</td></tr>';

                $('#delivery-main').append(reqHTML);


                //Ajax Load data from ajax
                $.ajax({
                    url : url,
                    type: "GET",
                    dataType: "JSON",

                    success: function(data)
                    {
                        // Product Deliverables
                        $(".del_items").remove();
//
                        var trHTML = '';
                        $.each(data.products, function (i, item) {
                            trHTML += '<tr class="del_items">' +
                                '<td>' + item.item.name +'</td>' +
                                '<td align="right">' + item.item.on_hand +'</td>' +
                                '<td align="right">' + item.quantity +' ' + item.item.unit_name +'</td>' +
                                '</tr>';
                        });
//
                        $('#delivery-items').append(trHTML);

                        // GL Transactions


                        $(".tr_items").remove();
//
                        var trnHTML = '';
                        $.each(data.transactions, function (i, trItem) {
                            trnHTML += '<tr class="tr_items">' +
                                '<td>' + trItem.acc_name + '</td>' +
                                '<td align="right">' + trItem.debit_amt + '</td>' +
                                '<td align="right">' + trItem.credit_amt + '</td>' +
                                '</tr>';
                        });
//
                        $('#trans-items').append(trnHTML);


                    },
                    error: function (request, textStatus, errorThrown)
                    {
                        var myObj = JSON.parse(request.responseText);
                        $.alert({
                            title: 'Alert!',
                            content: myObj.message + ' ' + myObj.error,
                        });

                    }
                });

                $('#data-section').show();
                $('#top-head').show();
                $('#delivery-table').parents('div.dataTables_wrapper').first().hide();

            });

            $(this).on('click', '.btn-back', function (e) {
                $('#data-section').hide();
                $('#top-head').hide();
                $('#delivery-table').parents('div.dataTables_wrapper').first().show();
            });

            $(document).on('click', '.btn-approve', function (e) {
                // Stop the browser from submitting the form.
                e.preventDefault();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var url = 'ApproveDeliveryItems/' + $('#challan_no').val();

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
                        $('#data-section').hide();
                        $('#delivery-table').parents('div.dataTables_wrapper').first().show();
                        $('#delivery-table').DataTable().draw(true);
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
