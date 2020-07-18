@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Approve Receive</li>
        </ol>
    </nav>

    <div class="row col-md-12 dataTables_wrapper" style="overflow-x:auto;">
        <table class="table table-bordered table-hover table-responsive" id="items-table">
            <thead style="background-color: #b0b0b0">
            <tr>
                <th>Challan No</th>
                <th>Date</th>
                <th>Supplier</th>
                <th>product</th>
                <th>Quantity</th>
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

    <div id="view-section">
{{--        <form id="ajax-items">--}}
            <div class="row" id="edit-section">
                <div class="col-sm-5">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Receive Info</h5>
                            <table id="challan-main" class="table table-striped challan-main">

                            </table>
                        </div>
                    </div>
                </div>

                <input name="challan_no" type="hidden" id="challan_no" value="">
{{--                <input name="is_return" type="hidden" id="is_return" value="">--}}

                <div class="col-sm-7" >
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Received Product Info</h5>
                            <table id="receive-items" class="table table-striped table-info table-bordered">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Supplier</th>
                                    <th style="text-align: right">Receive Qty</th>
                                </tr>
                                </thead>

                                <tbody>

                                </tbody>

                                <tfoot>
                                <tr>
                                    <td colspan="2"><button type="submit" id="btn-approve-items" class="btn btn-primary btn-approve-items">Approve</button></td>
                                    <td><button type="submit" id="btn-reject-items" class="btn btn-danger btn-reject-items">Reject</button></td>
                                </tr>
                                </tfoot>

                            </table>

                        </div>
                    </div>
                </div>
            </div>
{{--        </form>--}}






        <div class="return-section" id="return-section">
            <div class="row" id="edit-section">
                <div class="col-sm-5">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Return Info</h5>
                            <table id="return-main" class="table table-striped return-main">

                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-sm-7" >
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Returned Product Info</h5>
                            <table id="return-items" class="table table-striped table-info table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Supplier</th>
                                        <th style="text-align: right">Returned Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


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
                ajax: 'receiveDBData',
                columns: [
                    { data: 'challan_no', name: 'receives.challan_no' },
                    { data: 'receive_date', name: 'receives.receive_date' },
                    { data: 'supplier', name: 'supplier' },
                    { data: 'product', name: 'product' },
                    { data: 'quantity', name: 'quantity' },
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


            $(this).on('click', '.btn-receive-details', function (e) {
                e.preventDefault();

                var url = $(this).data('remote');

                $('#challan_no').val($(this).data('challan'));

                $(".challan-info").remove();

                var reqHTML = '';

                reqHTML = '<tr class="challan-info">' +
                    '<td align="left">Challan No</td><td align="left">' + $(this).data('challan') + '</td></tr>' +
                    '<tr class="challan-info"><td align="left">Date</td><td align="left">' + $(this).data('date') + '</td>/tr>' +
                    '<tr class="challan-info"><td align="left">Type</td><td align="left">' + $(this).data('type') + '</td>/tr>' +
                    '<tr class="challan-info"><td align="left">Ref No</td><td align="left">' + $(this).data('ref_no') + '</td>/tr>' ;

                $('#challan-main').append(reqHTML);


                $(".return-info").remove();

                var retHTML = '';

                retHTML = '<tr class="return-info">' +
                    '<td align="left">Challan No</td><td align="left">' + $(this).data('rt_challan') + '</td></tr>' +
                    '<tr class="return-info"><td align="left">Date</td><td align="left">' + $(this).data('rt_date') + '</td>/tr>' +
                    '<tr class="return-info"><td align="left">Type</td><td align="left">' + $(this).data('rt_type') + '</td>/tr>' +
                    '<tr class="return-info"><td align="left">Ref No</td><td align="left">' + $(this).data('rt_ref') + '</td>/tr>' ;

                $('#return-main').append(retHTML);



                //Ajax Load data from ajax
                $.ajax({
                    url : url,
                    type: "GET",
                    dataType: "JSON",

                    success: function(data)
                    {


                        $(".receive-items").remove();
//
                        var trHTML = '';
                        $.each(data, function (i, item) {
                                $.each(item.items, function (i, tt) {
                                    trHTML += '<tr class="receive-items">' +
                                        '<td>' + tt.item.name +'</td>' +
                                        '<td>' + tt.supplier.name +'</td>' +
                                        '<td align="right">' + tt.quantity +'</td>' +
                                        '</tr>';
                                });


                        });

                        $('#receive-items').append(trHTML);


                        $(".return-items").remove();


//                         $(".receive-items").remove();
// //
//                         var trHTML = '';
//                         $.each(data, function (i, item) {
//
//
//                             trHTML += '<tr class="receive-items">' +
//                                 '<td>' + item.item.name +'</td>' +
//                                 '<td>' + item.supplier.name +'</td>' +
//                                 '<td align="right">' + item.quantity +'</td>' +
//                                 '</tr>';
//                         });
//
//                         $('#receive-items').append(trHTML);
//
//
                        $(".return-items").remove();

                        var returnHTML = '';
                        $.each(data, function (i, item) {

                            // alert(item.receive.returninfo.user.name);

                                $.each(item.returninfo.items, function (i, tItem) {

                                    // alert(tItem.item.name);

                                    returnHTML += '<tr class="return-items">' +
                                        '<td>' + tItem.item.name + '</td>' +
                                        '<td>' + tItem.supplier.name + '</td>' +
                                        '<td align="right">' + tItem.quantity + '</td>' +
                                        '</tr>';
                                });
                        });

                        $('#return-items').append(returnHTML);

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
                $(".invoice-info").remove();
                $('#top-head').hide();
                $('#items-table').parents('div.dataTables_wrapper').first().show();
            });

            $(this).on('click', '.btn-approve-items', function (e) {
                // Stop the browser from submitting the form.
                e.preventDefault();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var url = 'approveItems';

                // confirm then
                $.ajax({
                    beforeSend: function (request) {
                        return confirm("Are you sure?");
                    },
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: {method: '_POST', submit: true,challan:$('#challan_no').val()},
                    error: function (request, status, error) {
                        var myObj = JSON.parse(request.responseText);
                        alert(myObj.message + ' ' + myObj.error);
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
