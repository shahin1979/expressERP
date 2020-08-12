@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Export Invoice</li>
        </ol>
    </nav>

    <div class="container-fluid">

        @empty($contract)
        <div class="container spark-screen">
            <div class="row">
                <div class="col-md-6 col-md-offset-2">

                    <div class="search" id="search">
                        <br/>
                        {!! Form::open(['url'=>'export/createExportInvoiceIndex', 'method' => 'GET']) !!}

                        <table width="50%" class="table table-responsive table-hover" >

                            <tr>
                                <td width="5%"><label for="type" class="control-label">Contract No</label></td>
                                <td width="15%">{!! Form::select('contract_id',$contracts, null , array('id' => 'contract_id', 'class' => 'form-control')) !!}</td>
                                <td width="10%"><button name="action" type="submit" class="btn btn-primary pull-right">Submit</button></td>
                            </tr>

                        </table>

                        {!! Form::close() !!}

                    </div>
                </div>

                <div style="width: 5px"></div>
            </div>
        </div>
        @endempty

        @isset($contract)

            {!! Form::open(['url'=>'export/invoice/store','method'=>'post']) !!}
                {{ csrf_field() }}
                <div>
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td><label for="invoice_date" class="control-label">Invoice Date</label></td>
                            <td>{!! Form::text('invoice_date', \Carbon\Carbon::now()->format('d-m-Y') , array('id' => 'invoice_date', 'class' => 'form-control','required','readonly')) !!}</td>
                            <td><label for="invoice_no" class="control-label">Invoice No</label></td>
                            <td>{!! Form::text('invoice_no',$invoice_no , array('id' => 'invoice_no', 'class' => 'form-control','readonly'=>true)) !!}</td>
                        </tr>

                        <tr>
                            <td><label for="buyer_bank" class="control-label">Importer Bank</label></td>
                            <td>{!! Form::select('importer_bank',$imp_banks, null , array('id' => 'importer_bank', 'class' => 'form-control')) !!}</td>
                            <td><label for="exporter_bank" class="control-label">Our Bank</label></td>
                            <td>{!! Form::select('exporter_bank',$exp_banks, null , array('id' => 'exporter_bank', 'class' => 'form-control')) !!}</td>
                        </tr>

                        <tr>
                            <td><label for="loading_port" class="control-label">Port of Loading</label></td>
                            <td>{!! Form::text('loading_port', 'Chittagong Sea Port, Bangladesh' , array('id' => 'loading_port', 'class' => 'form-control')) !!}</td>
                            <td><label for="dest_port" class="control-label">Port of Destination</label></td>
                            <td>{!! Form::text('dest_port', 'Shanghai Port, China' , array('id' => 'dest_port', 'class' => 'form-control')) !!}</td>
                        </tr>

                        <tr>
                            <td><label for="app_bin" class="control-label">Applicants BIN</label></td>
                            <td>{!! Form::text('app_bin', null , array('id' => 'app_bin', 'class' => 'form-control')) !!}</td>
                            <td><label for="color" class="control-label">Color</label></td>
                            <td>{!! Form::text('color', null , array('id' => 'color', 'class' => 'form-control')) !!}</td>
                        </tr>

                        <tr>
                            <td><label for="exchange_rate" class="control-label">Exchange Rate</label></td>
                            <td>{!! Form::text('exchange_rate',1 , array('id' => 'exchange_rate', 'class' => 'form-control')) !!}</td>
                            <td><label for="invoice_amt" class="control-label">BDT</label></td>
                            <td>{!! Form::text('invoice_amt', number_format($products->items->sum('total_price'), 2, '.', ''), array('id' => 'invoice_amt', 'class' => 'form-control')) !!}</td>
                        </tr>

                        <tr>
                            <input name="id" type="hidden" id="id" value="{!! $contract->id !!}">
                            <input name="ref_no" type="hidden" id="ref_no" value="{!! $contract->export_contract_no !!}">
                            <td><label for="description" class="control-label">Delivery Terms</label></td>
                            <td colspan="3">{!! Form::textarea('description', null , ['id'=>'description','size' => '100x2','class'=>'field']) !!}</td>
                        </tr>

                        </tbody>
                        <tfoot></tfoot>
                    </table>
                </div>

                @isset($products)
                <div class="form-group col-md-12" style="background-color: rgba(177, 245, 174, 0.33)">
                    {!! Form::label('items', 'Items', ['class' => 'control-label']) !!}
                    <div class="table-responsive">
                        <table class="table table-bordered" id="items">
                            <thead>
                            <tr style="background-color: #f9f9f9;">
                                <th width="20%"  class="text-center">Product</th>
                                <th width="10%" class="text-right">Unit Price(USD)</th>
                                <th width="15%" class="text-center">Contract Quantity</th>
{{--                                <th width="15%" class="text-left">Delivered</th>--}}
                                <th width="20%" class="text-right">Invoice Quantity</th>
                                <th width="20%" class="text-right">Sub Total</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($products->items as $item_row=>$item)

                                <tr>
                                    <td id="itemcode">{!! $item->item->name !!}</td>
                                    <td class="text-right">{!! $item->unit_price !!}$</td>
                                    @foreach($contract->items as $row)
                                        @if($row->product_id === $item->product_id)
                                            <td class="text-right">{!! $row->quantity !!}$</td>
                                        @endif
                                    @endforeach
                                    <td class="text-right">{!! number_format($item->quantity,2) !!}{!! $item->item->unit_name !!}</td>
                                    <td class="text-right" style="vertical-align: middle;">{!!number_format($item->unit_price * $item->quantity,4) !!}</td>
                                </tr>

                            @endforeach

                            <tr>
                                <td class="text-right" colspan="4"><strong>Total Invoice Price</strong></td>
                                <td class="text-right" id="grand-total">{!! number_format($products->items->sum('total_price'),2) !!}</td>
                            </tr>
                            </tbody>

                            <tfoot>
                            <tr>
                                <td colspan="5">{!! Form::submit('SUBMIT',['class'=>'btn btn-primary button-control pull-right']) !!}</td>
                            </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
                @endisset
            {!! Form::close() !!}
        @endisset

    </div>

@endsection

@push('scripts')

    <script>

        $(document).on('keyup', '#exchange_rate', function(){
            amountBTD();
        });


        function amountBTD(){

            var exchange_rate = document.getElementById('exchange_rate').value;
            var usd_amount = document.getElementById('grand-total').innerHTML;
            var num = parseFloat(usd_amount.replace(/,/g, ""));

            $('#invoice_amt').val((num * exchange_rate).toFixed(2));
        }


        $(function (){
            $(document).on("focus", "input:text", function() {
                $(this).select();
            });
        });

    </script>

@endpush
