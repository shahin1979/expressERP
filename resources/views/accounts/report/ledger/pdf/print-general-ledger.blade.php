@extends('layouts.pdf')
@section('content')
    <table class="table">

        <tr>
            <td width="15%"><img src="{!! public_path($users_company->company_logo) !!}" style="width:100px;height:100px;"></td>
            <td width="50%" style="text-align: left"><span style="font-family:times;font-weight:bold; padding-right: 100px; line-height: 130%; height: 300%; font-size:15pt;color:black;">{!! $users_company->company->name !!}</span></td>
            <td width="35%" style="text-align: right"><span style="font-family:times;font-weight:bold; padding-right: 100px; line-height: 130%; height: 300%; font-size:12pt;color:black;">{!! $users_company->company->address !!}</span></td>

        </tr>
        <hr style="height: 2px">
    </table>

    <div class="blank-space"></div>
    <div class="blank-space"></div>

{{--    <table class="table table-responsive">--}}
{{--        <tbody>--}}
{{--        <tr>--}}
{{--            <td style="text-align: center">Trial Balance From Date {!! \Carbon\Carbon::parse($params['toDate'])->format('01-M-Y') !!} To {!! \Carbon\Carbon::parse($params['toDate'])->format('d-M-Y') !!}</td>--}}
{{--        </tr>--}}
{{--        </tbody>--}}

{{--    </table>--}}


    {{--    <div class="blank-space"></div>--}}


    @if(!empty($report))
        <div class="row">
            <table class="table">
                <tbody>
                <tr>
                    <td style="font-size:10pt">Account Name : {!! $params['acc_no'] !!} : {!! $params['acc_name'] !!}</td>
                    <td style="font-size:10pt; text-align: right">Opening Balance: {!! number_format($params['opening_bal'],2) !!}</td>
                </tr>
                <tr>
                    <td style="font-size:10pt">General Ledger Date From : {!! $params['from_date'] !!} To {!! $params['to_date'] !!}</td>
                    <td style="font-size:10pt; text-align: right">Balance Type: {!! $params['dr_cr'] !!}</td>
                </tr>
                </tbody>
            </table>
        </div>

        @php
            $dr_period = 0;
            $cr_period = 0;
        @endphp

        <div class="row">
            <table class="table">
                <thead>
                    <tr class="row-line" style="border-bottom: solid; line-height: 200%;">
                        <th width="10%" style="font-size:8pt; font-weight: bold">Date</th>
                        <th width="12%" style="font-size:8pt; font-weight: bold">Voucher No</th>
                        <th width="5%" style="font-size:8pt; font-weight: bold; text-align: center">TP</th>
                        <th width="28%" style="font-size:8pt; font-weight: bold; text-align: right">Contra</th>
    {{--                    <th width="15%" style="font-size:8pt; font-weight: bold;">Particulars</th>--}}
                        <th width="15%" style="font-size:8pt; font-weight: bold; text-align: right">Debit</th>
                        <th width="15%" style="font-size:8pt; font-weight: bold; text-align: right">Credit</th>
                        <th width="15%" style="font-size:8pt; font-weight: bold; text-align: right">Balance</th>

                    </tr>

                </thead>

                <tbody>
                @foreach($report as $i=>$row)
                    <tr style="line-height: 200%">
                        <td width="10%" style="border-bottom-width:1px; font-size:8pt;">{!! $row['tr_date'] !!}</td>
                        <td width="12%" style="border-bottom-width:1px; font-size:8pt; text-align: left">{!! $row['voucher_no'] !!}</td>
                        <td width="5%" style="border-bottom-width:1px; font-size:8pt; text-align: left">{!! $row['tr_code'] !!}</td>
                        <td width="28%" style="border-bottom-width:1px; font-size:6pt; text-align: left">
                            @foreach($contra as $contraLine)
                                @if($contraLine['voucher_no'] == $row['voucher_no'])
                                   {!! $contraLine['acc_name'] !!}{!! count($contra->where('voucher_no',$row['voucher_no'])) > 1 ? '<br/>' : null !!}
                                @endif
                            @endforeach
                        </td>
{{--                        <td width="15%" style="font-size:8pt; text-align: left">{!! $row['description'] !!}</td>--}}
                        <td width="15%" style="border-bottom-width:1px; font-size:8pt; text-align: right">{!! number_format($row['dr_amt'],2) !!}</td>
                        <td width="15%" style="border-bottom-width:1px; font-size:8pt; text-align: right">{!! number_format($row['cr_amt'],2) !!}</td>
                        <td width="15%" style="border-bottom-width:1px; font-size:8pt; text-align: right">{!! number_format($row['balance'],2) !!}</td>

                    </tr>

                    @php($dr_period = $dr_period + $row['dr_amt'])
                    @php($cr_period = $cr_period + $row['cr_amt'])

                @endforeach
                </tbody>


                <tfoot>
{{--                <tr style="line-height: 300%">--}}
{{--                    <td colspan="3" style="border-bottom-width:1px; font-size:8pt; font-weight: bold;">Grand Total</td>--}}
{{--                    <td style="border-bottom-width:1px; font-size:8pt; font-weight: bold; text-align: right">{!! number_format(($report->where('is_group',false)->sum('opening_dr') - $report->where('is_group',false)->sum('opening_cr')),2)  !!}</td>--}}
{{--                    <td style="border-bottom-width:1px; font-size:8pt; font-weight: bold; text-align: right">{!! number_format($report->where('is_group',false)->sum('dr_tr'),2)  !!}</td>--}}
{{--                    <td style="border-bottom-width:1px; font-size:8pt; font-weight: bold; text-align: right">{!! number_format($report->where('is_group',false)->sum('cr_tr'),2)  !!}</td>--}}
{{--                    <td style="border-bottom-width:1px; font-size:8pt; font-weight: bold; text-align: right">{!! number_format($report->where('is_group',false)->sum('balance'),2)  !!}</td>--}}

{{--                </tr>--}}
                </tfoot>



            </table>
        </div>
    @endif

@endsection
