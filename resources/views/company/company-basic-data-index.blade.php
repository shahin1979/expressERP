@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>
    <link href="{!! asset('assets/bootstrap4-toggle-3.6.1/css/bootstrap4-toggle.min.css') !!}" rel="stylesheet">
    <script src="{!! asset('assets/bootstrap4-toggle-3.6.1/js/bootstrap4-toggle.min.js') !!}"></script>
    <link href="{!! asset('assets/css/bootstrap-imageupload.css') !!}" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="{!! asset('assets/js/bootstrap-imageupload.js') !!}"></script>

{{--    <link href="{!! asset('assets/css/jquery.datetimepicker.min.css') !!}" rel="stylesheet" type="text/css" />--}}


    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Company Properties</li>
        </ol>
    </nav>


    <div class="container-fluid">

        <div class="row">
            <div class="col-md-6">
                <div class="pull-left">
                    <a class="btn btn-primary" href="{!! URL::previous() !!}"> <i class="fa fa-list"></i> Back </a>
                </div>
            </div>
        </div>

        {!! Form::open(['url'=>'company/propertiesSave','method'=>'POST','enctype'=>'multipart/form-data']) !!}
        <div class="row">
{{--            <div><img src="{!! asset($users_company->company_logo) !!}" height="40px" width="150px" class="header-brand-img" alt="LOGO"></div>--}}

            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        Basic
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover table-striped">
                            <tbody>
                            <tr>
                                <td>{!! $comp->name !!}</td>
                                <td>{!! $comp->address !!}</td>
                            </tr>

                            <input type="hidden" id="posted" name="posted" value="{!! isset($basic->posted) !!}" class="form-control" />

                            <tr>
                                <td>Cash Account</td>
                                <td>{!! Form::text('cash',isset($basic->cash) ? $basic->cash : '101',['id'=>'cash','class'=>'form-control'])  !!}</td>
                            </tr>

                            <tr>
                                <td>Cash Account</td>
                                <td>{!! Form::text('bank',isset($basic->bank) ? $basic->bank : '102',['id'=>'bank','class'=>'form-control'])  !!}</td>

                            </tr>

                            <tr>
                                <td>Cash Account</td>
                                <td>{!! Form::text('sales',isset($basic->sales) ? $basic->sales : '301',['id'=>'sales','class'=>'form-control'])  !!}</td>
                            </tr>

                            <tr>
                                <td>Cash Account</td>
                                <td>{!! Form::text('purchase',isset($basic->purchase) ? $basic->purchase : '401',['id'=>'purchase','class'=>'form-control'])  !!}</td>
                            </tr>

                            <tr>
                                <td>Cash Account</td>
                                <td>{!! Form::text('capital',isset($basic->capital) ? $basic->capital : '501',['id'=>'capital','class'=>'form-control'])  !!}</td>
                            </tr>

                            <tr>
                                <td>FP Start</td>
                                <td>{!! Form::text('fp_start',(isset($basic->fp_start) ? \Carbon\Carbon::parse($basic->fp_start)->format('d-m-Y')  : \Carbon\Carbon::now()->format('d-m-Y')), array('id' => 'fp_start', 'class' => 'form-control','readonly')) !!}</td>
                            </tr>

                            <tr>
                                <td>Auto Sales/Purchase Ledger</td>
                                <td>
                                    <input type="checkbox" {!! isset($basic->auto_ledger) ?  $basic->auto_ledger == 1 ? 'checked' : 'unchecked' : 'unchecked' !!} name="hAuto_ledger" data-toggle="toggle" data-onstyle="primary">
                                </td>
                            </tr>

                            <tr>
                                <td>Company Logo</td>
                                <td>
                                    <div class="imageupload">
                                        <div class="file-tab">
                                            <label class="btn btn-success btn-file">
                                                <span>Upload</span>
                                                <!-- The file is stored here. -->
                                                <input type="file" name="company_logo" id="company_logo">
                                            </label>
                                            <button type="button" class="btn btn-default">Remove</button>
                                        </div>
                                    </div>

                                </td>
                            </tr>




                            {{--                                    @endif--}}

                            </tbody>
                        </table>
{{--                        <a href="#" class="btn btn-primary">Go somewhere</a>--}}
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        Modules
                    </div>

                    <div class="card-body">
                        <table class="table table-striped table-bordered table-hover">
                            <tbody>
                            @foreach($modules as $row)

