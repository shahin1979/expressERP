@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Sales Register</li>
        </ol>
    </nav>



    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-9" >
                <br/>
                <div><h3>Sales Register Report</h3></div>
                <div style="background-color: #ff0000;height: 2px">&nbsp;</div>
                <br/>


                <div class="div">
                    <br/>
                    {!! Form::open(['url'=>'inventory/report/rptSalesRegisterIndex', 'method' => 'GET']) !!}

                    <table width="90%" class="table table-responsive table-hover" >

                        <tr>
                            <td width="5%"><label for="date_from" class="control-label" >From</label></td>
                            <td width="10%">{!! Form::text('date_from', Carbon\Carbon::now()->format('d-m-Y'), array('id' => 'date_from', 'class' => 'form-control','required','readonly')) !!}</td>
                            <td width="5%"><label for="date_to" class="control-label" >To</label></td>
                            <td width="10%">{!! Form::text('date_to', Carbon\Carbon::now()->format('d-m-Y'), array('id' => 'date_to', 'class' => 'form-control','required','readonly')) !!}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><button name="action" type="submit" value="preview" class="btn btn-info btn-reject pull-left">Preview</button></td>
                            <td colspan="2"><button name="action" type="submit" value="print" class="btn btn-primary btn-approve pull-right">Print</button></td>
                        </tr>

                    </table>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>


    @if(!empty($invoice))

        @php
            $grand_total = 0;
        @endphp

        <div class="card-body">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr class="table-success">
                    <th>Invoice date</th>
                    <th>Invoice No</th>
                    <th>Product</th>
                    <th style="text-align: right">Quantity</th>
                    <th style="text-align: right">Unit Price</th>
                    <th style="text-align: right">Sub Total</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoice as $i=>$row)
                    <tr style="background-color: {!! $i % 2 == 0 ? '#ffffff': '#afffff' !!}">
                        <td>{!! $row->invoice_date !!}</td>
                        <td>
                            {!! $row->invoice_no !!}<br>
                            {!! $row->customer->name !!}
                        </td>
                        <td>
                            @foreach($row->items as $items)
                                {!! $items->item->name !!}<br/>
                            @endforeach
                        </td>

                        <td style="text-align: right">
                            @foreach($row->items as $items)
                                {!! number_format($items->quantity,2) !!}<br/>
                            @endforeach
                        </td>

                        <td style="text-align: right">
                            @foreach($row->items as $items)
                                {!! number_format($items->unit_price,2) !!}<br/>
                            @endforeach
                        </td>

                        <td style="text-align: right">
                            @foreach($row->items as $items)
                              {!! number_format(($items->unit_price * $items->quantity),2) !!}<br/>
                                @php($grand_total = $grand_total +  ($items->unit_price * $items->quantity))
                            @endforeach
                        </td>

{{--                        <td style="text-align: right">{!!number_format($row->invoice_amt,2) !!}</td>--}}
                        <td style="text-align: right">{!! $row->status !!}</td>
                    </tr>


                @endforeach
                </tbody>
                <tfoot>
                <tr style="background-color: #3A92AF">
                    <td colspan="5">Period Total</td>
                    <td style="text-align: right">{!! number_format($grand_total,2)  !!}</td>
                    <td></td>
                </tr>
                </tfoot>
            </table>
        </div>
    @endif

@endsection

@push('scripts')

    <script>

        $(document).ready(function(){

            $( "#date_from" ).datetimepicker({
                format:'d-m-Y',
                timepicker: false,
                closeOnDateSelect: true,
                scrollInput : false,
                inline:false
            });

            $( "#date_to" ).datetimepicker({
                format:'d-m-Y',
                timepicker: false,
                closeOnDateSelect: true,
                scrollInput : false,
                inline:false
            });
        });

    </script>

@endpush
