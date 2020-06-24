@extends('layouts.pdf')
@section('content')





    @if(!empty($trans))

        @foreach($vouchers as $voucher)

            <table class="table">

                <tr>
                    <td width="15%"><img src="{!! public_path('company/'.$users_company->company_id.'_logo.jpg') !!}" style="width:40px;height:40px;"></td>
                    <td width="50%" style="text-align: left"><span style="font-family:times;font-weight:bold; padding-right: 100px; line-height: 130%; height: 300%; font-size:15pt;color:black;">{!! $users_company->company->name !!}</span></td>
                    <td width="35%" style="text-align: right"><span style="font-family:times;font-weight:bold; padding-right: 100px; line-height: 130%; height: 300%; font-size:12pt;color:black;">{!! $users_company->company->address !!}</span></td>

                </tr>
                <hr style="height: 2px">
            </table>

            <div class="blank-space"></div>
            <div class="blank-space"></div>


            <table class="table">
                <thead>
                <tr>
                    <th colspan="2" style="border-bottom-width:0.2px; font-size:10pt;">Voucher No :{!! $voucher->voucher_no !!} <br/>
                        Voucher Code :{!! $voucher->code->trans_name !!} </th>

                    <th colspan="2" style="border-bottom-width:0.2px; font-size:10pt; text-align: right">Voucher Date :{!! $voucher->trans_date !!}<br/>
                        User :{!! $voucher->user->name !!}</th>
                </tr>

                </thead>
            </table>


            <table class="table">
                <thead>

                    <tr class="row-line" style="line-height: 200%">
                        <th width="45%">Account Head</th>
                        <th width="25%">Description</th>
                        <th width="15%" style="text-align: right">Debit</th>
                        <th width="15%" style="text-align: right">Credit</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($trans as $row)
                    @if($voucher->voucher_no == $row->voucher_no)
                        <tr style="line-height: 200%">
                            <td width="45%" style="border-bottom-width:0.2px; font-size:10pt;">{!! $row->acc_no !!} : {!! $row->account->acc_name !!}</td>
                            <td width="25%" style="border-bottom-width:0.2px; font-size:8pt;">{!! $row->trans_desc1 !!}</td>
                            <td width="15%" style="border-bottom-width:0.2px; font-size:10pt; text-align: right">{!! number_format($row->dr_amt,2) !!}</td>
                            <td width="15%" style="border-bottom-width:0.2px; font-size:10pt; text-align: right">{!! number_format($row->cr_amt,2) !!}</td>
                        </tr>
                    @endif

                @endforeach
                </tbody>

                <div class="blank-space"></div>

                <tfoot>
                <tr class="table-primary">

                    <td colspan="4" style="font-size:10pt; text-align: left; font-weight: bold">Total: ({!! convert_number_to_words_bd_format($trans->where('voucher_no',$voucher->voucher_no)->sum('dr_amt')) !!}) : {!! number_format($trans->where('voucher_no',$voucher->voucher_no)->sum('dr_amt'),2) !!}BDT</td>
{{--                    <td style="text-align: right; font-weight: bold">{!! number_format($trans->where('voucher_no',$voucher->voucher_no)->sum('cr_amt'),2) !!}</td>--}}
                </tr>
                </tfoot>
            </table>



            <br pagebreak="true">

        @endforeach

    @endif


{{--    <div class="row justify-content-center">--}}
{{--        <div class="blank-space"></div>--}}
{{--        <span>Daily Transaction List From {!! $dates->min('trans_date') !!} To {!! $dates->max('trans_date') !!}</span>--}}
{{--    </div>--}}
    {{--    <div class="blank-space"></div>--}}


{{--    @if(!empty($trans))--}}

{{--        <div class="row justify-content-center">--}}

{{--            @foreach($dates as $date)--}}


