@extends('layouts.pdf')
@section('content')
    <table class="table">

        <tr>
            <td width="15%"><img src="{!! public_path('company/'.$users_company->company_id.'_logo.jpg') !!}" style="width:100px;height:100px;"></td>
            <td width="50%" style="text-align: left"><span style="font-family:times;font-weight:bold; padding-right: 100px; line-height: 130%; height: 300%; font-size:15pt;color:black;">{!! $users_company->company->name !!}</span></td>
            <td width="35%" style="text-align: right"><span style="font-family:times;font-weight:bold; padding-right: 100px; line-height: 130%; height: 300%; font-size:12pt;color:black;">{!! $users_company->company->address !!}</span></td>

        </tr>
        <hr style="height: 2px">
    </table>

    <div class="blank-space"></div>
    <div class="blank-space"></div>

    <table class="table table-responsive">
        <tbody>
        <tr>
            <td style="text-align: center">Trial Balance From Date {!! \Carbon\Carbon::parse($params['toDate'])->format('01-M-Y') !!} To {!! \Carbon\Carbon::parse($params['toDate'])->format('d-M-Y') !!}</td>
        </tr>
        </tbody>

    </table>


    {{--    <div class="blank-space"></div>--}}


    @if(!empty($report))

        <div class="row">
            <table class="table">
                <thead>
                <tr class="row-line" style="border-bottom: solid; line-height: 200%;">
                    <th width="8%" style="font-size:10pt">Acc No</th>
                    <th width="27%" style="font-size:10pt">Name</th>
                    <th width="5%" style="font-size:10pt; text-align: center">TP</th>
                    <th width="15%" style="font-size:10pt; text-align: right">Opening</th>
{{--                    <th width="11%" style="font-size:10pt; text-align: right">Opening Credit</th>--}}
                    <th width="15%" style="font-size:10pt; text-align: right">Debit</th>
                    <th width="15%" style="font-size:10pt; text-align: right">Credit</th>
                    <th width="15%" style="font-size:10pt; text-align: right">Balance</th>

                </tr>

                </thead>
                <tbody>

                    @foreach($report as $i=>$row)
                        @if($row['is_group'] == $params['report_type'])
                            <tr style="line-height: 200%">
                                <td width="8%" style="border-bottom-width:1px; font-size:8pt;">{!! $row['acc_no'] !!}</td>
                                <td width="27%" style="border-bottom-width:1px; font-size:8pt;">{!! $row['acc_name'] !!}</td>
                                <td width="5%" style="border-bottom-width:1px; font-size:8pt; text-align: center">{!! $row['acc_type'] !!}</td>
                                <td width="15%" style="border-bottom-width:1px; font-size:8pt; text-align: right">{!! number_format(($row['opening_dr'] - $row['opening_cr']),2) !!}</td>
{{--                                <td width="11%" style="border-bottom-width:1px; font-size:8pt; text-align: right">{!! number_format($row['opening_cr'],2) !!}</td>--}}

                                @if($params['report_type'] == false)

                                    <td width="15%" style="border-bottom-width:1px; font-size:8pt; text-align: right">{!! number_format($row['dr_tr'],2) !!}</td>
                                    <td width="15%" style="border-bottom-width:1px; font-size:8pt; text-align: right">{!! number_format($row['cr_tr'],2) !!}</td>
                                    <td width="15%" style="border-bottom-width:1px; font-size:8pt; text-align: right">{!! number_format($row['balance'],2)  !!}</td>
                                @else
                                    <td width="15%" style="border-bottom-width:1px; font-size:8pt; text-align: right">{!! number_format($report->where('ledger_code',$row['ledger_code'])->where('is_group',false)->sum('dr_tr'),2) !!}</td>
                                    <td width="15%" style="border-bottom-width:1px; font-size:8pt; text-align: right">{!! number_format($report->where('ledger_code',$row['ledger_code'])->where('is_group',false)->sum('cr_tr'),2) !!}</td>
                                    <td width="15%" style="border-bottom-width:1px; font-size:8pt; text-align: right">{!! number_format($report->where('ledger_code',$row['ledger_code'])->where('is_group',false)->sum('balance'),2)  !!}</td>
                                @endif
                            </tr>
                        @endif
                    @endforeach


                </tbody>

                <tfoot>
                <tr style="line-height: 300%">
                    <td colspan="3" style="border-bottom-width:1px; font-size:8pt; font-weight: bold;">Grand Total</td>
                    <td style="border-bottom-width:1px; font-size:8pt; font-weight: bold; text-align: right">{!! number_format(($report->where('is_group',false)->sum('opening_dr') - $report->where('is_group',false)->sum('opening_cr')),2)  !!}</td>
                    <td style="border-bottom-width:1px; font-size:8pt; font-weight: bold; text-align: right">{!! number_format($report->where('is_group',false)->sum('dr_tr'),2)  !!}</td>
                    <td style="border-bottom-width:1px; font-size:8pt; font-weight: bold; text-align: right">{!! number_format($report->where('is_group',false)->sum('cr_tr'),2)  !!}</td>
                    <td style="border-bottom-width:1px; font-size:8pt; font-weight: bold; text-align: right">{!! number_format($report->where('is_group',false)->sum('balance'),2)  !!}</td>

                </tr>
                </tfoot>



            </table>
        </div>
    @endif

@endsection
