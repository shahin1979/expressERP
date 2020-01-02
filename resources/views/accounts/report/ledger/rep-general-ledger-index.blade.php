@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">General Ledger</li>
        </ol>
    </nav>

    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-10 col-md-offset-1" >
                <br/>
                <div><h3>General Ledger Report</h3></div>
                <div style="background-color: #ff0000;height: 2px">&nbsp;</div>
                <br/>


                <div class="div">
                    <br/>
                    {!! Form::open(['url'=>'ledger/rptGeneralLedgerIndex', 'method' => 'GET']) !!}

                    <table width="90%" class="table table-responsive table-hover" >

                        <tr>
                            <td width="5%"><label for="acc_no" class="control-label" >Account</label></td>
                            <td width="15%">{!! Form::select('acc_no',$ledgers, null  , array('id' => 'acc_no', 'class' => 'form-control','required')) !!}</td>
                            <td width="5%"><label for="date_from" class="control-label" >From</label></td>
                            <td width="10%">{!! Form::text('date_from', Carbon\Carbon::now()->format('d-m-Y'), array('id' => 'date_from', 'class' => 'form-control','required','readonly')) !!}</td>
                            <td width="5%"><label for="date_to" class="control-label" >To</label></td>
                            <td width="10%">{!! Form::text('date_to', Carbon\Carbon::now()->format('d-m-Y'), array('id' => 'date_to', 'class' => 'form-control','required','readonly')) !!}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><button name="action" type="submit" value="preview" class="btn btn-info btn-reject pull-left">Preview</button></td>
                            <td colspan="4"><button name="action" type="submit" value="print" class="btn btn-primary btn-approve pull-right">Print</button></td>
                        </tr>

                    </table>

                    {!! Form::close() !!}

                </div>
            </div>

            <div style="width: 5px"></div>

        </div>
    </div>

    @if(!empty($report))

        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-striped table-bordered table-hover table-responsive">
                                <tbody>
                                <tr>
                                    <td>Account Name</td>
                                    <td>{!! $params['acc_name'] !!}</td>
                                    <td>{!! $params['acc_no'] !!}</td>

                                </tr>

                                <tr>
                                    <td>Date</td>
                                    <td>From : {!! $params['from_date'] !!}</td>
                                    <td>To: {!! $params['to_date'] !!}</td>

                                </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card">

                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-striped table-bordered table-hover table-responsive">
                                <tbody>
                                <tr>
                                    <td>Opening Balance</td>
                                    <td>{!! number_format($params['opening_bal'],2) !!}</td>
                                    <td>{!! $params['dr_cr'] !!}</td>

                                </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>





        <div class="card">

            <div class="card-header">Account Name : {!! $params['acc_no'] !!} - {!! $params['acc_name'] !!} <span style="text-align: right">Opening Balance </span><br/>
                Date : From : {!! $params['from_date'] !!} To {!! $params['to_date'] !!}

            </div>

            <div class="card-body">
                <div class="table-responsive">

                    <table class="table table-striped table-bordered table-hover table-responsive">
                        <thead>
                        <tr >
                            <th>Transaction date</th>
                            <th>Voucher No</th>
                            <th>Type</th>
                            <th>Contra</th>
                            <th>Description</th>
                            <th style="text-align: right">Debit</th>
                            <th style="text-align: right">Credit</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($report as $i=>$row)
                            <tr style="background-color: {!! $i%2 == 0 ? '#67b84c' : '#ffffff' !!}">
                                <td>{!! $row['tr_date'] !!}</td>
                                <td>{!! $row['voucher_no'] !!}</td>
                                <td>{!! $row['tr_code'] !!}</td>
                                <td>{!! $row['contra'] !!}</td>
                                <td>{!! $row['description'] !!}</td>
                                <td style="text-align: right">{!! number_format($row['dr_amt'],2) !!}</td>
                                <td style="text-align: right">{!! number_format($row['cr_amt'],2) !!}</td>

                            </tr>
                        @endforeach
                        </tbody>
{{--                        <tfoot>--}}
{{--                        <tr style="background-color: #3A92AF">--}}
{{--                            <td colspan="3">Grand Total</td>--}}
{{--                            <td style="text-align: right">{!! number_format($report->sum('opening_dr'),2)  !!}</td>--}}
{{--                            <td style="text-align: right">{!! number_format($report->sum('opening_cr'),2)  !!}</td>--}}
{{--                            <td style="text-align: right">{!! number_format($report->sum('dr_tr'),2)  !!}</td>--}}
{{--                            <td style="text-align: right">{!! number_format($report->sum('cr_tr'),2)  !!}</td>--}}
{{--                            <td style="text-align: right">{!! number_format($report->sum('balance'),2)  !!}</td>--}}

{{--                        </tr>--}}
{{--                        </tfoot>--}}

                    </table>

                </div>

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
                inline:false
            });
        });

    </script>

@endpush
