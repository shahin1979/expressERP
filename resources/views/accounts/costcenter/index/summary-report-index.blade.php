@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Cost Summary</li>
        </ol>
    </nav>

    <div class="container spark-screen">
        <div class="row" id="current">
            <div class="col-md-8 col-md-offset-1" >
                <br/>
                <div><h3>Cost Center Summary</h3></div>
                <div style="background-color: #ff0000;height: 2px">&nbsp;</div>
                <br/>


                <div class="div">
                    <br/>
                    {!! Form::open(['url'=>'costcenter/rptCostCenterSummary', 'method' => 'GET']) !!}

                    <table width="80%" class="table table-responsive table-hover" >

{{--                        <tr>--}}
{{--                            <td width="5%"><label for="date_from" class="control-label" >Trial Balance For</label></td>--}}
{{--                            <td width="10%">{!! Form::select('report_type',['A'=>'Account Wise', 'G'=>'Group Wise'], 'A'  , array('id' => 'report_type', 'class' => 'form-control','required')) !!}</td>--}}
{{--                            <td width="5%"><label for="date_to" class="control-label" >As On</label></td>--}}
{{--                            <td width="10%">{!! Form::text('date_to', Carbon\Carbon::now()->format('d-m-Y'), array('id' => 'date_to', 'class' => 'form-control','required','readonly')) !!}</td>--}}
{{--                        </tr>--}}
                        <tr>
                            <td width="70%"><button name="action" type="submit" value="preview" class="btn btn-info btn-reject pull-left">Preview</button></td>
                            <td width=30%"><button name="action" type="submit" value="print" class="btn btn-primary btn-approve pull-right">Print</button></td>
                        </tr>

                    </table>

                    {!! Form::close() !!}

                </div>
            </div>

            <div style="width: 5px"></div>

            <div class="col-md-3" >

                <div class="div">
                    <br/>
                    {!! Form::open(['url'=>'ledger/previousTrialBalanceIndex', 'method' => 'GET']) !!}

                    <table width="80%" class="table table-responsive table-hover" >


                        <tr>
                            <td colspan="2"><button name="action" type="submit" class="btn btn-warning btn-reject pull-left disabled">Previous Year</button></td>
                        </tr>

                    </table>

                    {!! Form::close() !!}

                </div>
            </div>

        </div>


    </div>

    @if(!empty($report))

        <div class="card" id="summary">
            <div class="card-header">
                Cost Center Summary Report
            </div>
            <div class="card-body">
{{--                <div class="table-responsive">--}}

                    <table class="table table-striped table-bordered table-hover table-responsive" id="summary-table">
                        <thead>
                        <tr class="table-primary">
                            <th rowspan="2" style="text-align: center">SL</th>
                            <th rowspan="2" width="25%">Action</th>
                            <th rowspan="2" width="25%">Name</th>
                            <th colspan="2" style="text-align: center">{!! $fiscal->where('fp_no',1)->first()->month_name !!}</th>
                            <th colspan="2" style="text-align: center">{!! $fiscal->where('fp_no',2)->first()->month_name !!}</th>
                            <th colspan="2" style="text-align: center">{!! $fiscal->where('fp_no',3)->first()->month_name !!}</th>
                            <th colspan="2" style="text-align: center">{!! $fiscal->where('fp_no',4)->first()->month_name !!}</th>
                            <th colspan="2" style="text-align: center">{!! $fiscal->where('fp_no',5)->first()->month_name !!}</th>
                            <th colspan="2" style="text-align: center">{!! $fiscal->where('fp_no',6)->first()->month_name !!}</th>
                            <th colspan="2" style="text-align: center">{!! $fiscal->where('fp_no',7)->first()->month_name !!}</th>
                            <th colspan="2" style="text-align: center">{!! $fiscal->where('fp_no',8)->first()->month_name !!}</th>
                            <th colspan="2" style="text-align: center">{!! $fiscal->where('fp_no',9)->first()->month_name !!}</th>
                            <th colspan="2" style="text-align: center">{!! $fiscal->where('fp_no',10)->first()->month_name !!}</th>
                            <th colspan="2" style="text-align: center">{!! $fiscal->where('fp_no',11)->first()->month_name !!}</th>
                            <th colspan="2" style="text-align: center">{!! $fiscal->where('fp_no',12)->first()->month_name !!}</th>


                        </tr>
                        <tr>
                            <td>Budget</td>
                            <td>Expense</td>

                            <td>Budget</td>
                            <td>Expense</td>
                            <td>Budget</td>
                            <td>Expense</td>
                            <td>Budget</td>
                            <td>Expense</td>
                            <td>Budget</td>
                            <td>Expense</td>
                            <td>Budget</td>
                            <td>Expense</td>
                            <td>Budget</td>
                            <td>Expense</td>
                            <td>Budget</td>
                            <td>Expense</td>
                            <td>Budget</td>
                            <td>Expense</td>
                            <td>Budget</td>
                            <td>Expense</td>
                            <td>Budget</td>
                            <td>Expense</td>
                            <td>Budget</td>
                            <td>Expense</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($report as $i=>$row)
                                <tr style="background-color: {!! $i % 2 == 0 ? '#ffffff': '#afffff' !!}">
                                    <td style="width: 20%;">{!! $i+1 !!}</td>
                                    <td style="text-align: left"><a href="#" class="btn btn-success btn-details"><i class="fa fa-eye"></i></a></td>
{{--                                    <td><i class="fa fa-eye"></i></td>--}}
                                    <td style="width: 20%;">{!! $row->name !!}</td>
                                    <td style="text-align: right">{!! number_format($row->budget_01,2) !!}</td>
                                    <td style="text-align: right">{!! number_format($amount->where('fp_no',1)->where('cost_center_id',$row->id)->sum('dr_amt'),2) !!}</td>
                                    <td style="text-align: right">{!! number_format($row->budget_02,2) !!}</td>
                                    <td style="text-align: right">{!! number_format($amount->where('fp_no',2)->where('cost_center_id',$row->id)->sum('dr_amt'),2) !!}</td>
                                    <td style="text-align: right">{!! number_format($row->budget_03,2) !!}</td>
                                    <td style="text-align: right">{!! number_format($amount->where('fp_no',3)->where('cost_center_id',$row->id)->sum('dr_amt'),2) !!}</td>
                                    <td style="text-align: right">{!! number_format($row->budget_04,2) !!}</td>
                                    <td style="text-align: right">{!! number_format($amount->where('fp_no',4)->where('cost_center_id',$row->id)->sum('dr_amt'),2) !!}</td>
                                    <td style="text-align: right">{!! number_format($row->budget_05,2) !!}</td>
                                    <td style="text-align: right">{!! number_format($amount->where('fp_no',5)->where('cost_center_id',$row->id)->sum('dr_amt'),2) !!}</td>
                                    <td style="text-align: right">{!! number_format($row->budget_06,2) !!}</td>
                                    <td style="text-align: right">{!! number_format($amount->where('fp_no',6)->where('cost_center_id',$row->id)->sum('dr_amt'),2) !!}</td>
                                    <td style="text-align: right">{!! number_format($row->budget_07,2) !!}</td>
                                    <td style="text-align: right">{!! number_format($amount->where('fp_no',7)->where('cost_center_id',$row->id)->sum('dr_amt'),2) !!}</td>
                                    <td style="text-align: right">{!! number_format($row->budget_08,2) !!}</td>
                                    <td style="text-align: right">{!! number_format($amount->where('fp_no',8)->where('cost_center_id',$row->id)->sum('dr_amt'),2) !!}</td>
                                    <td style="text-align: right">{!! number_format($row->budget_09,2) !!}</td>
                                    <td style="text-align: right">{!! number_format($amount->where('fp_no',9)->where('cost_center_id',$row->id)->sum('dr_amt'),2) !!}</td>
                                    <td style="text-align: right">{!! number_format($row->budget_10,2) !!}</td>
                                    <td style="text-align: right">{!! number_format($amount->where('fp_no',10)->where('cost_center_id',$row->id)->sum('dr_amt'),2) !!}</td>
                                    <td style="text-align: right">{!! number_format($row->budget_11,2) !!}</td>
                                    <td style="text-align: right">{!! number_format($amount->where('fp_no',11)->where('cost_center_id',$row->id)->sum('dr_amt'),2) !!}</td>
                                    <td style="text-align: right">{!! number_format($row->budget_12,2) !!}</td>
                                    <td style="text-align: right">{!! number_format($amount->where('fp_no',12)->where('cost_center_id',$row->id)->sum('dr_amt'),2) !!}</td>
                                </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
{{--                        <tr style="background-color: #3A92AF">--}}
{{--                            <td colspan="3">Grand Total</td>--}}
{{--                            <td style="text-align: right">{!! number_format($report->where('is_group',false)->sum('opening_dr'),2)  !!}</td>--}}
{{--                            <td style="text-align: right">{!! number_format($report->where('is_group',false)->sum('opening_cr'),2)  !!}</td>--}}
{{--                            <td style="text-align: right">{!! number_format($report->where('is_group',false)->sum('dr_tr'),2)  !!}</td>--}}
{{--                            <td style="text-align: right">{!! number_format($report->where('is_group',false)->sum('cr_tr'),2)  !!}</td>--}}
{{--                            <td style="text-align: right">{!! number_format($report->where('is_group',false)->sum('balance'),2)  !!}</td>--}}

