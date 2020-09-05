@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Update Shipment Information</li>
        </ol>
    </nav>



    @empty($delivery)

        <div class="container-fluid spark-screen">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="search" id="search">
                        <br/>
                        {!! Form::open(['url'=>'export/updateShipmentDataIndex', 'method' => 'GET']) !!}

                        <table width="50%" class="table table-responsive table-hover" >

                            <tr>
                                <td width="15%"><label for="challan_id" class="control-label">Delivery Challan No</label></td>
                                <td width="30%">{!! Form::select('challan_id',$selections, null , array('id' => 'challan_id', 'class' => 'form-control')) !!}</td>
                                <td width="10%"><button type="submit" name="action" class="btn btn-primary pull-right">Submit</button></td>
                            </tr>

                        </table>

                        {!! Form::close() !!}

                    </div>
                </div>

            </div>
        </div>

    @endempty

    @isset($delivery)

        <div class="row justify-content-center">
            <div class=" col-md-10">
                <div class="card">
                    <div class="card-body">
                        <!-- Default form contact -->

                        {!! Form::open(['url'=>'export/updateShipmentData', 'method' => 'POST']) !!}
                            {{ csrf_field() }}

                            <table class="table table-sm table-responsive">
                                <tbody>
                                <tr>
                                    <td><label for="shipping_ref" class="control-label">Shipping Ref</label></td>
                                    <td align="right">{!! Form::text('shipping_ref',$delivery->shipping_ref , array('id' => 'shipping_ref', 'class' => 'form-control','required')) !!}</td>
                                    <td><label for="shipping_mark" class="control-label">Shipping Mark</label></td>
                                    <td align="right">{!! Form::text('shipping_mark',$delivery->shipping_ref , array('id' => 'shipping_mark', 'class' => 'form-control','required')) !!}</td>
                                    {{--<td><button type="button" class="btn btn-default btn-primary" data-toggle="modal" data-target="#modal-unit"><i class="glyphicon glyphicon-plus-sign"></i></button></td>--}}
                                    <td><label for="shipping_date" class="control-label">Shipping Date</label></td>
                                    <td>{!! Form::text('shipping_date', isset($delivery->shipping_date) ? $delivery->shipping_date : \Carbon\Carbon::now()->format('d-m-Y') , array('id' => 'shipping_date', 'class' => 'form-control','required','readonly')) !!}</td>
                                </tr>
                                <tr>
                                    <td><label for="vessel_no" class="control-label">Vessel No</label></td>
                                    <td>{!! Form::text('vessel_no', $delivery->vessel_no , array('id' => 'vessel_no', 'class' => 'form-control','required')) !!}</td>
                                    <td><label for="packing" class="control-label">Packing</label></td>
                                    <td colspan="3">{!! Form::text('packing', $delivery->packing , array('id' => 'packing', 'class' => 'form-control')) !!}</td>

                                    {!! Form::hidden('challan_id', $delivery->id, array('id' => 'challan_id')) !!}

                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="6"><button name="action" type="submit" value="approve" class="btn btn-primary pull-right">Submit</button></td>
                                </tr>
                                </tfoot>
                            </table>

                            {!! Form::Close() !!}
                    </div>
                </div>
            </div>
        </div>
    @endisset

    @isset($delivery->shipping_date)
{{--    {!! Form::open(['url'=>'export/updateContainerData', 'method' => 'POST']) !!}--}}
    <div class="row justify-content-center">
        <div class=" col-md-10">
            <div class="card">
                <div class="card-body">Container Information</div>
                <div class="card-body">
                    <!-- Default form contact -->
                    <table class="table table-bordered table-hover table-striped table-responsive" id="container-table">
                        <thead>
                        <tr class="table-primary" style="border-bottom: darkred">
                            <th>Lot No</th>
                            <th>Total Bale</th>
                            <th>Truck No</th>
                            <th>Net Weight</th>
                            <th>Gross Weight</th>
                            <th>Seal No</th>
                            <th>CBM</th>
                            <th>Container</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $row)
                            <tr>
                                <td class="text-right">{!! $row->lot_no !!}</td>
                                <td>{!! $row->bale_count !!}</td>
                                <td>{!! $row->vehicle_no !!}</td>
                                <td class="text-right">{!! number_format($row->net_weight,2) !!}</td>
                                <td class="text-right">{!! number_format($row->gross_weight,2) !!}</td>
                                <td>{!! $row->seal_no !!}</td>
                                <td>{!! $row->cbm !!}</td>
                                <td>{!! Form::select('container', $containers , $row->container,  array('id' => 'container', 'class' => 'form-control')) !!}</td>
                                <td><button name="action" type="submit" class="btn btn-primary btn-container"><span>Save</span></button></td>
                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                        <tr class="table-primary">
                            <td colspan="3">Grand Total</td>
                            <td class="text-right">{!! number_format($products->sum('net_weight'),2) !!}</td>
                            <td class="text-right">{!! number_format($products->sum('gross_weight'),2) !!}</td>
                            <td colspan="4"></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
{{--        {!! Form::close() !!}--}}
    @endisset()

@endsection
@push('scripts')

    <script>
        $(document).ready(function(){

            $( "#shipping_date" ).datetimepicker({
                format:'d-m-Y',
                timepicker: false,
                closeOnDateSelect: true,
                scrollInput : false,
                inline:false
            });


            $("#container-table").on('click','.btn-container',function(){
                // get the current row
                var currentRow=$(this).closest("tr");
                var col1 = currentRow.find("td:eq(0)").html(); // get current row 1st table cell TD value
                var container = currentRow.find("td:eq(7) select").val(); // get current row container table cell  TD value

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var url = 'updateContainerData/' + col1;

                // confirm then
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: {method: '_POST', submit: true, container: container,challan:$('#challan_id').val()},

                    error: function (request, status, error) {
                        var myObj = JSON.parse(request.responseText);
                        $.alert({
                            title: 'Alert!',
                            content: myObj.message + ' ' + myObj.error,
                        });
                    },

                    success: function (response) {
                        $.alert({
                            title: 'Alert!',
                            content: 'Shipment Information updated',
                        });

                        // alert(response.seal.cbm);



                        currentRow.find("td:eq(5)").html(response.container.seal_no);
                        currentRow.find("td:eq(6)").html(response.container.cbm);

                        currentRow.find("td:eq(7) select").css('color', 'red');
                        currentRow.find("td:eq(7) select").attr('disabled', 'true');
                    },
                });

            });
        });

        $(function (){
            $(document).on("focus", "input:text", function() {
                $(this).select();
            });
        });

    </script>
@endpush
