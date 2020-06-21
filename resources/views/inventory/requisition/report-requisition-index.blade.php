@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Print Requisition</li>
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

{{--    <div class="container spark-screen">--}}
{{--        <div class="row">--}}
{{--            <div class="col-md-10 col-md-offset-1" >--}}
{{--                <br/>--}}
{{--                <div><h3>General Ledger Report</h3></div>--}}
{{--                <div style="background-color: #ff0000;height: 2px">&nbsp;</div>--}}
{{--                <br/>--}}


{{--                <div class="div">--}}
{{--                    <br/>--}}
{{--                    {!! Form::open(['url'=>'ledger/rptGeneralLedgerIndex', 'method' => 'GET']) !!}--}}

{{--                    <table width="90%" class="table table-responsive table-hover" >--}}

{{--                        <tr>--}}
{{--                            <td width="5%"><label for="req_no" class="control-label" >Requisition No</label></td>--}}
{{--                            <td width="15%">{!! Form::text('req_no', null, array('id' => 'req_no', 'class' => 'form-control')) !!}</td>--}}
{{--                            <td width="5%"><label for="date_from" class="control-label" >From</label></td>--}}
{{--                            <td width="10%">{!! Form::text('date_from', Carbon\Carbon::now()->format('d-m-Y'), array('id' => 'date_from', 'class' => 'form-control','required','readonly')) !!}</td>--}}
{{--                            <td width="5%"><label for="date_to" class="control-label" >To</label></td>--}}
{{--                            <td width="10%">{!! Form::text('date_to', Carbon\Carbon::now()->format('d-m-Y'), array('id' => 'date_to', 'class' => 'form-control','required','readonly')) !!}</td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <td colspan="2"><button name="action" type="submit" value="preview" class="btn btn-info btn-reject pull-left">Preview</button></td>--}}
{{--                            <td colspan="4"><button name="action" type="submit" value="print" class="btn btn-primary btn-approve pull-right">Print</button></td>--}}
{{--                        </tr>--}}

{{--                    </table>--}}

{{--                    {!! Form::close() !!}--}}

{{--                </div>--}}
{{--            </div>--}}

{{--            <div style="width: 5px"></div>--}}

{{--        </div>--}}
{{--    </div>--}}

{{--    <div class="justify-content-center">--}}
{{--        <img src="{!! asset('assets/images/page-under-construction.jpg') !!}" class="img-responsive">--}}
{{--    </div>--}}


@endsection

@push('scripts')

    <script>

        $(function() {
            var table = $('#requisition-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'reqDataForPrint',
                columns: [
                    {data: 'ref_no', name: 'requisitions.ref_no'},
                    {data: 'req_date', name: 'requisitions.req_date'},
                    {data: 'req_type', name: 'requisitions.req_type', orderable: false, searchable: false},
                    {data: 'req_for', name: 'req_for'},
                    {data: 'product', name: 'product'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'user.name', name: 'user.name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
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

        });

    </script>

@endpush
