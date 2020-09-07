
@extends('layouts.pdf')
@section('content')

    <table border="0" cellpadding="0">

        <tr>
            <td width="33%"><img src="{!! public_path('/assets/images/mumanu_b.jpg') !!}" style="width:250px;height:60px;"></td>
            <td width="2%"></td>
            <td width="65%"><span style="font-family:times;font-weight:bold; padding-left: 100px; line-height: 130%; height: 300%; font-size:30pt;color:black;">Polyester Industries Ltd.</span></td>

        </tr>
        <tr>
            <td colspan="3"><span style="line-height: 60%; text-align:center; font-family:times;font-weight:bold;font-size:20pt;color:black;">Panishail, Singair, Manikganj, Bangladesh</span></td>
        </tr>
        <hr style="height: 2px">


    </table>

    <div class="row"></div>
    <div class="row"></div>

    <div>
        <table style="width:100%">
            <tr>
                <td style="width:30%"></td>
                <td style="width:60%">
                    <table style="width:100%" class="order-bank">
                        <thead>
                        <tr>
                            <td style="width:70%; border-bottom-width:1px;" colspan="2"><span style="text-align:center; border: #000000; font-family:times;font-weight:bold;font-size:15pt;color:#000000; ">PACKING LIST</span></td>
                        </tr>
                        </thead>
                    </table>
                </td>
                <td style="width:10%"></td>
            </tr>
        </table>
    </div>


    <div class="blank-space"></div>

    @isset($invoice)

        <table class="table order-bank" width="90%" cellpadding="1">

            <tbody>
            <tr style="line-height: 150%">


                <td width="55%"><p style="font-size:10pt; text-transform: uppercase"><strong>TO:</strong><br/>
                        {!! ($invoice->customer->name) !!}<br/>
                        <strong>ADD:</strong>{!! $invoice->customer->address !!}<br/>
                        {!! $invoice->customer->phone_number !!}</p>
                </td>
                <td width="5%"></td>

                <td width="20%"><p style="font-size:10pt; text-transform: uppercase"><strong>DATE:</strong><br/>
                        <strong>INVOICE NO:</strong><br/>
                        <strong>CONTRACT NO:</strong><br/>
                        <strong>DATE:</strong></p>
                </td>

                <td width="20%"><p style="font-size:10pt; text-transform: uppercase">{!! Carbon\Carbon::parse($invoice->invoice_date)->format('F d, Y') !!}<br/>
                        {!! $invoice->invoice_no !!}<br/>
                        {!! $invoice->contract->export_contract_no !!}<br/>
                        {!! Carbon\Carbon::parse($invoice->contract->contract_date)->format('M d, Y') !!}</p>
                </td>

            </tr>
            </tbody>
        </table>

        <div class="blank-space"></div>
        <table class="table" width="90%" cellpadding="0">
            <tbody>

            <tr style="line-height: 100%">
                <td colspan="2" width="100%" style="font-size:10pt;"><p style="text-transform: uppercase; text-decoration: none">SHIPMENT FROM {!! $invoice->loading_port !!} TO {!! $invoice->destination_port !!}<br>VESSEL NO: {!! $invoice->challan->vessel_no !!}</p></td>
            </tr>

            <tr style="line-height: 150%">
                <td width="30%" style="font-size:10pt; font-weight: bold"><p style="text-decoration: none">SHIPPING MARKS</p></td>
                <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">{!! $invoice->challan->shipping_mark !!}</p> </td>
            </tr>

