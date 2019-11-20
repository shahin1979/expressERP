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
                            <td width="10%">{!! Form::text('date_from', Carbon\Carbon::now()->format('d-m-Y'), array('id' => 'date_from', 'class' => 'form-control','required','readonly')) !!}</td>
                            <td width="5%"><label for="date_to" class="control-label" >To</label></td>
                            <td width="10%">{!! Form::text('date_to', Carbon\Carbon::now()->format('d-m-Y'), array('id' => 'date_to', 'class' => 'form-control','required','readonly')) !!}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><button name="submittype" type="submit" value="preview" class="btn btn-info btn-reject pull-left">Preview</button></td>
                            <td colspan="2"><button name="submittype" type="submit" value="print" class="btn btn-primary btn-approve pull-right">Print</button></td>
                        </tr>

                    </table>

                    {!! Form::close() !!}

                </div>
            </div>

            <div style="width: 5px"></div>

        </div>
    </div>

    @if(!empty($trans))

        <div class="row justify-content-center">

            <table class="table table-bordered table-responsive table-hover">
                <thead>
                <th>Date</th>
                <th>Voucher No</th>
                <th>Type</th>
                <th>Acc No</th>
                <th>Acc Name</th>
                <th>Description</th>
                <th style="text-align: right">Debit Amt</th>
                <th style="text-align: right">Credit Amt</th>
                <th>User</th>
                </thead>
                <tbody>
                @foreach($trans as $row)
                    <tr>
                        <td>{!! $row->trans_date !!}</td>
                        <td>{!! $row->voucher_no !!}</td>
                        <td>{!! $row->tr_code !!}</td>
                        <td>{!! $row->acc_no !!}</td>
                        <td>{!! $row->accNo->acc_name !!}</td>
                        <td>{!! $row->trans_desc1 !!}</td>
                        <td style="text-align: right">{!! number_format($row->dr_amt,2) !!}</td>
                        <td style="text-align: right">{!! number_format($row->cr_amt,2) !!}</td>
                        <td>{!! $row->user->name !!}</td>
                    </tr>
                @endforeach
                </tbody>


            </table>


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
