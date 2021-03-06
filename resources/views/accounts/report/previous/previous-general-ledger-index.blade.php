
@extends('layouts.app')

@section('content')
    <script src="{!! asset('assets/js/jquery-3.3.1.min.js') !!}"></script>

    <script type="text/javascript">

        $(document).ready(function() {

            $('select[name="report_year"]').on('change', function() {
                var report_year = $(this).val();
                if(report_year) {
                    $.ajax({
                        url: 'ajax/' + report_year,
                        type: "GET",
                        dataType: "json",
                        success:function(data) {
                            $('select[name="acc_no"]').empty();
                            $.each(data, function(key, value) {
                                $('select[name="acc_no"]').append('<option value="'+ key +'">'+ value +'</option>');
                            });
                        }
                    });
                }else{
                    $('select[name="acc_no"]').empty();
                }
            });
        });

    </script>


    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">General Ledger</li>
        </ol>
    </nav>

    <div class="container spark-screen">

        <div class="row" id="previous">
            <div class="col-md-12" >
                <br/>
                <div><h3>General Ledger Previous Year</h3></div>
                <div style="background-color: #058553;height: 2px">&nbsp;</div>
                <br/>


                <div class="div">
                    <br/>
                    {!! Form::open(['url'=>'accounts/report/previousGenLedgerIndex', 'method' => 'GET']) !!}

                    <table width="80%" class="table table-responsive table-hover" >

                        <tr>
                            <td width="5%"><label for="date_from" class="control-label" >Year</label></td>
                            <td width="10%">{!! Form::select('report_year',[''=>'Please Select','2015-2016'=>'2015-2016', '2016-2017'=>'2016-2017','2017-2018'=>'2017-2018','2018-2019'=>'2018-2019'], 'A'  , array('id' => 'report_year', 'class' => 'form-control','required')) !!}</td>
                            <td width="5%"><label for="acc_no" class="control-label" >Account</label></td>
                            <td width="15%">{!! Form::select('acc_no',['101121002'=>'Cash In Hand'], '101121002'  , array('id' => 'acc_no', 'class' => 'form-control','required')) !!}</td>
                            <td width="5%"><label for="date_from" class="control-label" >From</label></td>
                            <td width="10%">{!! Form::text('date_from', Carbon\Carbon::now()->format('d-m-Y'), array('id' => 'date_from', 'class' => 'form-control','required','readonly')) !!}</td>
                            <td width="5%"><label for="date_to" class="control-label" >To</label></td>
                            <td width="10%">{!! Form::text('date_to', Carbon\Carbon::now()->format('d-m-Y'), array('id' => 'date_to', 'class' => 'form-control','required','readonly')) !!}</td>
                        </tr>

                        <tr>
                            <td colspan="2"><button name="action" type="submit" value="preview" class="btn btn-info btn-reject pull-left">Preview</button></td>
                            <td colspan="6"><button name="action" type="submit" value="print" class="btn btn-primary btn-approve pull-right">Print</button></td>
                        </tr>

                    </table>

                    {!! Form::close() !!}

                </div>
            </div>


        </div>
    </div>

    @if(!empty($report))

        <div class="card">
            <div class="card-header">
                <table class="table table-info">
                    <tbody>
                    <tr>
                        <td>Account Name : {!! $params['acc_no'] !!}</td>
                        <td style="text-align: right">Opening Balance: {!! number_format($params['opening_bal'],2) !!}</td>
                    </tr>
                    <tr>
                        <td>Date From : {!! $params['from_date'] !!} To {!! $params['to_date'] !!}</td>
                        <td style="text-align: right">Balance Type: {!! $params['dr_cr'] !!}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr class="table-success">
                        <th>Transaction date</th>
                        <th>Voucher No</th>
                        <th>Type</th>
                        <th>Contra</th>
                        <th>Description</th>
                        <th style="text-align: right">Debit</th>
                        <th style="text-align: right">Credit</th>
                        <th style="text-align: right">Balance</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($report as $i=>$row)
                        <tr style="background-color: {!! $i % 2 == 0 ? '#ffffff': '#afffff' !!}">
                            <td>{!! $row['tr_date'] !!}</td>
                            <td>{!! $row['voucher_no'] !!}</td>
                            <td>{!! $row['tr_code'] !!}</td>
                            <td>
                                @foreach($contra as $contraLine)
                                    @if($contraLine['voucher_no'] == $row['voucher_no'])
                                        {!! $contraLine['acc_name'] !!}<br/>
                                    @endif
                                @endforeach
                            </td>
                            <td>{!! $row['description'] !!}</td>
                            <td style="text-align: right">{!! number_format($row['dr_amt'],2) !!}</td>
                            <td style="text-align: right">{!! number_format($row['cr_amt'],2) !!}</td>
                            <td style="text-align: right">{!! number_format($row['balance'],2) !!}</td>

                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr style="background-color: #3A92AF">
                        <td colspan="5">Period Total</td>
                        <td style="text-align: right">{!! number_format($report->sum('dr_amt'),2)  !!}</td>
                        <td style="text-align: right">{!! number_format($report->sum('cr_amt'),2)  !!}</td>
                    </tr>

                    <tr style="background-color: rgba(10,170,158,0.48)">
                        <td colspan="5">Closing Balance</td>
                        <td colspan="2" style="text-align: right">{!! number_format(($params['opening_bal'] + $report->sum('dr_amt') - $report->sum('cr_amt')),2) !!}</td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
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
                inline:false,
                // minDate:'01-07-2017',
                // maxDate:new Date('2018-06-30')
            });

        });
    </script>


@endpush
