
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


                <td width="45%"><p style="font-size:10pt; text-transform: uppercase"><strong>TO:</strong><br/>
                        {!! ($invoice->customer->name) !!}<br/>
                        <strong>ADD:</strong>{!! $invoice->customer->address !!}<br/>
                        {!! $invoice->customer->phone_number !!}</p>
                </td>
                <td width="10%"></td>

                <td width="20%"><p style="font-size:10pt; text-transform: uppercase"><strong>DATE:</strong><br/>
                        <strong>INVOICE NO:</strong><br/>
                        <strong>CONTRACT NO:</strong><br/>
                        <strong>DATE:</strong></p>
                </td>

                <td width="25%"><p style="font-size:10pt; text-transform: uppercase">{!! Carbon\Carbon::parse($invoice->invoice_date)->format('F d, Y') !!}<br/>
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

            <tr style="line-height: 150%">
                <td width="30%" style="font-size:10pt;font-weight: bold"><p style="text-decoration: none">SHIPMENT FROM:</p></td>
                <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">{!! $invoice->loading_port !!}</p> </td>
            </tr>

            <tr style="line-height: 150%">
                <td width="30%" style="font-size:10pt;font-weight: bold"><p style="text-decoration: none">PORT OF DISCHARGE OF DESTINATION:</p></td>
                <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">{!! $invoice->destination_port !!}</p> </td>
            </tr>

            <tr style="line-height: 150%">
                <td width="30%" style="font-size:10pt;font-weight: bold"><p style="text-decoration: none">VESSEL NO:</p></td>
                <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">{!! $invoice->challan->vessel_no !!}</p> </td>
            </tr>



            <tr style="line-height: 150%">
                <td style="font-size:10pt;font-weight: bold"><p style="text-decoration: none">MADE IN:</p></td>
                <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">BANGLADESH</p> </td>
            </tr>


            <tr style="line-height: 150%">
                <td width="30%" style="font-size:10pt;font-weight: bold"><p style="text-decoration: none">COUNTRY OF ORIGIN:</p></td>
                <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">BANGLADESH</p> </td>
            </tr>

            <tr style="line-height: 150%">
                <td width="30%" style="font-size:10pt;font-weight: bold"><p style="text-decoration: none">1. DESCRIPTION OF GOODS:</p></td>
                {{--            <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">POLYESTER STAPLE FIBER. (PSF)--}}
                {{--                    H.S. CODE NO:5503.20 FINENESS :{!! $items[0]->danier !!} D, LENGTH: {!! $items[0]->length !!}MM, TYPE: COTTON TYPE.</p> </td>--}}

                <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">POLYESTER STAPLE FIBER. (PSF)
                        H.S. CODE NO:5503.20 FINENESS :{!! $invoice->items->first()->item->model->name !!}, LENGTH: {!! $invoice->items->first()->item->size->size !!}</p> </td>
            </tr>

            <tr style="line-height: 150%">
                <td colspan="2" style="font-size:10pt;font-weight: bold"><p style="text-decoration: none">2. PARTICULARS OF PACKING:</p></td>
            </tr>

            </tbody>
        </table>

        @php
            $serial = 1;
        @endphp

        <div class="blank-space"></div>
        @foreach($containers as $p=>$container)
            <table class="table order-bank" width="90%" cellpadding="2">

                <thead>
                <tr class="row-line">
                    <th colspan="5" style="text-align: left; font-size: 10px">CONTAINER {!! $loop->index + 1 !!} : {!! $container->container !!}</th>
                </tr>
                <tr class="row-line">
                    <th width="10%" style="text-align: left; font-size: 10px">SL No</th>
                    <th width="20%" style="text-align: left; font-size: 10px">BALE NO</th>
                    <th width="10%" style="text-align: left; font-size: 10px">LOT NO</th>
                    <th width="30%" style="text-align: center; font-size: 10px">SPECIFICATION</th>
                    <th width="15%" style="text-align: right; font-size: 10px">GROSS WEIGHTT</th>
                    <th width="15%" style="text-align: right; font-size: 10px">NET WEIGHTT</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $i=>$item)
                    @if($container->container === $item->container)
                        <tr>
                            <td width="10%" style="border-bottom-width:1px; font-size:10pt; text-align: left">{!! $serial++ !!}</td>
                            <td width="20%" style="border-bottom-width:1px; font-size:10pt; text-align: left">{!! $item->bale_no !!}</td>
                            <td width="10%" style="border-bottom-width:1px; font-size:10pt; text-align: left">{!! $item->lot_no !!}</td>
                            <td width="30%" style="border-bottom-width:1px; font-size:10pt; text-align: center">Polyester Staple Fiber</td>
                            <td width="15%" style="border-bottom-width:1px; font-size:10pt; text-align: right">{!! number_format($item->gross_weight,2) !!}</td>
                            <td width="15%" style="border-bottom-width:1px; font-size:10pt; text-align: right">{!!number_format($item->quantity_in,2) !!}</td>
                        </tr>

                    @endif
                @endforeach

                </tbody>

                @php
                    $serial = 1;
                @endphp

                <tfoot>
                <tr style="line-height: 200%">
                    <td style="border-bottom-width:0.2px; font-size:10pt;"></td>
                    <td style="border-bottom-width:0.2px; font-size:10pt;" colspan="2" >Total</td>
                    <td style="border-bottom-width:0.2px; font-size:10pt;"></td>
                    <td style="border-bottom-width:0.2px; font-size:10pt; text-align: right">{!!number_format($items->where('container',$container->container)->sum('gross_weight'),2) !!}</td>
                    <td style="border-bottom-width:0.2px; font-size:10pt; text-align: right">{!! number_format($items->where('container',$container->container)->sum('quantity_in'),2) !!}</td>
                </tr>
                </tfoot>

            </table>
            {{--*/ $con_sl +=  1 /*--}}

            <div class="blank-space"></div>
            <div class="blank-space"></div>
        @endforeach



        <table class="table order-bank" width="90%" cellpadding="0">

            <tbody>
            {{--<tr style="line-height: 150%">--}}
            {{--<td width="30%" style="font-size:10pt; font-weight: bold"><p style="text-decoration: none">TOTAL AMOUNT USD:</p></td>--}}
            {{--<td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">${!!number_format($invoice->fc_amt,2) !!}</p> </td>--}}
            {{--</tr>--}}
            <div class="blank-space"></div>
            {{--<tr style="line-height: 150%">--}}
            {{--<td colspan="2" style="font-size:10pt;"><p style="text-transform: uppercase">WE CERTIFYING GOODS ARE IN BANGLADESHI ORIGIN. WE CERTIFYING THAT WE HAVE SHIPPED THE GOODS IN GOOD CONDITION, DEFECT FREE AND ACCORDANCE WITH SPECIFICATION, QUALITY AND PRICE OF SALES CONTRACT</p></td>--}}
            {{--</tr>--}}
            {{--<div class="blank-space"></div>--}}

            <tr style="line-height: 150%">
                <td width="30%" style="font-size:10pt; font-weight: bold"><p style="text-decoration: none">3. PACKING:</p></td>
                <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">{!! $invoice->challan->packing !!}</p> </td>
            </tr>

            <tr style="line-height: 150%">
                <td width="30%" style="font-size:10pt; font-weight: bold"><p style="text-decoration: none">4. CONTRACT NUMBER:</p></td>
                <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">{!! $invoice->contract->export_contract_no !!} DATED: {!! Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') !!}</p></td>
            </tr>

            <tr style="line-height: 150%">
                <td width="30%" style="font-size:10pt; font-weight: bold"><p style="text-decoration: none">5. APPLICANT TIN/BIN NO:</p></td>
                <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">{!! $invoice->applicants_bin !!}</p> </td>
            </tr>

            <tr style="line-height: 150%">
                <td width="30%" style="font-size:10pt; font-weight: bold"><p style="text-decoration: none">6. SHIPPING MARKS</p></td>
                <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">{!! $invoice->challan->shipping_mark !!}</p> </td>
            </tr>

            </tbody>
        </table>

    @endisset
@endsection
