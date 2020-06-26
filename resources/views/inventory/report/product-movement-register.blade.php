@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>
    <script src="{!! asset('assets/js/bootstrap3-typeahead.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Product Movement</li>
        </ol>
    </nav>

    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-10" >
                <br/>
                <div><h3>Product Movement Register: @isset($param) 'Report Date :'. {!! $param['report_date'] !!} @endisset</h3></div>
                <div style="background-color: #ff0000;height: 2px">&nbsp;</div>
                <br/>


                <div class="div">
                    <br/>
                    {!! Form::open(['url'=>'inventory/report/productMovementRegister', 'method' => 'GET']) !!}

                    <table width="90%" class="table table-responsive table-hover" >

                        <tr>
                            <td width="15%"><label for="report_date" class="control-label" >Date</label></td>
                            <td width="25%">{!! Form::text('report_date', Carbon\Carbon::now()->format('d-m-Y'), array('id' => 'report_date', 'class' => 'form-control','required','readonly')) !!}</td>
                        </tr>
                        <tr>
                            <td><button name="action" type="submit" value="preview" class="btn btn-info btn-reject pull-left">Preview</button></td>
                            <td><button name="action" type="submit" value="print" class="btn btn-primary btn-approve pull-right">Print</button></td>
                        </tr>

                    </table>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>

    @isset($report)

        <table class="table table-bordered table-responsive table-hover">
            <thead style="background-color: #8eb4cb">
            <tr>
                <th>SL</th>
                <th>Ref No</th>
                <th>Type</th>
                <th>Item Code</th>
                <th>Item Name</th>
                <th>Contra</th>
                <th>Unit</th>
                <th class="text-right">Stock In</th>
                <th class="text-right">Stock Out</th>

{{--                <th class="text-right">User</th>--}}

            </tr>
            </thead>
            <tbody>
            @foreach($report as $i=>$item)
                <tr>
                    <td>{!! $i+1 !!}</td>
                    <td>{!! $item->ref_no !!}</td>
                    <td>{!! $item->ref_type === 'P' ? 'Purchase' : ($item->ref_type === 'S' ? 'Sales' : 'Others') !!}</td>
                    <td>{!! $item->item->sku !!}</td>
                    <td>{!! $item->item->name !!}</td>
                    <td>{!! $item->ref_type === 'P' ? $item->supplier->name : ( $item->ref_type === 'S' ? $item->customer->name : 'Others') !!}</td>
                    <td>{!! $item->item->unit_name !!}</td>
                    <td align="right">{!! number_format($item->quantity_in,0) !!}</td>
                    <td align="right">{!! number_format($item->quantity_out,0) !!}</td>
{{--                    <td>{!! $item->user->name !!}</td>--}}
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <td colspan="7"></td>
            </tfoot>
        </table>
    @endisset

@endsection

@push('scripts')

    <script>

        $(document).ready(function(){

            $( "#report_date" ).datetimepicker({
                format:'d-m-Y',
                timepicker: false,
                closeOnDateSelect: true,
                scrollInput : false,
                inline:false
            });


        });

    </script>

@endpush

