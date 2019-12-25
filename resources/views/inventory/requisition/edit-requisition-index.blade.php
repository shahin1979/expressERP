@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Update Requisition</li>
        </ol>
    </nav>

{{--    <div class="justify-content-center">--}}
{{--        <img src="{!! asset('assets/images/page-under-construction.jpg') !!}" class="img-responsive">--}}
{{--    </div>--}}


    {{--    <div class="row">--}}
    {{--        <div class="col-md-6">--}}
    {{--            <div class="pull-left">--}}
    {{--                <button type="button" class="btn btn-project btn-success" data-toggle="modal" data-target="#modal-new-project"><i class="fa fa-plus"></i>New Project</button>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div class="col-md-6">--}}
    {{--            <div class="pull-right">--}}
    {{--                <button type="button" class="btn btn-print-project btn-success"><i class="fa fa-print"></i>Print</button>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}

    <div class="row col-md-10 col-md-offset-1 dataTables_wrapper" style="overflow-x:auto;">
        <table class="table table-bordered table-hover table-responsive" id="requisition-table">
            <thead style="background-color: #b0b0b0">
            <tr>
                <th>Req No</th>
                <th>Req Date</th>
                <th>Req Type</th>
                <th>product</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Special title treatment</h5>
                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Special title treatment</h5>
                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
            </div>
        </div>
    </div>


    <div class="card" id="edit-requisition">
        <div class="card-header">
            Edit Requisition Items
        </div>
        <div class="card-body">
            <form class="form-horizontal" role="form" action="{{ url('requisition.edit.post') }}" method="POST" >
                {{ csrf_field() }}

                    <table id="edit-table" class="table table-striped edit-table">
                        <thead style="background-color: #8eb4cb">
                        <tr>
                            <th>Req No</th>
                            <th>product</th>
                            <th>Quantity</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>

                        <tfoot>
                        <tr>
                            <td colspan="3"><button type="submit" class="btn btn-primary btn-update pull-right">Submit</button></td>
                        </tr>
                        </tfoot>
                    </table>
{{--                <div class="modal-footer">--}}
{{--                    <div class="col-md-10 col-md-offset-1">--}}
{{--                        <button type="submit" class="btn btn-primary pull-right">Submit</button>--}}
{{--                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </form>


            <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
    </div>


    <div id="edit-requisition">
        <table class="table table-responsive table-striped table-hover">

        </table>
    </div>


@endsection

@push('scripts')

    <script>
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
                    { data: 'product', name: 'product' },
                    { data: 'quantity', name: 'quantity' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
                ]
            });


            $(this).on('click', '.btn-edit', function (e) {
                e.preventDefault();

                var url = $(this).data('remote');

                //Ajax Load data from ajax
                $.ajax({
                    url : url,
                    type: "GET",
                    dataType: "JSON",

                    success: function(data)
                    {
                        $(".tabonedata").remove();
//
                        var trHTML = '';
                        $.each(data, function (i, item) {

                            trHTML += '<tr class="tabonedata">' +
                                '<td align="left">' + item.refno + '</td><td>' +  item.item.name + '</td>' +
                                '<td align="right"><input name="quantity[]" class="form-control text-right" type="text" id="quantity" value="'+ item.quantity +'"></td>' +
                                '<td><input name="id[]" type="hidden" id="id" value="'+ item.id +'"></td></tr>';
                        });
//
                        $('#edittable').append(trHTML);

                        $('#edit-modal').modal('show'); // show bootstrap modal when complete loaded
                        $('.modal-title').text('Requisition Details'); // Set title to Bootstrap modal title

                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error get data from ajax');
                    }
                });

            });


        });



    </script>

@endpush
