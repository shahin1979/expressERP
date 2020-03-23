@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Trial Balance</li>
        </ol>
    </nav>

    <div class="container spark-screen">
        <div class="row" id="current">
            <div class="col-md-8 col-md-offset-1" >
                <br/>
                <div><h3>Trial Balance</h3></div>
                <div style="background-color: #ff0000;height: 2px">&nbsp;</div>
                <br/>


                <div class="div">
                    <br/>
                    {!! Form::open(['url'=>'ledger/rptTrialBalanceIndex', 'method' => 'GET']) !!}

                    <table width="80%" class="table table-responsive table-hover" >

                        <tr>
                            <td width="5%"><label for="date_from" class="control-label" >Trial Balance For</label></td>
                            <td width="10%">{!! Form::select('report_type',['A'=>'Account Wise', 'G'=>'Group Wise'], 'A'  , array('id' => 'report_type', 'class' => 'form-control','required')) !!}</td>
                            <td width="5%"><label for="date_to" class="control-label" >As On</label></td>
                            <td width="10%">{!! Form::text('date_to', Carbon\Carbon::now()->format('d-m-Y'), array('id' => 'date_to', 'class' => 'form-control','required','readonly')) !!}</td>
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

            <div class="col-md-3" >

                <div class="div">
                    <br/>
                    {!! Form::open(['url'=>'ledger/previousTrialBalanceIndex', 'method' => 'GET']) !!}

                    <table width="80%" class="table table-responsive table-hover" >


                        <tr>
                            <td colspan="2"><button name="action" type="submit" class="btn btn-warning btn-reject pull-left">Previous Year</button></td>
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
                Trial Balance As On {!! $params['toDate'] !!}
            </div>
            <div class="card-body">
                <div class="table-responsive">

                    <table class="table table-striped table-bordered table-hover table-responsive">
                        <thead>
                        <tr class="table-primary">
                            <th>Acc No</th>
                            <th>Acc Name</th>
                            <th>Acc Type</th>
                            <th style="text-align: right">Opening Debit</th>
                            <th style="text-align: right">Opening Credit</th>
                            <th style="text-align: right">Debit</th>
                            <th style="text-align: right">Credit</th>
                            <th style="text-align: right">Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($report as $i=>$row)
                            @if($row['is_group'] == $params['report_type'])
                            <tr style="background-color: {!! $i % 2 == 0 ? '#ffffff': '#afffff' !!}">
                                <td>{!! $row['acc_no'] !!}</td>
                                <td>{!! $row['acc_name'] !!}</td>
                                <td>{!! $row['acc_type'] !!}</td>
                                <td style="text-align: right">{!! number_format($row['opening_dr'],2) !!}</td>
                                <td style="text-align: right">{!! number_format($row['opening_cr'],2) !!}</td>

                                @if($params['report_type'] == false)

                                <td style="text-align: right">{!! number_format($row['dr_tr'],2) !!}</td>
                                <td style="text-align: right">{!! number_format($row['cr_tr'],2) !!}</td>
                                <td style="text-align: right">{!! number_format($row['balance'],2)  !!}</td>
                                @else
                                    <td style="text-align: right">{!! number_format($report->where('ledger_code',$row['ledger_code'])->where('is_group',false)->sum('dr_tr'),2) !!}</td>
                                    <td style="text-align: right">{!! number_format($report->where('ledger_code',$row['ledger_code'])->where('is_group',false)->sum('cr_tr'),2) !!}</td>
                                    <td style="text-align: right">{!! number_format($report->where('ledger_code',$row['ledger_code'])->where('is_group',false)->sum('balance'),2)  !!}</td>
                                @endif
                            </tr>
                            @endif
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #3A92AF">
                                <td colspan="3">Grand Total</td>
                                <td style="text-align: right">{!! number_format($report->where('is_group',false)->sum('opening_dr'),2)  !!}</td>
                                <td style="text-align: right">{!! number_format($report->where('is_group',false)->sum('opening_cr'),2)  !!}</td>
                                <td style="text-align: right">{!! number_format($report->where('is_group',false)->sum('dr_tr'),2)  !!}</td>
                                <td style="text-align: right">{!! number_format($report->where('is_group',false)->sum('cr_tr'),2)  !!}</td>
                                <td style="text-align: right">{!! number_format($report->where('is_group',false)->sum('balance'),2)  !!}</td>

                            </tr>
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

        $( "#date_to" ).datetimepicker({
            format:'d-m-Y',
            timepicker: false,
            closeOnDateSelect: true,
            scrollInput : false,
            inline:false
        });

        $( "#p_date_to" ).datetimepicker({
            format:'d-m-Y',
            timepicker: false,
            closeOnDateSelect: true,
            scrollInput : false,
            inline:false
        });
    });
</script>


@endpush
