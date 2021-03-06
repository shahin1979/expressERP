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
                        <td style="width:70%; border-bottom-width:1px;" colspan="2"><span style="text-align:center; border: #000000; font-family:times;font-weight:bold;font-size:15pt;color:#000000; ">COMMERCIAL INVOICE</span></td>
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

    <table class="table order-bank" width="90%" cellpadding="0">

        <tbody>
        <tr style="line-height: 150%">
            <td width="30%" style="font-size:10pt; color:black; font-weight: bold"><p style="text-decoration: none">INVOICE NO:</p></td>
            <td width="40%" align="left" style="font-size:10pt;">{!! $invoice->invoice_no !!}</td>
            <td align="right" width="10%" style="font-size:10pt;">DATE :</td>
            <td align="right" width="20%"><p style="font-size:10pt; text-transform: uppercase">{!! Carbon\Carbon::parse($invoice->invoice_date)->format('F d, Y') !!}</p></td>
        </tr>
        </tbody>
    </table>

    <div class="blank-space"></div>
    <table class="table" width="90%" cellpadding="0">
        <tbody>
        <tr style="line-height: 150%">
            <td width="30%" style="font-size:10pt;font-weight: bold"><p style="text-decoration: none">FOR ACCOUNT OF:</p></td>
            <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">{!! ($invoice->customer->name) !!}</p></td>
        </tr>
        <tr style="line-height: 150%">
            <td width="30%" style="font-size: 10pt; font-weight: bold"><p style="text-decoration: none">ADD:</p></td>
            <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">{!! $invoice->customer->address !!}</p></td>
        </tr>

        <tr style="line-height: 150%">
            <td width="30%" style="font-size: 10pt; font-weight: bold"><p style="text-decoration: none"></p></td>
            <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">{!! $invoice->customer->phone_number !!}</p></td>
        </tr>

        <tr style="line-height: 150%">
            <td width="30%" style="font-size:10pt;font-weight: bold"><p style="text-decoration: none">SHIPMENT FROM:</p></td>
            <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">{!! $invoice->loading_port !!}</p> </td>
        </tr>

        <tr style="line-height: 150%">
            <td width="30%" style="font-size:10pt;font-weight: bold"><p style="text-decoration: none">PORT OF DISCHARGE OF DESTINATION:</p></td>
            <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase"><br>{!! $invoice->destination_port !!}</p> </td>
        </tr>

        <tr style="line-height: 150%">
            <td width="30%" style="font-size:10pt;font-weight: bold"><p style="text-decoration: none">DESCRIPTION OF GOODS:</p></td>
            <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">POLYESTER STAPLE FIBER (PSF).
                    H.S. CODE NO:5503.20 FINENESS :{!! $invoice->items->first()->item->model->name !!}, LENGTH: {!! $invoice->items->first()->item->size->size !!}, TYPE: COTTON TYPE,{!! isset($invoice->color) ? $invoice->color : null !!}</p></td>
        </tr>

        <tr style="line-height: 150%">
            <td style="font-size:10pt;font-weight: bold"><p style="text-decoration: none"></p></td>
            <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">MADE IN BANGLADESH</p> </td>
        </tr>

        <tr style="line-height: 150%">
            <td width="30%" style="font-size:10pt;font-weight: bold"><p style="text-decoration: none">PRICE:</p></td>
            <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">USD {!! $invoice->items->first()->unit_price !!} PER KILOGRAM</p> </td>
        </tr>


        <tr style="line-height: 150%">
            <td width="30%" style="font-size:10pt;font-weight: bold"><p style="text-decoration: none">DELIVERY TERM:</p></td>
            <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">{!! $invoice->delivery_terms !!}</p> </td>
        </tr>

        <tr style="line-height: 150%">
            <td width="30%" style="font-size:10pt;font-weight: bold"><p style="text-decoration: none">COUNTRY OF ORIGIN:</p></td>
            <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">BANGLADESH</p> </td>
        </tr>

        </tbody>
    </table>

    <div class="blank-space"></div>

    <table style="width:100%">
        <tr>
            <td style="width:45%">
                <table>

                    <table class="table order-bank" width="100%" cellpadding="2">

                        <thead>
                        <tr class="row-line">
                            <th width="15%" style="text-align: left; font-size: 8px">SL No</th>
                            <th width="20%" style="text-align: center; font-size: 8px">NO. OF BALES</th>
                            <th width="35%" style="text-align: right; font-size: 8px">GROSS WEIGHT(KG)</th>
                            <th width="30%" style="text-align: right; font-size: 8px">NET WEIGHT(KG)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $i=>$item)
                            @if($i<=9)
                                <tr>
                                    <td width="15%" style="border-bottom-width:1px; font-size:10pt; text-align: left">{!! $i+1 !!}</td>
                                    <td width="20%" style="border-bottom-width:1px; font-size:10pt; text-align: center">{!! $item->bale_count !!}</td>
                                    <td width="35%" style="border-bottom-width:1px; font-size:10pt; text-align: right">{!! number_format($item->grs_weitht,2) !!}</td>
                                    <td width="30%" style="border-bottom-width:1px; font-size:10pt; text-align: right">{!!number_format($item->net_weight,2) !!}</td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>

                </table>
            </td>
            <td style="width:5%"></td>
            <td style="width:50%">
                <table>

                    <table class="table order-bank" width="100%" cellpadding="2">

                        <thead>
                        <tr class="row-line">
                            <th width="15%" style="text-align: left; font-size: 8px">SL No</th>
                            <th width="20%" style="text-align: center; font-size: 8px">NO. OF BALES</th>
                            <th width="35%" style="text-align: right; font-size: 8px">GROSS WEIGHT(KG)</th>
                            <th width="30%" style="text-align: right; font-size: 8px">NET WEIGHT(KG)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $i=>$item)

                            @if($i>9)
                                <tr>
                                    <td width="15%" style="border-bottom-width:1px; font-size:10pt; text-align: left">{!! $i+1 !!}</td>
                                    <td width="20%" style="border-bottom-width:1px; font-size:10pt; text-align: center">{!! $item->bale_count !!}</td>
                                    <td width="35%" style="border-bottom-width:1px; font-size:10pt; text-align: right">{!! number_format($item->grs_weight,2) !!}</td>
                                    <td width="30%" style="border-bottom-width:1px; font-size:10pt; text-align: right">{!!number_format($item->net_weight,2) !!}</td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr style="line-height: 200%">
                            <td style="border-bottom-width:0.2px; font-size:10pt;" >Total</td>
                            <td style="border-bottom-width:0.2px; font-size:10pt; text-align: center">{!! $products->sum('bale_count') !!}</td>
                            <td style="border-bottom-width:0.2px; font-size:10pt; text-align: right">{!!number_format( $products->sum('grs_weight'),2) !!}</td>
                            <td style="border-bottom-width:0.2px; font-size:10pt; text-align: right">{!! number_format($products->sum('net_weight'),2) !!}</td>
                        </tr>
                        </tfoot>

                    </table>

                </table>
            </td>
        </tr>
    </table>






    <br/>


    <table class="table order-bank" width="90%" cellpadding="0">

        <tbody>
        <tr style="line-height: 150%">
            <td width="30%" style="font-size:10pt; font-weight: bold"><p style="text-decoration: none">TOTAL AMOUNT USD:</p></td>
            <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">${!!number_format($invoice->fc_amt,2) !!}</p> </td>
        </tr>
        <div class="blank-space"></div>
        <tr style="line-height: 150%">
            <td colspan="2" style="font-size:10pt;"><p style="text-transform: uppercase">WE CERTIFYING GOODS ARE IN BANGLADESHI ORIGIN. WE CERTIFYING THAT WE HAVE SHIPPED THE GOODS IN GOOD CONDITION, DEFECT FREE AND ACCORDANCE WITH SPECIFICATION, QUALITY AND PRICE OF SALES CONTRACT</p></td>
        </tr>
        <div class="blank-space"></div>

        <tr style="line-height: 150%">
            <td width="30%" style="font-size:10pt; font-weight: bold"><p style="text-decoration: none">APPLICANT TIN/BIN NO:</p></td>
            <td width="60%" style="font-size:10pt;"><p style="text-transform: uppercase">{!! $invoice->applicants_bin !!}</p> </td>
        </tr>

        </tbody>
    </table>

@endisset
@endsection