{{--                        </tr>--}}
                        </tfoot>

                    </table>

{{--                </div>--}}

            </div>
        </div>

        <div class="col-sm-6 mb-4 justify-content-center" id="details-cost">
        <div class="card justify-content-center" >
            <div class="card-header" id="cost-center-name">

            </div>
            <div class="card-body">
                {{--                <div class="table-responsive">--}}

                <table class="table table-striped table-bordered table-hover table-responsive">
                    <thead>
                    <tr class="table-primary">
                        {{--                            <th rowspan="2" style="text-align: center">SL</th>--}}
                        <th>Month Name</th>
                        <th>Budget</th>
                        <th>Expense</th>
                        <th>Balance</th>
                        <th>Action</th>
                    </tr>

                    </thead>
                    <tbody>
                        @for($i=1; $i<=12; $i++)
                            <tr>
{{--                                <td style="text-align: left"><a href="{!! url('ledger/'.$row['acc_no'].'/'.$params['toDate']) !!}" class="btn btn-primary">{!! $row['acc_name'] !!}</a></td>--}}
                                <td>{!! $fiscal->where('fp_no',$i)->first()->month_name !!}</td>
                                <td style="text-align: right" id="monthly-budget-{!! $i !!}"></td>
                                <td style="text-align: right" id="monthly-expense-{!! $i !!}"></td>
                                <td style="text-align: right" id="balance-{!! $i !!}"></td>
                                <td style="text-align: right" id="details-{!! $i !!}"></td>
                            </tr>
                        @endfor
                    </tbody>
                    <tfoot>

                    </tfoot>

                </table>
            </div>
        </div>
        </div>

        <div class="col-sm-6 mb-4 justify-content-center" id="transactions">
            <div class="card justify-content-center" >
                <div class="card-header" id="cost-center-name">

                </div>
                <div class="card-body">
                    {{--                <div class="table-responsive">--}}

                    <table class="table table-striped table-bordered table-hover table-responsive">
                        <thead>
                        <tr class="table-primary">
                            {{--                            <th rowspan="2" style="text-align: center">SL</th>--}}
                            <th rowspan="2" width="25%">Transaction Date</th>
                            <th rowspan="2" width="25%">Voucher No</th>
                            <th rowspan="2" width="25%">Debit</th>
                            <th rowspan="2" width="25%">Credit</th>
                        </tr>

                        </thead>
                        <tbody>
{{--                        @for($i=1; $i<=12; $i++)--}}
{{--                            <tr>--}}
{{--                                <td></td>--}}
{{--                                <td style="text-align: right" id="monthly-budget-{!! $i !!}"></td>--}}
{{--                                <td style="text-align: right" id="monthly-expense-{!! $i !!}"></td>--}}
{{--                                <td style="text-align: right" id="balance-{!! $i !!}"></td>--}}
{{--                            </tr>--}}
{{--                        @endfor--}}
                        </tbody>
                        <tfoot>

                        </tfoot>

                    </table>
                </div>
            </div>
        </div>
    @endif


