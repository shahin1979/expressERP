@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Delivery Export Products</li>
        </ol>
    </nav>

    <div>

        <div class="container spark-screen">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
{{--                    <br/>--}}
{{--                    <div><h3>Deliver Export Invoice Products </h3></div>--}}
{{--                    <div style="background-color: #ff0000;height: 2px">&nbsp;</div>--}}
{{--                    <br/>--}}


                    <div class="search" id="search">
                        <br/>
                        {!! Form::open(['url'=>'deliveryExportProductIndex', 'method' => 'GET']) !!}

                        <table width="50%" class="table table-responsive table-hover" >

                            <tr>
                                <td width="15%"><label for="type" class="control-label">Export Contract No</label></td>
                                <td width="30%">{!! Form::select('contract_no',$contracts, null , array('id' => 'contract_no', 'class' => 'form-control')) !!}</td>
                                <td width="10%"><button type="submit" class="btn btn-primary pull-right">Submit</button></td>
                            </tr>

                        </table>

                        {!! Form::close() !!}

                    </div>
                </div>

            </div>
        </div>
    </div>






@endsection

@push('scripts')

    <script>



    </script>

@endpush
