@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Depreciation Setup</li>
        </ol>
    </nav>

    <table>
        <tbody>
        <tr>
            <td width="30%"></td>
            <td width="40%" style="font-weight: bold; text-align: center; color:#721c24">Depreciation for the month : {!! $period->month_name !!} : {!! $period->year !!}</td>
            <td width="30%"></td>
        </tr>
        </tbody>
    </table>

    <div class="row" id="top-head">
        <div class="col-lg-10 margin-tb">
            <div class="pull-left">
                <button type="button" class="btn btn-success btn-add-account">Add New Account</button>
            </div>
        </div>
    </div>

{{--    <div class="justify-content-center">--}}
{{--        <img src="{!! asset('assets/images/page-under-construction.jpg') !!}" class="img-responsive">--}}
{{--    </div>--}}

    <div class="row dataTables_wrapper">
        <div style="overflow-x:auto;">
            <table class="table table-bordered table-hover" id="depreciation-table" style="font-size: 12px" >
                <thead>
                    <th>Acc No</th>
                    <th>Particulars</th>
                    <th>Contra</th>
                    <th>Opening</th>
                    <th>Additional</th>
                    <th>Total</th>
                    <th>Rate</th>
                    <th>Depr Amt</th>
                    <th>After Depr</th>
                    <th>Action</th>
                </thead>

                <input type="hidden" id="fp-no" name="fp-no" value="{!! $period->fp_no !!}">
                <input type="hidden" id="year" name="year" value="{!! $period->year !!}">

                <tfoot>
                <tr>
                    <th colspan="3" style="text-align:right">Total : </th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><button type="submit" id="btn-depreciation-post" class="btn btn-success btn-depreciation-post">POST</button></th>
                </tr>

                </tfoot>
            </table>
        </div>

    </div>


    <form class="form-horizontal" role="form" method="POST" id="add-dep-account">
{{--        {{ csrf_field() }}--}}

        <div class="card" id="depreciation-setup">
            <div class="card-header">
                Add Fixed Asset Account For Depreciation
            </div>
            <div class="card-body">
                <table class="table-bordered table table-hover">
                    <tbody>
                        <tr>
                            <td><label for="accNo" class="control-label">Account</label></td>
                            <td>{!! Form::select('acc_no',$ledgers, null , array('id' => 'acc_no', 'class' => 'form-control')) !!}</td>
                            <td><label for="rate" class="control-label">Rate</label></td>
                            <td>{!! Form::text('rate', null , array('id' => 'rate', 'class' => 'form-control', 'required')) !!}</td>
                            <td><label for="rate" class="control-label">Contra Account</label></td>
                            <td>{!! Form::select('contra_acc', $contra,null, array('id' => 'contra_acc', 'class' => 'form-control','required')) !!}</td>
                        </tr>
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="4"><button type="submit" id="btn-depreciation-save" class="btn btn-primary btn-depreciation-save">Submit</button></td>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
    </form>





@endsection

@push('scripts')
<script>
    $(document).ready(function(){

        $('#depreciation-setup').hide();
        // $('#edit-category').hide();

    });

    $(function() {
        var table= $('#depreciation-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            pageLength: 100,
            authwidth: false,
            ajax: 'getDepreciationData',
            columns: [
                { data: 'acc_no', name: 'acc_no' },
                { data: 'account.acc_name', name: 'account.acc_name' },
                { data: 'contra_acc', name: 'contra_acc' },
                { data: 'open_bal', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2 ), name: 'open_bal' },
                { data: 'additional_bal', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2 ), name: 'additional_bal' },
                { data: 'total_bal', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2 ), name: 'total_bal' },
                { data: 'dep_rate', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2 ), name: 'dep_rate' },
                { data: 'dep_amt', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2 ), name: 'dep_amt' },
                { data: 'closing_bal', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2 ), name: 'closing_bal' },
                { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
            ],


            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
                var numFormat = $.fn.dataTable.render.number( '\,', '.', 2 ).display;

                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                // Total over all pages
                opntotal = api.column( 3 ).data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                adtntotal = api.column( 4 ).data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                bfrtotal = api.column( 5 ).data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                dprntotal = api.column( 7 ).data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                aftrtotal = api.column( 8 ).data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );


                // Update footer
                $( api.column( 3 ).footer() ).html(numFormat(opntotal,2));
                $( api.column( 4 ).footer() ).html(numFormat(adtntotal,2));
                $( api.column( 5 ).footer() ).html(numFormat(bfrtotal,2));

                $( api.column( 7 ).footer() ).html(numFormat(dprntotal,2));
                $( api.column( 8 ).footer() ).html(numFormat(aftrtotal,2));

            }
        });


    });


    $(document).on('click', '.btn-add-account', function (e) {
        $('#depreciation-setup').show();
        $('#depreciation-table').parents('div.dataTables_wrapper').first().hide();
        $('#top-head').hide();
    });

    // ADD New Account for Depreciation
    $(document).on('click', '.btn-depreciation-save', function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var url = 'saveDepreciationAccount';

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',

            data: $('#add-dep-account').serialize(),

            error: function (request, status, error) {
                alert(request.responseText);
            },

            success: function (data) {

                // alert(data.success);
                $('#depreciation-setup').hide();
                $('#top-head').show();
                $('#depreciation-table').parents('div.dataTables_wrapper').first().show();
                $('#depreciation-table').DataTable().draw(false);
            }
        });
    });


    $(document).on('click', '.btn-depreciation-post', function (e) {

        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var url = 'depreciation/store';

        $.ajax({
            beforeSend: function (request) {
                return confirm("Are you sure?");
            },
            url: url,
            type: 'POST',
            dataType: 'json',

            data: {method: '_POST', submit: true, fp_no:$('#fp-no').val(),year:$('#year').val(),},

            error: function (request, status, error) {
                alert(request.responseText);
            },

            success: function (data) {

                alert(data.success);

                $('#depreciation-table').DataTable().draw(true);
            }
        });

        // if( !confirm('Are you sure that you want to post depreciation ?')){
        //     e.preventDefault();
        //     return false;
        // }
        // window.location.href = 'depreciation/store';


    });


</script>


@endpush