{{--                <table class="table">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th colspan="9">Transaction date : {!! $date->trans_date !!}</th>--}}
{{--                    </tr>--}}
{{--                    <tr class="row-line" style="line-height: 300%">--}}
{{--                        <th width="50px" style="border-bottom-width:1px; font-size:8pt; text-align: left; font-weight: bold">Date</th>--}}
{{--                        <th style="border-bottom-width:1px; font-size:8pt; text-align: left; font-weight: bold">Voucher No</th>--}}
{{--                        <th width="25px" style="border-bottom-width:1px; font-size:8pt; text-align: left; font-weight: bold">Type</th>--}}
{{--                        <th width="45px" style="border-bottom-width:1px; font-size:8pt; text-align: left; font-weight: bold">Acc No</th>--}}
{{--                        <th width="90px"style="border-bottom-width:1px; font-size:8pt; text-align: left; font-weight: bold">Acc Name</th>--}}
{{--                        <th width="90px" style="border-bottom-width:1px; font-size:8pt; text-align: left; font-weight: bold">Description</th>--}}
{{--                        <th style="border-bottom-width:1px; font-size:8pt; text-align: right; font-weight: bold" >Debit Amt</th>--}}
{{--                        <th style="border-bottom-width:1px; font-size:8pt; text-align: right; font-weight: bold">Credit Amt</th>--}}
{{--                        <th style="border-bottom-width:1px; font-size:8pt; text-align: left; font-weight: bold">User</th>--}}
{{--                    </tr>--}}

{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                    @foreach($trans as $row)--}}
{{--                        @if($date->trans_date == $row->trans_date)--}}
{{--                            <tr style="line-height: 250%">--}}
{{--                                <td width="50px" style="border-bottom-width:1px; font-size:8pt; text-align: left">{!! $row->trans_date !!}</td>--}}
{{--                                <td style="border-bottom-width:1px; font-size:8pt; text-align: left">{!! $row->voucher_no !!}</td>--}}
{{--                                <td width="25px" style="border-bottom-width:1px; font-size:8pt; text-align: left">{!! $row->tr_code !!}</td>--}}
{{--                                <td width="45px" style="border-bottom-width:1px; font-size:8pt; text-align: left">{!! $row->acc_no !!}</td>--}}
{{--                                <td width="90px"style="border-bottom-width:1px; font-size:8pt; text-align: left">{!! $row->account->acc_name !!}</td>--}}
{{--                                <td width="90px" style="border-bottom-width:1px; font-size:8pt; text-align: left">{!! $row->trans_desc1 !!}</td>--}}
{{--                                <td style="border-bottom-width:1px; font-size:8pt; text-align: right">{!! number_format($row->dr_amt,2) !!}</td>--}}
{{--                                <td style="border-bottom-width:1px; font-size:8pt; text-align: right">{!! number_format($row->cr_amt,2) !!}</td>--}}
{{--                                <td style="border-bottom-width:1px; font-size:8pt; text-align: left">{!! $row->user->name !!}</td>--}}
{{--                            </tr>--}}
{{--                        @endif--}}
{{--                    @endforeach--}}
{{--                    </tbody>--}}
{{--                    <tfoot>--}}
{{--                    <tr>--}}
{{--                        <td colspan="6" style="text-align: right; font-size:8pt; font-weight: bold">Total</td>--}}
{{--                        <td style="text-align: right; font-size:8pt;font-weight: bold">{!!number_format($trans->where('trans_date',$date->trans_date)->sum('dr_amt'),2) !!}</td>--}}
{{--                        <td style="text-align: right; font-size:8pt; font-weight: bold">{!! number_format($trans->where('trans_date',$date->trans_date)->sum('cr_amt'),2) !!}</td>--}}
{{--                        <td></td>--}}
{{--                    </tr>--}}
{{--                    </tfoot>--}}


{{--                </table>--}}

{{--            @endforeach--}}


{{--        </div>--}}


{{--    @endif--}}

@endsection
