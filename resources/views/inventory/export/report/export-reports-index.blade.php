@extends('layouts.app')

@section('content')
    <script src="{!! asset('assets/js/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Export Report Menus</li>
        </ol>
    </nav>

    <div class="container-fluid">

        <div class="col-sm-3">
            <div class="card">
                @isset($menus)
                    <div class="card-body" style="justify-content: center">
                        <table class="table table-striped table-success">
                            <tbody>
                            @foreach($menus as $menu)
                                <tr>
                                    <td><a href="{!! url(''.$menu->url.'') !!}" class="btn btn-facebook" style="width: 240px">{!! $menu->name !!}</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endisset
            </div>

        </div>
    </div>


    <div class="container-fluid">
    <div class="row">
        <div class="row col-sm-5">
            <div class="card">
                <div class="card-header" >Print Commercial Invoice</div>
                <div class="card-body" style="justify-content: center">
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


        <div class="col-sm-2">
            <div class="card">
                <div class="card-header" ></div>
                <div class="card-body" style="justify-content: center">

                </div>
            </div>
        </div>


        <div class="col-sm-5 justify-content-end">
            <div class="card">
                <div class="card-header" >Print Packing List</div>
                <div class="card-body" style="justify-content: center">
                    <div class="search" id="search">
                        <br/>
                        {!! Form::open(['url'=>'export/report/exportPackingListIndex', 'method' => 'GET']) !!}

                        <table width="50%" class="table table-responsive table-hover" >
                            <tr>
                                <td align="left" width="5%"><label for="type" class="control-label">Challan No</label></td>
                                <td width="10%">{!! Form::select('challan_id',$deliveries, null , array('id' => 'challan_id', 'class' => 'form-control')) !!}</td>
                            </tr>
                            <tr>
                                <td align="left" width="5%"><label for="type" class="control-label">Report Title</label></td>
                                <td width="10%">{!! Form::select('report_id',['P'=>'Packing List','D'=>'Details Packing List','C'=>'Container Packing List'], null , array('id' => 'report_id', 'class' => 'form-control')) !!}</td>
                            </tr>

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
    </div>

@endsection
