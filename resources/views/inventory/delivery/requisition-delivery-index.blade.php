@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>
    <link href="{!! asset('assets/bootstrap4-toggle-3.6.1/css/bootstrap4-toggle.min.css') !!}" rel="stylesheet">
    <script src="{!! asset('assets/bootstrap4-toggle-3.6.1/js/bootstrap4-toggle.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Update Requisition</li>
        </ol>
    </nav>

    <div class="row col-md-12 dataTables_wrapper" style="overflow-x:auto;">
        <table class="table table-bordered table-hover table-responsive" id="requisition-table">
            <thead style="background-color: #b0b0b0">
            <tr>
                <th>Req No</th>
                <th>Req Date</th>
                <th>Req For</th>
                <th>product</th>
                <th>Quantity</th>
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


    <form id="ajax-items">
        <div class="row" id="edit-section">
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Requisition Info</h5>
                        <table id="requisition-main" class="table table-striped requisition-main">

                        </table>
                    </div>
                </div>
            </div>

            <input name="req_no" type="hidden" id="req_no" value="">

            <div class="col-sm-9" >
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Product Info</h5>
                        <table id="requisition-items" class="table table-striped table-info table-bordered requisition-items">
                            <thead>
                            <tr>
                                <th>Requisition <br/> For</th>
                                <th>Item</th>
                                <th style="text-align: right">In<br/>Stock</th>
                                <th style="text-align: right">Requisition<br/>Qty</th>
                                <th style="text-align: right">Delivery</th>
                            </tr>
                            </thead>

                            <tbody>

                            </tbody>

                            <tfoot>
                                <tr style="background-color: rgba(224,229,229,0.96)">
                                    <td colspan="3">Full Delivery <input type="checkbox"  name="full_delivery" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="primary"></td>
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

            $('#edit-section').hide();
            $('#top-head').hide();

        });


        $(function() {
            var table= $('#requisition-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'getReqItems',
                columns: [
                    { data: 'ref_no', name: 'requisitions.ref_no' },
                    { data: 'req_date', name: 'requisitions.req_date' },
                    { data:'req_for', name: 'req_for'},
                    { data: 'product', name: 'product' },
                    { data: 'quantity', name: 'quantity' },
                    { data: 'user.name', name: 'user.name' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
                ],
                order: [[ 2, "desc" ]]
            });


            $(this).on('click', '.btn-delivery-index', function (e) {
                e.preventDefault();

                $('#req_no').val($(this).data('requisition'));
                var url = $(this).data('remote');

                $(".req-info").remove();

                var reqHTML = '';

                reqHTML = '<tr class="req-info">' +
                    '<td align="left">Req No</td><td align="left">' + $(this).data('requisition') + '</td></tr>' +
                    '<tr class="req-info"><td align="left">Req Date</td><td align="left">' + $(this).data('date') + '</td>/tr>' +
                    '<tr class="req-info"><td align="left">Req Type</td><td align="left">' + $(this).data('type') + '</td></tr>';

                $('#requisition-main').append(reqHTML);


                //Ajax Load data from ajax
                $.ajax({
                    url : url,
                    type: "GET",
                    dataType: "JSON",

                    success: function(data)
                    {
                        $(".req_items").remove();
//
                        var trHTML = '';
                        $.each(data, function (i, item) {

                            trHTML += '<tr class="req_items">' +
                                    '<td>' + item.location.name +'</td>' +
                                    '<td>' + item.item.name +'</td>' +
                                    '<td align="right">' + item.item.on_hand +'</td>' +
                                    '<td align="right">' + item.quantity +' ' + item.item.unit_name +'</td>' +
                                    '<input name="item[' + i +'][id]" type="hidden" id="id" value="'+ item.id +'">' +
                                    '<td align="right"><input name="item[' + i + '][quantity]" class="form-control text-right" type="text" id="quantity" value="'+ 0 +'"></td>' +
                                '</tr>';
                        });
//
                        $('#requisition-items').append(trHTML);


                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error get data from ajax');
                    }
                });

                $('#edit-section').show();
                $('#top-head').show();
                $('#requisition-table').parents('div.dataTables_wrapper').first().hide();

            });

            $(this).on('click', '.btn-back', function (e) {
                $('#edit-section').hide();
                $('#top-head').hide();
                $('#requisition-table').parents('div.dataTables_wrapper').first().show();
            });




            $('#ajax-items').submit(function(e) {
                // Stop the browser from submitting the form.
                e.preventDefault();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var url = 'deliveryRequisition';

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
                        $('#requisition-table').parents('div.dataTables_wrapper').first().show();
                        $('#requisition-table').DataTable().draw(true);
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
