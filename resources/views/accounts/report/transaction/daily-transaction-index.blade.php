@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Daily Transaction List</li>
        </ol>
    </nav>

    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-8 col-md-offset-1" >
                <br/>
                <div><h3>Daily Transactions Report</h3></div>
                <div style="background-color: #ff0000;height: 2px">&nbsp;</div>
                <br/>


                <div class="div">
                    <br/>
                    {!! Form::open(['url'=>'accounts/report/dailyTransactionIndex', 'method' => 'GET']) !!}

                    <table width="80%" class="table table-responsive table-hover" >

                        <tr>
                            <td width="5%"><label for="date_from" class="control-label" >Transaction Date</label></td>
                            <td width="10%">{!! Form::text('date_from', empty($dates) ? Carbon\Carbon::now()->format('d-m-Y') : $dates->min('trans_date') , array('id' => 'date_from', 'class' => 'form-control','required','readonly')) !!}</td>
                            <td width="5%"><label for="date_to" class="control-label" >To</label></td>
                            <td width="10%">{!! Form::text('date_to', empty($dates) ? Carbon\Carbon::now()->format('d-m-Y') : $dates->max('trans_date'), array('id' => 'date_to', 'class' => 'form-control','required','readonly')) !!}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><button name="action" type="submit" value="preview" class="btn btn-info btn-reject pull-left">Preview</button></td>
                            <td colspan="2"><button name="action" type="submit" value="print" class="btn btn-primary btn-approve pull-right">Print</button></td>
                        </tr>

                    </table>

                    {!! Form::close() !!}

                </div>
            </div>

            <div style="width: 5px"></div>

        </div>
    </div>
    @php($count = 0)

    @if(!empty($trans))

        <div class="row justify-content-center">

            <div class="form-group">
                <input type="text" name="search" id="search" class="form-control" placeholder="Search Any Data" />
            </div>

            @foreach($dates as $date)


            <table class="table table-bordered table-responsive table-hover records_table" id="records_table">
                <thead>
                    <tr class="new-row">
                        <th colspan="9">Transaction date : {!! $date->trans_date !!}</th>
                    </tr>
                    <tr class="table-primary">
                        <th>Date</th>
                        <th>Voucher No</th>
                        <th>Type</th>
                        <th>Acc No</th>
                        <th>Acc Name</th>
                        <th>Description</th>
                        <th style="text-align: right">Debit Amt</th>
                        <th style="text-align: right">Credit Amt</th>
                        <th>User</th>
                    </tr>

                </thead>
                <tbody id="tbl-body">
                    @foreach($trans as $row)
                        @if($date->trans_date == $row->trans_date)
                            <tr class="new-row {!! $count%2== 0 ? 'table-success' : 'table-light' !!}">
                                <td>{!! $row->trans_date !!}</td>
                                <td>{!! $row->voucher_no !!}</td>
                                <td>{!! $row->tr_code !!}</td>
                                <td>{!! $row->acc_no !!}</td>
                                <td>{!! isset($row->account->acc_name) ? $row->account->acc_name : null  !!}</td>

                                    @if($row->tr_code=== 'PR' and $row->cr_amt > 0)
                                        <td>
                                            @foreach($row->purchase->items as $desc)
                                                @if( (isset($desc->supplier->ledger_acc_no) ? $desc->supplier->ledger_acc_no : $users_company->default_cash)  === $row->acc_no)
                                                Purchase : {!! $desc->item->name !!} Qty: {!! $desc->quantity !!}@ {!! $desc->unit_price !!}<br/>
                                                @endif
                                            @endforeach
                                        </td>
                                    @else
                                        <td>{!! $row->trans_desc1 !!}</td>
                                    @endif
                                <td style="text-align: right">{!! number_format($row->dr_amt,2) !!}</td>
                                <td style="text-align: right">{!! number_format($row->cr_amt,2) !!}</td>
                                <td>{!! $row->user->name !!}</td>
                            </tr>
                            @php($count++)
                        @endif

                    @endforeach
                </tbody>
                <tfoot>
                <tr class="new-row">
                    <td colspan="6">Total</td>
                    <td style="text-align: right">{!!number_format($trans->where('trans_date',$date->trans_date)->sum('dr_amt'),2) !!}</td>
                    <td style="text-align: right">{!! number_format($trans->where('trans_date',$date->trans_date)->sum('cr_amt'),2) !!}</td>
                    <td></td>
                </tr>
                </tfoot>


            </table>

            @endforeach


        </div>

{{--        FOR PAGINATION--}}
{{--        {{ $trans->links() }}--}}


    @endif

@endsection

@push('scripts')

    <script>

        $(document).ready(function(){

            $( "#date_from" ).datetimepicker({
                format:'d-m-Y',
                timepicker: false,
                closeOnDateSelect: true,
                scrollInput : false,
                inline:false
            });

            $( "#date_to" ).datetimepicker({
                format:'d-m-Y',
                timepicker: false,
                closeOnDateSelect: true,
                scrollInput : false,
                inline:false
            });
        });




        function fetch_transaction_data(query = '')
        {


            var url = 'dailyTransactionIndex';

            $.ajax({
                url: url,
                method:'GET',
                data:{query:query, date_from:$('#date_from').val(), date_to:$('#date_to').val()},
                dataType:'json',
                success:function(response)
                {
                    $(".records_table tr:has(td)").remove();
                    $(".records_table tr:has(th)").remove();

                    var trHTML = '<tr class="table-primary">\n' +
                        '                        <th>Date</th>\n' +
                        '                        <th>Voucher No</th>\n' +
                        '                        <th>Type</th>\n' +
                        '                        <th>Acc No</th>\n' +
                        '                        <th>Acc Name</th>\n' +
                        '                        <th>Description</th>\n' +
                        '                        <th style="text-align: right">Debit Amt</th>\n' +
                        '                        <th style="text-align: right">Credit Amt</th>\n' +
                        '                        <th>User</th>\n' +
                        '                    </tr>';

                    $.each(response.trans, function (i, item) {

                        trHTML += '<tr><td>' + item.trans_date + '</td><td>' + item.voucher_no + '</td><td>' + item.tr_code + '</td>' +
                            '<td>' + item.acc_no + '</td><td>' + item.account.acc_name + '</td><td>' + item.trans_desc1 + '</td>' +
                            '<td style="text-align: right">' + item.dr_amt + '</td><td style="text-align: right">' + item.cr_amt + '</td><td>' + item.user.name + '</td></tr>';
                    });

                    $('#records_table').append(trHTML);



                    // $('tbody').html(data.table_data);
                    // $('#total_records').text(data.total_data);
                }
            })
        }

        $(document).on('keyup', '#search', function(){
            var query = $(this).val();
            fetch_transaction_data(query);
        });

    </script>

@endpush
