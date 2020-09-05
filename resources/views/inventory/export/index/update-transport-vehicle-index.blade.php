@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Update Truck Info</li>
        </ol>
    </nav>



    @empty($delivery)

        <div class="container-fluid spark-screen">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="search" id="search">
                        <br/>
                        {!! Form::open(['url'=>'export/updateVehicleNoIndex', 'method' => 'GET']) !!}

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
            <div class=" col-md-7">
                <div class="card">
                    <div class="card-body">
                        <!-- Default form contact -->
                        <table class="table table-bordered table-hover table-striped table-responsive" id="lot-table">
                            <thead>
                            <tr class="table-primary">
                                <th>Lot No</th>
                                <th>Total Bale</th>
                                <th>Net Weight</th>
                                <th>Gross Weight</th>
                                <th width="250px">Truck No</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lots as $row)
                                <tr>
                                    <td>{!! $row->lot_no !!}</td>
                                    <td>{!! $products->where('lot_no',$row->lot_no)->count('bale_no') !!}</td>
                                    <td class="text-right">{!! $products->where('lot_no',$row->lot_no)->sum('quantity_in') !!}</td>
                                    <td class="text-right">{!! $products->where('lot_no',$row->lot_no)->sum('gross_weight') !!}</td>
                                    <td>{!! Form::text('vehicle_no', null , array('id' => 'vehicle_no', 'class' => 'form-control')) !!}</td>
                                    <td><button name="action" type="submit" class="btn btn-primary btn-save"><span>Save</span></button></td>
                                </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr class="bg-info">
                                <td colspan="2">Grand Total</td>
                                <td class="text-right">{!! $products->sum('quantity_in') !!}</td>
                                <td class="text-right">{!! $products->sum('gross_weight') !!}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>


            <div class=" col-md-4">
                <div class="card">
                    <div class="card-body">
                        <!-- Default form contact -->
                        <table class="table table-bordered table-hover table-striped table-responsive" id="bale_table">
                            <thead>
                            <tr class="table-primary">
                                <th>Bale No</th>
                                <th>Truck No</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>


                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

        </div>

    @endisset
@endsection
@push('scripts')

<script>
    $(document).ready(function(){

        // code to read selected table row cell data (values).
        $("#lot-table").on('click','.btn-save',function(){


            // get the current row
            var currentRow=$(this).closest("tr");
            var col1 = currentRow.find("td:eq(0)").html(); // get current row 1st table cell TD value
            var col5 = currentRow.find("td:eq(4) input").val(); // get current row 3rd table cell  TD value

            if(!currentRow.find("td:eq(4) input").val()){ alert('Please Enter Truck Number'); return false}

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var url = 'updateVehicleNo/' + col1;

            // confirm then
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {method: '_POST', submit: true, vehicle: col5},

                error: function (request, status, error) {
                    var myObj = JSON.parse(request.responseText);
                    $.alert({
                        title: 'Alert!',
                        content: myObj.message + ' ' + myObj.error,
                    });
                },

                success: function (data) {
                    $.alert({
                        title: 'Alert!',
                        content: 'Truck No updated',
                    });

                    currentRow.find("td:eq(4) input").css('color', 'red');
                    currentRow.find("td:eq(4) input").attr('readonly', 'true');
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
