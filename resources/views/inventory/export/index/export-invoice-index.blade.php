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
                            <td align="right"><label for="invoice_no" class="control-label">Invoice No</label></td>
                            <td align="right">{!! Form::text('invoice_no',null , array('id' => 'invoice_no', 'class' => 'form-control')) !!}</td>
                        </tr>

                        <tr>
                            <td><label for="buyer_bank" class="control-label">Importer Bank</label></td>
                            <td>{!! Form::select('importer_bank',$imp_banks, null , array('id' => 'importer_bank', 'class' => 'form-control')) !!}</td>
                            <input name="buyer_bank_id" type="hidden" id="buyer_bank_id">
                            <td><label for="our_bank" class="control-label">Our Bank</label></td>
                            <td>{!! Form::text('our_bank',null , array('id' => 'our_bank', 'class' => 'form-control typeahead1', 'autocomplete'=>'off')) !!}</td>
                            <input name="seller_bank_id" type="hidden" id="seller_bank_id">
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
                            <td>{!! Form::text('invoice_amt',null , array('id' => 'invoice_amt', 'class' => 'form-control')) !!}</td>
                        </tr>

                        <tr>
                            <input name="id" type="hidden" id="id" value="{!! $contract->id !!}">
                            <input name="ref_no" type="hidden" id="ref_no" value="{!! $contract->export_contract_no !!}">
                            <td><label for="description" class="control-label">Delivery Terms</label></td>
                            <td colspan="3">{!! Form::textarea('description', null , ['id'=>'description','size' => '60x4','class'=>'field']) !!}</td>
                        </tr>

                        </tbody>
                        <tfoot></tfoot>
                    </table>
                </div>

            {!! Form::close() !!}
        @endisset

    </div>

@endsection

@push('scripts')

    <script>



    </script>

@endpush
