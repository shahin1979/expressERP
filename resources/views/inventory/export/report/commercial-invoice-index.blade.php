@extends('layouts.app')

@section('content')
    <script src="{!! asset('assets/js/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('export/report/exportReportsIndex') !!}">Export Report</a></li>
            <li class="breadcrumb-item active">Print Commercial Invoice</li>
        </ol>
    </nav>

    <div class="container-fluid justify-content-center">

    <div class="spark-screen">
        <div class="row">
            <div class="col-md-6 col-md-offset-2">
                <br/>
                <div><h3>Print Commercial Invoice</h3></div>
                <div style="background-color: #ff0000;height: 2px">&nbsp;</div>
                <br/>


                <div class="search" id="search">
                    <br/>
                    {!! Form::open(['url'=>'export/report/exportCommercialInvoiceReportIndex', 'method' => 'GET']) !!}

                    <table width="50%" class="table table-responsive table-hover" >

                        <td align="left" width="5%"><label for="type" class="control-label">Invoice No</label></td>
                        <td width="10%">{!! Form::select('invoice_no',$invoices, null , array('id' => 'invoice_no', 'class' => 'form-control')) !!}</td>

                        <tr>
                            <td width="10%"><button name="action" type="submit" value="pre-shipment" class="btn btn-info btn-reject pull-left">Pre-Shipment</button></td>
                            <td width="10%"><button name="action" type="submit" value="print" class="btn btn-primary btn-approve pull-right">Post-Shipment</button></td>
                        </tr>
                    </table>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
