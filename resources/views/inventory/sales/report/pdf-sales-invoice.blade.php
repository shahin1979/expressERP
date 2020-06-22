@extends('layouts.pdf')
@section('content')

    <table class="table" width="95%" cellpadding="2">

        <tr>
            <td width="10%"><img src="{!! public_path('company/'.$users_company->company_id.'_logo.jpg') !!}" style="width:40px;height:40px;"></td>
            <td width="50%" style="text-align: left"><span style="font-weight:bold; padding-right: 100px; line-height: 130%; height: 300%; font-size:15pt;color:black;">{!! $users_company->company->name !!}</span><br/>
                <span style="font-weight:bold; padding-right: 100px; line-height: 100%; height: 200%; font-size:12pt;color:black;">{!! $users_company->company->address !!}</span>
            </td>

            <td width="40%">
                <table>
                    <tbody>
                        <tr class="row-border">
                            <td colspan="2" style="text-align: center" ><span style="font-weight:bold; line-height: 130%; height: 200%; font-size:10pt;color:black;">INVOICE</span></td>

                        </tr>
                    <tr class="row-border">
                        <td style="border-bottom-width:1px; font-size:10pt; text-align: center">Invoice No</td>
                        <td style="border-bottom-width:1px; font-size:10pt; text-align: center">{!! $invoice->invoice_no !!}</td>
                    </tr>
                        <tr class="row-border">
                            <td style="border-bottom-width:1px; font-size:10pt; text-align: center">Invoice Date</td>
                            <td style="border-bottom-width:1px; font-size:10pt; text-align: center">{!! \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y') !!}</td>
                        </tr>
                    </tbody>

                </table>
            </td>
{{--            <td width="35%" style="text-align: right"><span style="font-weight:bold; padding-right: 100px; line-height: 130%; height: 300%; font-size:12pt;color:black;">{!! $users_company->company->address !!}</span></td>--}}

        </tr>
    </table>

    <div class="blank-space"></div>
    <div class="blank-space"></div>




    {{--    <div class="blank-space"></div>--}}

    @if(!empty($invoice))
        <table>
            <tr>
                <td width="50%" style="font-size:10pt"><strong>Billed To:</strong><br>
                    Name        : {!! $invoice->customer->name !!}<br>
                    {{--                            {!! $invoice->relationship->street !!}<br>--}}
                    Address     : {!! $invoice->customer->address !!}<br>
                    Phone No    : {!! $invoice->customer->phone_number !!}
                </td>
            </tr>
        </table>
        <div class="blank-space"></div>

        <table class="table order-bank" width="95%" cellpadding="2">

            <thead>
            <tr class="row-line">
                <th width="5%" style="text-align: left; font-size: 10px">SL</th>
                <th width="40%" style="text-align: left; font-size: 10px">Item Name</th>
                <th width="20%" style="text-align: right; font-size: 10px">Quantity</th>
                <th width="15%" style="text-align: right; font-size: 10px">Unit Price</th>
                <th width="20%" style="text-align: right; font-size: 10px">Sub Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoice->items as $i=>$item)
                <tr>
                    <td width="5%" style="border-bottom-width:1px; font-size:10pt; text-align: left">{!! $i+1 !!}</td>
                    <td width="40%" style="border-bottom-width:1px; font-size:10pt; text-align: left">{!! $item->item->name !!}</td>
                    <td width="20%" style="border-bottom-width:1px; font-size:10pt; text-align: right">{!! number_format($item->quantity,2) !!} {!! $item->item->unit_name !!}</td>
                    <td width="15%" style="border-bottom-width:1px; font-size:10pt; text-align: right">{!! number_format($item->unit_price,2) !!}</td>
                    <td width="20%" style="border-bottom-width:1px; font-size:10pt; text-align: right">{!! number_format($item->item_total,2) !!}</td>
                </tr>

            @endforeach
            </tbody>
            <tfoot>
                <tr style="line-height: 200%">
                    <td colspan="2" style="font-size:10pt;">Total</td>
                    <td style="font-size:10pt;text-align: right">{!! number_format($invoice->items->sum('quantity'),2) !!} {!! $item->item->unit_name !!}</td>
                    <td colspan="2"></td>
                </tr>

                <tr>
                    <td rowspan="4" colspan="3" style="font-size:8pt;text-align: left;">{!! convert_number_to_words_bd_format($invoice->due_amt) !!}</td>
                    <td style="font-size:8pt;">Sub Total</td>
                    <td style="font-size:8pt;text-align: right">{!! number_format($invoice->items->sum('item_total'),2) !!}</td>
                </tr>
                <tr>
                    <td style="font-size:8pt;">Sales Tax</td>
                    <td style="font-size:8pt;text-align: right">{!! number_format($invoice->items->sum('tax_total'),2) !!}</td>
                </tr>

                <tr>
                    <td style="font-size:8pt;">Discount Amount</td>
                    <td style="font-size:8pt;text-align: right">{!! number_format($invoice->discount_amt,2) !!}</td>
                </tr>
                <tr>
                    <td style="font-size:8pt;">Paid Amount</td>
                    <td style="font-size:8pt;text-align: right">{!! number_format($invoice->paid_amt,2) !!}</td>
                </tr>
                <tr>
                    <td colspan="4"  style="font-size:8pt;font-weight: bold">Due Amount</td>
                    <td style="font-size:8pt;text-align: right; font-weight: bold">{!! number_format($invoice->due_amt,2) !!}</td>
                </tr>

            </tfoot>

        </table>




        <div class="blank-space"></div>

