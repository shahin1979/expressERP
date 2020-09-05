@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Upload Shipment File</li>
        </ol>
    </nav>


    @empty($delivery)

        <div class="container-fluid spark-screen">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="search" id="search">
                        <br/>
                        {!! Form::open(['url'=>'export/uploadShipmentFile', 'method' => 'POST', 'files'=>'true']) !!}

                        <table width="50%" class="table table-responsive table-hover" >

                            <tr>
                                <td width="15%"><label for="invoice_id" class="control-label">Invoice No</label></td>
                                <td width="30%">{!! Form::select('invoice_id',$invoices, null , array('id' => 'invoice_id', 'class' => 'form-control')) !!}</td>

                                <td><div class="custom-file">
                                        <input type="file" name="import_file" class="custom-file-input" id="customFile">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </td>
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
        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>

@endpush
