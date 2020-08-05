@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Delivery Export Products</li>
        </ol>
    </nav>

    @empty($data)

    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="search" id="search">
                    <br/>
                    {!! Form::open(['url'=>'export/deliveryExportProductIndex', 'method' => 'GET']) !!}

                    <table width="50%" class="table table-responsive table-hover" >

                        <tr>
                            <td width="15%"><label for="type" class="control-label">Export Contract No</label></td>
                            <td width="30%">{!! Form::select('contract_id',$contracts, null , array('id' => 'contract_id', 'class' => 'form-control')) !!}</td>
                            <td width="10%"><button type="submit" class="btn btn-primary pull-right">Submit</button></td>
                        </tr>

                    </table>

                    {!! Form::close() !!}

                </div>
            </div>

        </div>
    </div>
    @endempty
    <div class="container-fluid">

        @isset($data)

            {!! Form::open(['url'=>'#','class' =>'delivery-products','id'=>'delivery-products']) !!}
            <div class="row">

                <table class="table table-bordered table-hover padding" >

                    <thead style="background-color: #cecece">
                        <tr>
                            <th>Contract No</th>
                            <th>Challan No</th>
                            <th>Delivery Mode</th>
                            <th>Truck No</th>
                            <th>Bale/Lot No</th>
                            <th>Action</th>
                        </tr>

                    </thead>
                    <tbody>
                        <tr style="background-color: rgba(175,255,255,0.18)">
                            <td id="contract_no">{!! $data->export_contract_no !!}</td>
                            <td>{!! Form::text('challan_no', isset($challan) ? $challan->challan_no : 99, array('id' => 'challan_no', 'class' => 'challan_no form-control','readonly'=>true)) !!}</td>
                            <td>{!! Form::select('d_mode',(['' => 'Please Select','B'=>'BY BALE', 'L'=>'BY LOT']), null , array('id' => 'd_mode', 'class' => 'd_mode form-control')) !!}</td>
                            <td>{!! Form::text('vehicle_no', null, array('id' => 'vehicle_no', 'class' => 'vehicle_no form-control', 'autocomplete'=>'off')) !!}</td>
                            <td rowspan="2">{!! Form::text('bale_no', null, array('id' => 'bale_no', 'class' => 'bale_no form-control','autofocus'=>'on','required','autocomplete'=>'off')) !!}</td>
                            {!! Form::hidden('contract_id', $data->id, array('id' => 'contract_id')) !!}
                            <td rowspan="2">{!! Form::submit('SUBMIT',['id'=>'btn-submit', 'class'=>'btn btn-primary btn-submit button-control']) !!}</td>

                        </tr>
                    </tbody>
                </table>
            </div>

            {!! Form::close() !!}

{{--            @isset($challan)--}}
            <div class="row">
                <div class="col-md-7 dataTables_wrapper" style="overflow-x:auto;">
                    <table class="table table-bordered table-hover" id="items-table">
                        <thead style="background-color: #c4e3f3">
                        <tr>
    {{--                        <th>Vehicle No</th>--}}
                            <th>Lot No</th>
                            <th>Bale No</th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th style="text-align:right">Total : </th>
                            <th></th>
                            <th>Balance</th>
                            <th></th>
                            <th>KG</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>


                <div class="col-md-4 dataTables_wrapper" style="overflow-x:auto;">
                    <table class="table table-bordered table-hover" id="products-table">
                        <thead style="background-color: #c4e3f3">
                        <tr>
                            {{--                        <th>Vehicle No</th>--}}
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th style="text-align:right">Total : </th>
                            <th>Quantity</th>
                            <th></th>
                            <th>Grand Total</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>


{{--            <div class="row">--}}
{{--                {!! Form::open(['url' => 'delivery.item.post','method'=>'POST']) !!}--}}
{{--                <button name="action" type="submit" class="btn btn-primary btn-post pull-left" value="{!! 0 !!}">APPROVE</button>--}}
{{--                {!! Form::close() !!}--}}
{{--                --}}{{--            {!! Form::submit('SUBMIT',['id'=>'btn-submit', 'class'=>'btn btn-primary btn-post pull-left','value'=>$invoiceno]) !!}--}}
{{--                <button type="button" class="btn btn-info pull-right btn-truck" data-toggle="modal" data-target="#myModal">View Truck</button>--}}
{{--            </div>--}}
{{--            @endisset--}}
        @endisset

    </div>

@endsection

@push('scripts')

    <script>

        $(document).ready(function() {

            $("#delivery-products").submit(function(e) {

                e.preventDefault();
                $.ajax({
                    url : 'storeExportProducts',
                    type: "POST",
                    data: {bale_no:$("#bale_no").val(), d_mode:$("#d_mode").val(), vehicle_no:$('#vehicle_no').val(), contract_id:$('#contract_id').val(),
                        challan_no:$('#challan_no').val()},

                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    dataType: "JSON",
                    async: false,
                    success: function(data)
                    {
                        $('#bale_no').val(null);
                        // $('#vehicle_no').val(null);
                        $('#challan_no').val(data.challan_no);
                        // alert(data.success);
                        $('#items-table').DataTable().draw(true);
                    },
                    error: function (request, textStatus, errorThrown)
                    {
                        var myObj = JSON.parse(request.responseText);
                        $.alert({
                            title: 'Alert!',
                            content: myObj.error,
                        });

                        // $('#items-table').DataTable().draw(false);

                    }
                });
            });
        });

        // alert($('#challan_no').val());


        var table= $('#items-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: 'delivery/items/' + $('#contract_id').val(),
            columns: [
                // { data: 'history.vehicle_no', name: 'history.vehicle_no' },
                { data: 'history.lot_no', name: 'history.lot_no' },
                { data: 'unique_id', name: 'unique_id' },
                { data: 'item.name', name: 'item.name' },
                { data: 'history.quantity_in', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2 ), name: 'history.quantity_in' },
                { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
            ],
            "fnDrawCallback": function() {
                var api = this.api()
                var json = api.ajax.json();
                $(api.column(1).footer()).html(json.delivered);
                $(api.column(3).footer()).html(json.balance);
            },

            rowCallback: function( row, data, index ) {
                if(index%2 == 0){
                    $(row).removeClass('myodd myeven');
                    $(row).addClass('myodd');
                }else{
                    $(row).removeClass('myodd myeven');
                    $(row).addClass('myeven');
                }
            }

        });

        $('#items-table').on('click', '.btn-delete', function (e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var url = $(this).data('remote');
            // confirm then
            $.ajax({
                url: url,
                type: 'DELETE',
                dataType: 'json',
                data: {method: '_DELETE', submit: true}
            }).always(function (data) {
                $('#items-table').DataTable().draw(false);
            });
        });



        var table= $('#products-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: 'delivery/products/' + $('#contract_id').val(),
            columns: [
                { data: 'item.name', name: 'item.name' },
                { data: 'unit_price', name: 'unit_price' },
                { data: 'quantity', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2 ), name: 'quantityn' },
            ],
            // "fnDrawCallback": function() {
            //     var api = this.api()
            //     var json = api.ajax.json();
            //     $(api.column(1).footer()).html(json.delivered);
            //     $(api.column(3).footer()).html(json.balance);
            // },
            //
            // rowCallback: function( row, data, index ) {
            //     if(index%2 == 0){
            //         $(row).removeClass('myodd myeven');
            //         $(row).addClass('myodd');
            //     }else{
            //         $(row).removeClass('myodd myeven');
            //         $(row).addClass('myeven');
            //     }
            // }

        });


    </script>

@endpush
