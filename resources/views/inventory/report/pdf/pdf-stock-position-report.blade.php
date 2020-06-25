@extends('layouts.pdf')
@section('content')

    <table class="table">

        <tr>
            <td width="15%"><img src="{!! public_path('company/'.$users_company->company_id.'_logo.jpg') !!}" style="width:100px;height:60px;"></td>
            <td width="50%" style="text-align: left"><span style="font-family:times;font-weight:bold; padding-right: 100px; line-height: 130%; height: 300%; font-size:15pt;color:black;">{!! $users_company->company->name !!}</span></td>
            <td width="35%" style="text-align: right"><span style="font-family:times;font-weight:bold; padding-right: 100px; line-height: 130%; height: 300%; font-size:12pt;color:black;">{!! $users_company->company->address !!}</span></td>

        </tr>
        <hr style="height: 2px">
    </table>

    <div class="blank-space"></div>

    <div class="row justify-content-center">
        <div class="blank-space"></div>
        <span>StocK Item Position as on : {!! \Carbon\Carbon::now()->format('d-M-Y') !!}</span>
    </div>

    {{--    <div class="blank-space"></div>--}}

    @if(!empty($report))

        <div class="container">
            <table class="table order-bank" width="100%" cellpadding="2">

                <thead>
                <tr class="row-line">
                    <th width="5%" style="text-align: left; font-size: 10px">SL No</th>
                    <th width="15%" style="text-align: left; font-size: 10px">Product Code</th>
                    <th width="35%" style="text-align: center; font-size: 10px">Product Name</th>
                    <th width="10%" style="text-align: right; font-size: 10px">Opening</th>
                    <th width="10%" style="text-align: right; font-size: 10px">Purchased</th>
                    <th width="10%" style="text-align: right; font-size: 10px">Sold</th>
                    <th width="10%" style="text-align: right; font-size: 10px">On Hand</th>
{{--                    <th width="8%" style="text-align: right; font-size: 10px">Committed</th>--}}
{{--                    <th width="10%" style="text-align: right; font-size: 10px">Available</th>--}}
                    <th width="5%" style="text-align: right; font-size: 10px">Unit</th>
                </tr>
                </thead>
                <tbody>
                @foreach($report as $i=>$item)
                    <tr class="row-line" style="line-height: 200%">
                        <td width="5%">{!! $i+1 !!}</td>
                        <td width="15%">{!! $item->sku !!}</td>
                        <td width="35%">{!! $item->name !!}</td>
                        <td width="10%" align="right">{!! number_format($item->opening_qty,0) !!}</td>
                        <td width="10%" align="right">{!! number_format($item->received_qty,0) !!}</td>
                        <td width="10%" align="right">{!! number_format($item->sell_qty,0) !!}</td>
                        <td width="10%" align="right">{!! number_format($item->onhand,0) !!}</td>
{{--                        <td width="8%" align="right">{!! number_format($item->committed,0) !!}</td>--}}
{{--                        <td width="10%" style="text-align: right">{!! number_format($item->onhand,0) - $item->committed !!}</td>--}}
                        <td width="5%">{!! $item->unit_name !!}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                {{--<tr style="line-height: 200%">--}}
                {{--<td style="border-bottom-width:0.2px; font-size:10pt;"></td>--}}
                {{--<td style="border-bottom-width:0.2px; font-size:10pt;" >Total</td>--}}
                {{--<td style="border-bottom-width:0.2px; font-size:10pt; text-align: center">{!! $totalbelcount !!}</td>--}}
                {{--<td style="border-bottom-width:0.2px; font-size:10pt; text-align: right">{!!number_format($totalgrosswt,2) !!}</td>--}}
                {{--<td style="border-bottom-width:0.2px; font-size:10pt; text-align: right">{!! number_format($totalnetwt,2) !!}</td>--}}
                {{--</tr>--}}
                </tfoot>

            </table>
        </div>

    @endif




@endsection