{{--            <tr style="line-height: 150%">--}}
{{--                <td width="30%" style="font-size:10pt;font-weight: bold"><p style="text-decoration: none">1. DESCRIPTION OF GOODS:</p></td>--}}
{{--                <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">POLYESTER STAPLE FIBER (PSF).--}}
{{--                        H.S. CODE NO:5503.20 FINENESS :{!! $invoice->items->first()->item->model->name !!}, LENGTH: {!! $invoice->items->first()->item->size->size !!}, TYPE: COTTON TYPE, COLOR : {!! $invoice->items->first()->item->color->color !!}<br><strong>MADE IN BANGLADESH</strong></p> </td>--}}
{{--            </tr>--}}

{{--            <tr style="line-height: 150%">--}}
{{--                <td colspan="2" style="font-size:10pt;font-weight: bold"><p style="text-decoration: none">2. PARTICULARS OF PACKING:</p></td>--}}
{{--            </tr>--}}

            </tbody>
        </table>

        <div class="blank-space"></div>



        <table class="table order-bank" width="90%" cellpadding="2">

            <thead>
            <tr class="row-line">
                <th width="30%" style="text-align: left; font-size: 10px">DESCRIPTION OF GOODS</th>
                <th width="20%" style="text-align: left; font-size: 10px">SPECIFICATION</th>
                <th width="10%" style="text-align: center; font-size: 10px">PKG</th>
                <th width="20%" style="text-align: right; font-size: 10px">NET WEIGHT</th>
                <th width="20%" style="text-align: right; font-size: 10px">GROSS WEIGHT</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td width="30%" style="border-bottom-width:1px; font-size:8pt; text-align: center">POLYESTER STAPLE FIBER (PSF).</td>
                <td width="20%" style="border-bottom-width:1px; font-size:8pt; text-align: center">FINENESS: {!! $invoice->items->first()->item->model->name !!}<br>LENGTH: {!! $invoice->items->first()->item->size->size !!}<br>{!! $invoice->items->first()->item->color->color !!}</td>
                <td width="10%" style="border-bottom-width:1px; font-size:8pt; text-align: center">{!! $products->sum('bale_count') !!}BALES</td>
                <td width="20%" style="border-bottom-width:1px; font-size:8pt; text-align: right">{!!number_format($products->sum('gross_weight'),2) !!}KGS</td>
                <td width="20%" style="border-bottom-width:1px; font-size:8pt; text-align: right">{!! number_format($products->sum('net_weight'),2) !!}KGS</td>
            </tr>
            </tbody>
            <tfoot>
            <tr style="line-height: 200%">
                <td style="border-bottom-width:0.2px; font-size:10pt;"></td>
                <td style="border-bottom-width:0.2px; font-size:10pt;" >Total</td>
                <td style="border-bottom-width:0.2px; font-size:8pt; text-align: center">{!! $products->sum('bale_count') !!}BALES</td>
                <td style="border-bottom-width:0.2px; font-size:8pt; text-align: right">{!!number_format($products->sum('gross_weight'),2) !!}KGS</td>
                <td style="border-bottom-width:0.2px; font-size:8pt; text-align: right">{!! number_format($products->sum('net_weight'),2) !!}KGS</td>
            </tr>
            </tfoot>

        </table>



        <table class="table order-bank" width="90%" cellpadding="0">

            <tbody>
            <div class="blank-space"></div>
            <tr style="line-height: 150%">
                <td width="30%" style="font-size:10pt; font-weight: bold"><p style="text-decoration: none">PACKING:</p></td>
                <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">{!! $invoice->challan->packing !!}</p> </td>
            </tr>

            <tr style="line-height: 150%">
                <td width="30%" style="font-size:10pt; font-weight: bold"><p style="text-decoration: none">TOTAL:</p></td>
                <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">{!! $products->sum('bale_count') !!} BALES</p></td>
            </tr>
            <tr style="line-height: 150%">
                <td width="30%" style="font-size:10pt; font-weight: bold"><p style="text-decoration: none">G W</p></td>
                <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">{!! number_format($products->sum('gross_weight'),2) !!}KGS</p></td>
            </tr>

            <tr style="line-height: 150%">
                <td width="30%" style="font-size:10pt; font-weight: bold"><p style="text-decoration: none">MEASUREMENT</p></td>
                <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">{!!number_format($products->sum('cbm'),2) !!}CBM</p></td>
            </tr>




            </tbody>
        </table>

    @endisset
@endsection