{{--                                <@php($)--}}

                                <tr>
                                    <td width="350px">{!! $row->module_name !!}</td>
                                    <td width="150px"><input type="checkbox" {!! empty($comp_modules) ? 'unchecked' : $comp_modules->contains('module_id',$row->id) ? 'checked' : 'unchecked' !!} name="module_id[]" value="{!! $row->id !!}" data-toggle="toggle" data-onstyle="primary">
                                </tr>
                            @endforeach

                            <tr>
                                <td>Logo</td>
                                <td><img src="{!! asset($users_company->company_logo) !!}" class="rounded float-left" style="height: 100px; width: 150px" alt="..."></td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td><button type="submit" id="emp-data" value="" class="btn btn-emp-data btn-primary btn-sm">Submit</button></td>
                                <td><button type="submit" id="emp-reject" value="" class="btn btn-emp-reject btn-danger btn-sm pull-right">Exit</button></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}



{{--        <div class="row justify-content-center">--}}
{{--            <div class="card">--}}
{{--                <div class="card-header">--}}
{{--                    <h3>Company Basic Data Settings</h3>--}}
{{--                    --}}{{--<h3 style="font-weight: bold">Department Name : {!! isset($data[0]->department_id) ? $data[0]->department->name : '' !!}<br/>--}}
{{--                    --}}{{--Report Title: Attendance Summery Report. Date from : {!! \Carbon\Carbon::parse($from_date)->format('d-M-Y') !!} to {!! \Carbon\Carbon::parse($to_date)->format('d-M-Y') !!}</h3>--}}
{{--                </div>--}}
{{--                {!! Form::open(['url'=>'company/propertiesSave','method'=>'POST']) !!}--}}
{{--                <div class="card-body">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-md-10" style="overflow-x:auto;">--}}
{{--                            <table class="table table-bordered table-hover table-striped">--}}
{{--                                <tbody>--}}
{{--                                        <tr>--}}
{{--                                            <td colspan="2">{!! $company->name !!}</td>--}}
{{--                                            <td colspan="2">{!! $company->address !!}</td>--}}
{{--                                        </tr>--}}

{{--                                        <input type="hidden" id="posted" name="posted" value="{!! isset($basic->posted) !!}" class="form-control" />--}}

{{--                                        <tr>--}}
{{--                                            <td>Cash Account</td>--}}
{{--                                            <td>{!! Form::text('cash',isset($basic->cash) ? $basic->cash : '101',['id'=>'cash','class'=>'form-control'])  !!}</td>--}}
{{--                                            <td>Project Module</td>--}}
{{--                                            <td><input type="checkbox" {!! isset($basic->project) ? ($basic->project == 1 ? 'checked' : 'unchecked') : 'unchecked' !!} name="hProject" data-toggle="toggle" data-onstyle="primary">--}}
{{--                                        </tr>--}}

{{--                                        <tr>--}}
{{--                                            <td>Cash Account</td>--}}
{{--                                            <td>{!! Form::text('bank',isset($basic->bank) ? $basic->bank : '102',['id'=>'bank','class'=>'form-control'])  !!}</td>--}}
{{--                                            <td>Inventory Module</td>--}}
{{--                                            <td><input type="checkbox" {!! isset($basic->inventory) ? $basic->inventory == 1 ? 'checked' : 'unchecked' : 'unchecked' !!} name="hInventory" data-toggle="toggle" data-onstyle="primary">--}}

{{--                                        </tr>--}}

{{--                                        <tr>--}}
{{--                                            <td>Cash Account</td>--}}
{{--                                            <td>{!! Form::text('sales',isset($basic->sales) ? $basic->sales : '301',['id'=>'sales','class'=>'form-control'])  !!}</td>--}}
{{--                                            <td>Auto Sales/Purchase Ledger</td>--}}
{{--                                            <td>--}}
{{--                                                <input type="checkbox" {!! isset($basic->auto_ledger) ?  $basic->auto_ledger == 1 ? 'checked' : 'unchecked' : 'unchecked' !!} name="hAuto_ledger" data-toggle="toggle" data-onstyle="primary">--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}

{{--                                        <tr>--}}
{{--                                            <td>Cash Account</td>--}}
{{--                                            <td>{!! Form::text('purchase',isset($basic->purchase) ? $basic->purchase : '401',['id'=>'purchase','class'=>'form-control'])  !!}</td>--}}
{{--                                            <td>Currency</td>--}}
{{--                                            <td><input type="checkbox" checked data-toggle="toggle" data-onstyle="primary"></td>--}}

{{--                                            <td>BDT</td>--}}
{{--                                        </tr>--}}

{{--                                        <tr>--}}
{{--                                            <td>Cash Account</td>--}}
{{--                                            <td>{!! Form::text('capital',isset($basic->capital) ? $basic->capital : '501',['id'=>'capital','class'=>'form-control'])  !!}</td>--}}
{{--                                            <td>FP Start</td>--}}
{{--                                            <td>{!! Form::text('fp_start',(isset($basic->fpstart) ? \Carbon\Carbon::parse($basic->fpstart)->format('d-m-Y')  : \Carbon\Carbon::now()->format('d-m-Y')), array('id' => 'fp_start', 'class' => 'form-control','readonly')) !!}</td>--}}
{{--                                        </tr>--}}


{{--                                        <tr>--}}
{{--                                            <td><button type="submit" id="emp-data" value="" class="btn btn-emp-data btn-primary btn-sm">Submit</button></td>--}}
{{--                                            <td><button type="submit" id="emp-reject" value="" class="btn btn-emp-reject btn-danger btn-sm pull-right">Exit</button></td>--}}
{{--                                        </tr>--}}
{{--                                    @endif--}}

{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                {!! Form::close() !!}--}}
{{--            </div>--}}

{{--        </div>--}}
    </div>





{{--        @if(isset($basic->posted) == false)--}}

{{--            <div class="col-md-6">--}}
{{--                {!! Form::submit('NEW',['class'=>'btn btn-primary button-control','name'=>'action', 'value'=>'NEW']) !!}--}}
{{--            </div>--}}
{{--        @endif--}}

{{--        @if(isset($basic->posted) == true)--}}

{{--            <div class="col-md-6">--}}
{{--                {!! Form::submit('UPDATE',['class'=>'btn btn-primary button-control','name'=>'action', 'value'=>'UPDATE']) !!}--}}
{{--            </div>--}}
{{--        @endif--}}
{{--        {!! Form::close() !!}--}}

{{--        {!! Form::open(['url'=>'home', 'method' => 'GET']) !!}--}}

{{--        <div class="col-md-6">--}}
{{--            {!! Form::submit('EXIT',['class'=>'btn btn-primary button-control pull-right']) !!}--}}
{{--        </div>--}}


@endsection
@push('scripts')

    <script>

        $(document).ready(function(){

            $( "#fp_start" ).datetimepicker({
                format:'d-m-Y',
                timepicker: false,
                closeOnDateSelect: true,
                scrollInput : false,
                inline:false
            });
        });

        $('.imageupload').imageupload({
            allowedFormats: [ "jpg", "jpeg" ],
            previewWidth: 250,
            previewHeight: 250,
            maxFileSizeKb: 2048
        });


    </script>

@endpush
