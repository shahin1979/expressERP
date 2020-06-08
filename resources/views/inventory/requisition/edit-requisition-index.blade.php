@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

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
                <th>Req Type</th>
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
        <div class="col-sm-5">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Requisition Info</h5>
                    <table id="requisition-main" class="table table-striped requisition-main">

                    </table>
                </div>
            </div>
        </div>



        <div class="col-sm-7" >
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Product Info</h5>
                    <table id="requisition-items" class="table table-striped table-info table-bordered requisition-items">
                        <thead>
                        <tr>
                            <th>Requisition For</th>
                            <th>Item</th>
                            <th>Quantity</th>
                        </tr>
                        </thead>

                        <tbody>

                        </tbody>

                        <tfoot>
                        <tr>
                            <td colspan="3"><button type="submit" id="btn-update-requisition" class="btn btn-primary update-requisition">Update</button></td>
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
                ajax: 'requisitionData',
                columns: [
                    { data: 'ref_no', name: 'requisitions.ref_no' },
                    { data: 'req_date', name: 'requisitions.req_date' },
                    { data: 'req_type', name: 'requisitions.req_type', orderable: false, searchable: false },
                    { data:'req_for', name: 'req_for'},
                    { data: 'product', name: 'product' },
                    { data: 'quantity', name: 'quantity' },
                    { data: 'user.name', name: 'user.name' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
                ],
                order: [[ 1, "desc" ]]
            });


            $(this).on('click', '.btn-edit', function (e) {
                e.preventDefault();
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
                                '<td align="right">' + item.location.name +'</td>' +
                                '<td align="right">' + item.item.name +'</td>' +
                                '<td align="right"><input name="item[' + i + '][quantity]" class="form-control text-right" type="text" id="quantity" value="'+ item.quantity +'"></td>' +
                                '<td><input name="item[' + i +'][id]" type="hidden" id="id" value="'+ item.id +'"></td></tr>';
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
                var url = 'updateRequisition';

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



    </script>

@endpush
