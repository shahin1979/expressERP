@extends('layouts.pdf')
@section('content')

    <table class="table">

        <tr>
            <td width="15%"><img src="{!! public_path('company/'.$users_company->company_id.'_logo.jpg') !!}" style="width:250px;height:60px;"></td>
            <td width="50%" style="text-align: left"><span style="font-family:times;font-weight:bold; padding-right: 100px; line-height: 130%; height: 300%; font-size:15pt;color:black;">{!! $users_company->company->name !!}</span></td>
            <td width="35%" style="text-align: right"><span style="font-family:times;font-weight:bold; padding-right: 100px; line-height: 130%; height: 300%; font-size:12pt;color:black;">{!! $users_company->company->address !!}</span></td>

        </tr>
        <hr style="height: 2px">
    </table>

    <div class="blank-space"></div>

    <div class="row justify-content-center">
        <div class="blank-space"></div>
        <span>Requisition No : {!! $requisition->ref_no !!}</span>
    </div>

{{--    <div class="blank-space"></div>--}}

    @if(!empty($requisition))
        <table class="table order-bank" width="90%" cellpadding="2">

            <thead>
            <tr class="row-line">
                <th width="5%" style="text-align: left; font-size: 10px">SL</th>
                <th width="20%" style="text-align: left; font-size: 10px">Item SKU</th>
                <th width="50%" style="text-align: left; font-size: 10px">Item Name</th>
                <th width="15%" style="text-align: right; font-size: 10px">Quantity</th>
                <th width="10%" style="text-align: center; font-size: 10px">Unit</th>
            </tr>
            </thead>
            <tbody>
            @foreach($requisition->items as $i=>$item)
                <tr>
                    <td width="5%" style="border-bottom-width:1px; font-size:10pt; text-align: left">{!! $i+1 !!}</td>
                    <td width="20%" style="border-bottom-width:1px; font-size:10pt; text-align: left">{!! $item->item->sku !!}</td>
                    <td width="50%" style="border-bottom-width:1px; font-size:10pt; text-align: left">{!! $item->item->name !!}</td>
                    <td width="15%" style="border-bottom-width:1px; font-size:10pt; text-align: right">{!! number_format($item->quantity,2) !!}</td>
                    <td width="10%" style="border-bottom-width:1px; font-size:10pt; text-align: center">{!! $item->item->unit_name !!}</td>
                </tr>

            @endforeach
            </tbody>
{{--            <tfoot>--}}
{{--            <tr style="line-height: 200%">--}}
{{--                <td colspan="5" style="border-bottom-width:0.2px; font-size:10pt;"></td>--}}
{{--            </tr>--}}
{{--            </tfoot>--}}

        </table>

        <div class="blank-space"></div>
        <div class="blank-space"></div>
        <div class="blank-space"></div>
        <div class="blank-space"></div>
        <div class="blank-space"></div>
        <div class="blank-space"></div>

        <table class="table order-bank" width="90%" cellpadding="2">

            <tbody>
            <tr>
                <td style="border-top-width: 0.5px">Prepared By</td>
                <td></td>
                <td style="border-top-width: 0.5px; text-align: right">Approved By</td>
            </tr>
            <tr>
                <td>({!! $requisition->user->full_name !!})</td>
                <td></td>
                <td style="text-align: right">({!! $requisition->approver->full_name !!})</td>
            </tr>
            </tbody>

        </table>
    @endif




@endsection
