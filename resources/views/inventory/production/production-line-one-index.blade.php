@extends('layouts.app')

@section('content')
    <script src="{!! asset('assets/js/jquery-3.3.1.min.js') !!}"></script>
{{--    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>--}}

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            @isset($production)
            <li class="breadcrumb-item active">Line {!! $production->line_no == 1 ? 'One' : 'Two' !!}</li>
            @endisset

            @isset($prod)
                <li class="breadcrumb-item active">Line {!! $prod->line_no == 1 ? 'One' : 'Two' !!}</li>
            @endisset
        </ol>
    </nav>

    @empty($prod)

    <div class="container-fluid justify-content-center">
        <div class="row">
            <div class="col-md-8">
                <br/>
                <div>Current Product : {!! $production->item->name !!} {!! $production->item->model->name !!} D {!! $production->item->size->size !!}
                    Lot # {!! $production->lot_no !!} Bale # {!! $production->bale_no !!}</div>
                <div style="background-color: #ff0000;height: 2px">&nbsp;</div>
                <br/>


                <div class="search" id="search">
                    <div class="row">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 style="padding:12px 0px;font-size:25px;"><strong>Click Browse and select file</strong></h3>
                            </div>
                            <div class="card-body">
                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success" role="alert">
                                        {{ Session::get('success') }}
                                    </div>
                                @endif
{{--                                @if ($message = Session::get('error'))--}}
{{--                                    <div class="alert alert-danger" role="alert">--}}
{{--                                        {{ Session::get('error') }}--}}
{{--                                    </div>--}}
{{--                                @endif--}}
                                <form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 20px;" action="{{ url('production/submitWeight') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                                    {{--<form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 20px;" action="{{ URL::to('receivermfilepost') }}" class="form-horizontal" method="post" enctype="multipart/form-data">--}}
                                    <input type="file" name="import_file" />
                                    {{ csrf_field() }}
{{--                                    <br/>--}}
                                    <button class="btn btn-primary" name="action" value="{!! $production->line_no !!}">Submit to Import File</button>
                                </form>
                                <br/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endempty

    @isset($prod)

        {!! Form::open(['url'=>'production/save','target' => '_blank','id'=>'bale-submit-form']) !!}

        <div class="card padding-left">
            <div class="card-header">Production Bale No: {!! $prod->bale_no !!}</div>
            <table class="table table-bordered table-hover padding" >
                <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Danier</th>
                    <th>Unit</th>
                    <th>Gross Weight</th>
                    <th>TR Weight</th>
                    <th>Net Weight</th>
                </tr>

                </thead>
                <tbody>
                <tr style="background-color: #afffff;">
                    <td>Polyester Staple Fiber : {!! $prod->item->subcategory->name !!} {!! $prod->item->size->size !!}</td>
                    <td>{!! $prod->item->model->name !!}</td>
                    <td>KG</td>
                    <td>{!! Form::text('gross_weight',number_format($param['gross_weight'],2) , array('id' => 'gross_weight','class' => 'form-control','autocomplete'=>'off','readonly','required')) !!}</td>
                    <td>{!! $prod->tr_weight !!}</td>
                    <td>{!! Form::text('quantity_in', $param['gross_weight'] - $prod->tr_weight , array('id' => 'quantity_in','class' => 'form-control','readonly','autocomplete'=>'off')) !!}</td>
                    {!! Form::hidden('tr_weight', $prod->tr_weight, array('id' => 'tr_weight','class'=>'tr_weight')) !!}
                    {!! Form::hidden('product_id', $prod->product_id , array('id' => 'product_id')) !!}
                </tr>
                </tbody>
            </table>
        </div>
        <br>
        <div class="col-md-4">
            {!! Form::hidden('receive_no', $param['receive_no'], array('id' => 'receive_no')) !!}
            {!! Form::hidden('line_no', $prod->line_no, array('id' => 'line_no')) !!}
            {!! Form::submit('SUBMIT',['class'=>'btn  btn-primary btn-submit form-control','id'=>'SUBMIT']) !!}
        </div>
        {!! Form::close() !!}

    @endisset

@endsection
@push('scripts')

    <script>

        $(document).ready(function () {
            $(document).on('click', '.btn-submit', function (e) {

                $(this).attr("disabled", true);
                document.getElementsByName("grossWeight").clear();
            });
        });
    </script>

@endpush
