@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Print Voucher</li>
        </ol>
    </nav>

    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-8 col-md-offset-1" >
                <br/>
                <div><h3>Voucher Report</h3></div>
                <div style="background-color: #ff0000;height: 2px">&nbsp;</div>
                <br/>


                <div class="div">
                    <br/>
                    {!! Form::open(['url'=>'accounts/report/printVoucherIndex', 'method' => 'GET']) !!}

                    <table width="80%" class="table table-responsive table-hover" >

                        <tr>
                            <td width="5%"><label for="date_from" class="control-label" >Transaction Date</label></td>
                            <td width="10%">{!! Form::text('date_from', empty($vouchers) ? Carbon\Carbon::now()->format('d-m-Y') : $vouchers->min('trans_date') , array('id' => 'date_from', 'class' => 'form-control','required','readonly')) !!}</td>
                            <td width="5%"><label for="date_to" class="control-label" >To</label></td>
                            <td width="10%">{!! Form::text('date_to', empty($vouchers) ? Carbon\Carbon::now()->format('d-m-Y') : $vouchers->max('trans_date'), array('id' => 'date_to', 'class' => 'form-control','required','readonly')) !!}</td>
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

    @if(!empty($trans))

        @foreach($vouchers as $voucher)

        <div class="card">
            <div class="card-body">


                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr class="table-success">
                                    <th colspan="2">Voucher No :{!! $voucher->voucher_no !!} <br/>
                                        Voucher Code :{!! $voucher->code->trans_name !!} </th>

                                    <th colspan="2" style="text-align: right">Voucher Date :{!! $voucher->trans_date !!}<br/>
                                        User :{!! $voucher->user->name !!}</th>
                                </tr>
{{--                                <tr>--}}
{{--                                    <th colspan="2">Voucher Code :{!! $voucher->code->trans_name !!}</th>--}}
{{--                                    <th colspan="2" style="text-align: right">User :{!! $voucher->user->name !!}</th>--}}
{{--                                </tr>--}}
                                <tr class="table-primary">
                                    <th>Account Head</th>
                                    <th>Description</th>
                                    <th style="text-align: right">Debit</th>
                                    <th style="text-align: right">Credit</th>
                                </tr>
                            </thead>

                            <tbody>

                            @foreach($trans as $row)
                                @if($voucher->voucher_no == $row->voucher_no)
                                    <tr>
                                        <td>{!! $row->acc_no !!} : {!! $row->account->acc_name !!}</td>
                                        <td>{!! $row->trans_desc1 !!}</td>
                                        <td style="text-align: right">{!! number_format($row->dr_amt,2) !!}</td>
                                        <td style="text-align: right">{!! number_format($row->cr_amt,2) !!}</td>
                                    </tr>
                                @endif

                            @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-primary">
                                    <td colspan="2" style="font-weight: bold">Total</td>
                                    <td style="text-align: right; font-weight: bold">{!!number_format($trans->where('voucher_no',$voucher->voucher_no)->sum('dr_amt'),2) !!}</td>
                                    <td style="text-align: right; font-weight: bold">{!! number_format($trans->where('voucher_no',$voucher->voucher_no)->sum('cr_amt'),2) !!}</td>
                                </tr>
                            </tfoot>
                        </table>


            </div>
        </div>

        @endforeach

    @endif

{{--        <div class="row justify-content-center">--}}

{{--            <table class="table table-bordered table-responsive table-hover">--}}
{{--                <thead>--}}

{{--                <tr>--}}
{{--                    <th>Voucher No</th>--}}
{{--                </tr>--}}

{{--                <tr>--}}
{{--                    <th>Head of Accounts</th>--}}

{{--                    <th>Type</th>--}}
{{--                    <th>Acc No</th>--}}
{{--                    <th>Acc Name</th>--}}
{{--                    <th>Description</th>--}}
{{--                    <th style="text-align: right">Debit Amt</th>--}}
{{--                    <th style="text-align: right">Credit Amt</th>--}}
{{--                    <th>User</th>--}}
{{--                </tr>--}}

{{--                </thead>--}}
{{--                <tbody>--}}
{{--                @foreach($trans as $row)--}}
{{--                    <tr>--}}
{{--                        <td>{!! $row->trans_date !!}</td>--}}
{{--                        <td>{!! $row->voucher_no !!}</td>--}}
{{--                        <td>{!! $row->tr_code !!}</td>--}}
{{--                        <td>{!! $row->acc_no !!}</td>--}}
{{--                        <td>{!! $row->accNo->acc_name !!}</td>--}}
{{--                        <td>{!! $row->trans_desc1 !!}</td>--}}
{{--                        <td style="text-align: right">{!! number_format($row->dr_amt,2) !!}</td>--}}
{{--                        <td style="text-align: right">{!! number_format($row->cr_amt,2) !!}</td>--}}
{{--                        <td>{!! $row->user->name !!}</td>--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
{{--                </tbody>--}}


{{--            </table>--}}


{{--        </div>--}}


{{--    @endif--}}

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