@endsection

@push('scripts')
    <script>
        $(document).ready(function(){

            $('#details-cost').hide();
            $('#transactions').hide();

            $( "#date_to" ).datetimepicker({
                format:'d-m-Y',
                timepicker: false,
                closeOnDateSelect: true,
                scrollInput : false,
                inline:false
            });

            $('#summary-table').on( 'click', '.btn-details', function () {
                // alert('oo')
                var currentRow = $(this).closest("tr");
                $('#cost-center-name').html('Cost Center : '+ currentRow.find("td:eq(2)").text());

                var i;
                var row_budget = 3;
                var row_expense = 4;
                var url = 'costcenter/transactions/'+ currentRow.find("td:eq(" + row_budget + ")").text();
                var display = 'Show Vouchers';

                for(i=1; i<=12; i++)
                {
                    $('#monthly-budget-' + i).html(currentRow.find("td:eq(" + row_budget + ")").text());
                    $('#monthly-expense-' + i).html(currentRow.find("td:eq(" + row_expense + ")").text());
                    $('#balance-' + i).html(parseFloat(currentRow.find("td:eq(" + row_budget + ")").text().replace(/,/g, "")) - parseFloat(currentRow.find("td:eq(" + row_expense + ")").text().replace(/,/g, "")));
                    $('#details-' + i).html("<a href=" + '"' + url + '"'+ "target=" +'"' + "_blank" + '"' + ">" + display + "</a>");
                    row_budget = row_budget + 2;
                    row_expense = row_expense + 2;



                    // var htmlStr = "" +
                    //     "<tr>" +
                    //     "<td>" +
                    //     "<img src=" + '"' + 0 + '"' + ">" +
                    //     "</td>" +
                    //     "<td>" +
                    //     "<a href=" + '"' + 0 + '"'+ "target=" +'"' + "_blank" + '"' + ">" + 0 + "</a>" +
                    //     "</td>" +
                    //     "</tr>"
                    // $("#summary-table").append(htmlStr);




                    // html = '<td>View</td>';
                    // $("#summary-table tr:gt(0)").append("<td>Col</td>");
                }

                // $(this).find('tr').each(function(){
                //     $(this).find('td').eq(3).after('<td>new cell added</td>');
                // });


                    $('#summary').hide();
                    $('#details-cost').show();

            } );

        });
    </script>


@endpush