{{--        @if(!empty($ledgers))--}}

        <table class="table order-bank" width="95%" cellpadding="2">

            <thead>
            <tr class="row-line">
                <th colspan="6" style="text-align: center; font-size: 10px">ACCOUNTS</th>

            </tr>

            <tr class="row-line">
                <th width="5%" style="text-align: left; font-size: 8px">SL</th>
                <th width="12%" style="text-align: left; font-size: 8px">Voucher No</th>
                <th width="10%" style="text-align: left; font-size: 8px">Date</th>
                <th width="34%" style="text-align: left; font-size: 8px">Contra</th>
                <th width="12%" style="text-align: right; font-size: 8px">Debit</th>
                <th width="12%" style="text-align: right; font-size: 8px">Credit</th>
                <th width="15%" style="text-align: right; font-size: 8px">Balance</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td width="27%" style="border-bottom-width:1px; font-size:8pt; text-align: left" colspan="3"></td>
                <td width="34%" style="border-bottom-width:1px; font-size:8pt; text-align: left">Opening Balance</td>
                <td width="12%" style="border-bottom-width:1px; font-size:8pt; text-align: right"></td>
                <td width="12%" style="border-bottom-width:1px; font-size:8pt; text-align: right"></td>
                <td width="15%" style="border-bottom-width:1px; font-size:8pt; text-align: right">{!! number_format($opening,2) !!}</td>
            </tr>
            @foreach($ledgers['report'] as $i=>$ledger)
                <tr>
                    <td width="5%" style="border-bottom-width:1px; font-size:8pt; text-align: left">{!! $i+1 !!}</td>
                    <td width="12%" style="border-bottom-width:1px; font-size:8pt; text-align: left">{!! $ledger['voucher_no'] !!}</td>
                    <td width="10%" style="border-bottom-width:1px; font-size:8pt; text-align: left">{!! $ledger['tr_date'] !!}</td>
                    <td width="34%" style="border-bottom-width:1px; font-size:8pt; text-align: left">
                        @foreach($ledgers['contra'] as $line)
                            @if($line['voucher_no'] == $ledger['voucher_no'])
                                {!! $line['acc_name'] !!}
                            @endif
                        @endforeach
                    </td>
                    <td width="12%" style="border-bottom-width:1px; font-size:8pt; text-align: right">{!! number_format($ledger['dr_amt'],2) !!}</td>
                    <td width="12%" style="border-bottom-width:1px; font-size:8pt; text-align: right">{!! number_format($ledger['cr_amt'],2) !!}</td>
                    <td width="15%" style="border-bottom-width:1px; font-size:8pt; text-align: right">{!! number_format($ledger['balance'],2) !!}</td>
                </tr>

            @endforeach
            </tbody>
{{--            <tfoot>--}}
{{--            <tr style="line-height: 200%">--}}
{{--                <td colspan="2" style="font-size:10pt;">Total</td>--}}
{{--                <td style="font-size:10pt;text-align: right">{!! number_format($invoice->items->sum('quantity'),2) !!} {!! $item->item->unit_name !!}</td>--}}
{{--                <td colspan="2"></td>--}}
{{--            </tr>--}}
{{--            </tfoot>--}}

        </table>

{{--        @endif--}}

{{--        <hr style="height: 1px">--}}

        <div class="blank-space"></div>
        <div class="blank-space"></div>

        <div class="blank-space"></div>
        <div class="blank-space"></div>
        <div class="blank-space"></div>
        <div class="blank-space"></div>


        <table class="table order-bank" width="95%" cellpadding="2">

            <tbody>
            <tr>
                <td style="border-top-width: 0.5px; font-size:8pt; text-align: left">Customer Signature</td>
                <td></td>
                <td></td>
                <td style="border-top-width: 0.5px; font-size:8pt; text-align: right">Sold By</td>
            </tr>
            </tbody>

        </table>
    @endif




@endsection
