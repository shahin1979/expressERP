@extends('layouts.pdf')
@section('content')
    <table class="table">

        <tr>
            <td width="15%"><img src="{!! public_path('/assets/images/logo.jpg') !!}" style="width:250px;height:60px;"></td>
            <td width="35%" style="text-align: left"><span style="font-family:times;font-weight:bold; padding-right: 100px; line-height: 130%; height: 300%; font-size:15pt;color:black;">{!! $company->company->name !!}</span></td>
            <td width="50%" style="text-align: right"><span style="font-family:times;font-weight:bold; padding-right: 100px; line-height: 130%; height: 300%; font-size:12pt;color:black;">{!! $company->company->address !!}</span></td>

        </tr>
        <hr style="height: 2px">
    </table>

    <div class="blank-space"></div>

    <div class="row justify-content-center">
        <div class="blank-space"></div>
        <span>Daily Transaction List From {!! $dates->min('trans_date') !!} To {!! $dates->max('trans_date') !!}</span>
    </div>
{{--    <div class="blank-space"></div>--}}


    @if(!empty($trans))

        <div class="row justify-content-center">

            @foreach($dates as $date)


                <table class="table">
                    <thead>
                    <tr>
                        <th colspan="9">Transaction date : {!! $date->trans_date !!}</th>
                    </tr>
                    <tr class="row-line">
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
                    <tbody>
                    @foreach($trans as $row)
                        @if($date->trans_date == $row->trans_date)
                            <tr style="line-height: 250%">
                                <td style="border-bottom-width:1px; font-size:8pt; text-align: left">{!! $row->trans_date !!}</td>
                                <td style="border-bottom-width:1px; font-size:8pt; text-align: left">{!! $row->voucher_no !!}</td>
                                <td style="border-bottom-width:1px; font-size:8pt; text-align: left">{!! $row->tr_code !!}</td>
                                <td style="border-bottom-width:1px; font-size:8pt; text-align: left">{!! $row->acc_no !!}</td>
                                <td style="border-bottom-width:1px; font-size:8pt; text-align: left">{!! $row->accNo->acc_name !!}</td>
                                <td style="border-bottom-width:1px; font-size:8pt; text-align: left">{!! $row->trans_desc1 !!}</td>
                                <td style="border-bottom-width:1px; font-size:8pt; text-align: right">{!! number_format($row->dr_amt,2) !!}</td>
                                <td style="border-bottom-width:1px; font-size:8pt; text-align: right">{!! number_format($row->cr_amt,2) !!}</td>
                                <td style="border-bottom-width:1px; font-size:8pt; text-align: left">{!! $row->user->name !!}</td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="6">Total</td>
                        <td style="text-align: right">{!!number_format($trans->where('trans_date',$date->trans_date)->sum('dr_amt'),2) !!}</td>
                        <td style="text-align: right">{!! number_format($trans->where('trans_date',$date->trans_date)->sum('cr_amt'),2) !!}</td>
                        <td></td>
                    </tr>
                    </tfoot>


                </table>

            @endforeach


        </div>


    @endif

@endsection
