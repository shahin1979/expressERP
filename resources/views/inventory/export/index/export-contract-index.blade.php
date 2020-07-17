@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="{!! asset('assets/js/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Export Contract</li>
        </ol>
    </nav>

    <div class="container-fluid">

        {!! Form::open(['url'=>'setup/saveBankInfo','method'=>'POST']) !!}

        <table width="95%" class="table table-sm table-responsive">
            <tbody>
            <tr>
                <td><label for="customer_id" class="control-label">Customer</label></td>
                <td>{!! Form::select('customer_id', $customers , null , array('id' => 'customer_id', 'class' => 'form-control')) !!}</td>
                <td><label for="contract_date" class="control-label">Contract Date</label></td>
                <td>{!! Form::text('contract_date', \Carbon\Carbon::now()->format('d-m-Y') , array('id' => 'contract_date', 'class' => 'form-control','required','readonly')) !!}</td>
                <td align="right"><label for="contract_no" class="control-label">Contract No</label></td>
                <td align="right">{!! Form::text('contract_no',null , array('id' => 'contract_no', 'class' => 'form-control')) !!}</td>
            </tr>

            <tr>
                <td><label for="signing_date" class="control-label">Signing Date</label></td>
                <td>{!! Form::text('signing_date', \Carbon\Carbon::now()->format('d-m-Y') , array('id' => 'signing_date', 'class' => 'form-control','required','readonly')) !!}</td>
                <td><label for="expiry_date" class="control-label">Validity Expiry Date</label></td>
                <td>{!! Form::text('expiry_date', \Carbon\Carbon::now()->format('d-m-Y') , array('id' => 'expiry_date', 'class' => 'form-control','required','readonly')) !!}</td>
                <td><label for="t_limit" class="control-label">Tolerance Limit (%)</label></td>
                <td align="right">{!! Form::text('tolerance_limit',null , array('id' => 'tolerance_limit', 'class' => 'form-control')) !!}</td>
            </tr>


            <tr>
                <td><label for="loading_port" class="control-label">Port of Loading</label></td>
                <td>{!! Form::text('loading_port', 'Chittagong Sea Port, Bangladesh' , array('id' => 'loading_port', 'class' => 'form-control')) !!}</td>
                <td><label for="dest_port" class="control-label">Port of Destination</label></td>
                <td>{!! Form::text('dest_port', 'Shanghai Port, China' , array('id' => 'dest_port', 'class' => 'form-control')) !!}</td>

                <td><label for="shipment_time" class="control-label">Time of Shipment(Days)</label></td>
                <td>{!! Form::text('shipment_time', null , array('id' => 'shipment_time', 'class' => 'form-control')) !!}</td>

            </tr>

            <tr>
                <td><label for="currency" class="control-label">Description</label></td>
                <td>{!! Form::select('currency', $currencies , null , array('id' => 'currency', 'class' => 'form-control')) !!}</td>
                <td><label for="description" class="control-label">Description</label></td>
                <td colspan="4">{!! Form::textarea('description', null , ['id'=>'description','size' => '65x2','class'=>'field']) !!}</td>
            </tr>

            </tbody>
            <tfoot></tfoot>
        </table>


        {!! Form::close() !!}

    </div> <!--/.Container-->


@endsection

@push('scripts')

@endpush
