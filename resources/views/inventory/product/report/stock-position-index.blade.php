@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>
    <script src="{!! asset('assets/js/bootstrap3-typeahead.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Stock Position</li>
        </ol>
    </nav>

    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-6" >
                <br/>
                <div><h3>Stock Position Report</h3></div>
                <div style="background-color: #ff0000;height: 2px">&nbsp;</div>
                <br/>


                <div class="div">
                    <br/>
                    {!! Form::open(['url'=>'product/rptStockPositionIndex', 'method' => 'GET']) !!}

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

    @if(!empty($report))

        <table class="table table-bordered table-responsive table-hover">
            <thead style="background-color: #8eb4cb">
            <tr>
                <th>SL</th>
                <th>Item Code</th>
                <th>Item Name</th>
                <th class="text-right">Opening</th>
                <th class="text-right">Purchase</th>
                <th class="text-right">Sold</th>
                <th class="text-right">On Hand</th>
                <th class="text-right">Committed</th>
                <th class="text-right">Available</th>
                <th>Unit</th>
            </tr>
            </thead>
            <tbody>
            @foreach($report as $i=>$item)
                <tr>
                    <td>{!! $i+1 !!}</td>
                    <td>{!! $item->product_code !!}</td>
                    <td>{!! $item->name !!}</td>
                    <td align="right">{!! number_format($item->opening_qty,0) !!}</td>
                    <td align="right">{!! number_format($item->received_qty,0) !!}</td>
                    <td align="right">{!! number_format($item->sell_qty,0) !!}</td>
                    <td align="right">{!! number_format($item->onhand,0) !!}</td>
                    <td align="right">{!! number_format($item->committed,0) !!}</td>
                    <td align="right">{!! number_format(($item->onhand - $item->committed),0) !!}</td>
                    <td>{!! $item->unit_name !!}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <td colspan="7"></td>
            </tfoot>
        </table>
    @endif

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
