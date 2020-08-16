@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Approve Export Invoice</li>
        </ol>
    </nav>



    @empty($delivery)

        <div class="container-fluid spark-screen">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="search" id="search">
                        <br/>
                        {!! Form::open(['url'=>'export/approveExportInvoiceIndex', 'method' => 'GET']) !!}

                        <table width="50%" class="table table-responsive table-hover" >

                            <tr>
                                <td width="15%"><label for="invoice_id" class="control-label">Invoice No</label></td>
                                <td width="30%">{!! Form::select('invoice_id',$selections, null , array('id' => 'invoice_id', 'class' => 'form-control')) !!}</td>
                                <td width="10%"><button type="submit" class="btn btn-primary pull-right">Submit</button></td>
                            </tr>

                        </table>

                        {!! Form::close() !!}

                    </div>
                </div>

            </div>
        </div>

    @endempty

{{--    @isset($delivery)--}}

{{--        <div class="row justify-content-center">--}}
{{--            <div class="col-md-4">--}}
{{--                <div class="card">--}}
{{--                    <div class="card-body">--}}
{{--                        <!-- Default form contact -->--}}
{{--                        <form id="approve-delivery-form" action="{!! url('export/approveExportDeliveryIndex') !!}" method="POST">--}}
{{--                            @csrf--}}

{{--                            <input type="hidden" name="challan_id" value="{!! $delivery->id !!}">--}}

{{--                            <table class="table table-bordered table-hover table-responsive" id="delivery-table">--}}
{{--                                <tbody>--}}
{{--                                <tr>--}}
{{--                                    <td>Contract No :</td>--}}
{{--                                    <td> {!! $delivery->contract->export_contract_no !!}</td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td>Challan No:</td--}}
{{--                                    ><td>{!! $delivery->challan_no !!}</td>--}}
{{--                                <tr>--}}
{{--                                    <td width="30%">Customer :</td>--}}
{{--                                    <td width="65%">{!! $delivery->customer->name !!}</td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td>product :</td>--}}
{{--                                    <td>--}}
{{--                                        @foreach($delivery->items as $item)--}}
{{--                                            {!! $item->item->name !!} <br/>--}}
{{--                                        @endforeach--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td>Net Weight</td>--}}
{{--                                    <td>--}}
{{--                                        @foreach($delivery->items as $item)--}}
{{--                                            {!! $item->quantity !!} {!! $item->item->unit_name !!} <br/>--}}
{{--                                        @endforeach--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td>Total Bale</td>--}}
{{--                                    <td>--}}
{{--                                        {!! $delivery->serials->count('id') !!}--}}
{{--                                    </td>--}}
{{--                                </tr>--}}

{{--                                <tr>--}}
{{--                                    <td>Delivered By :</td>--}}
{{--                                    <td> {!! $delivery->user->name !!}</td>--}}
{{--                                </tr>--}}
{{--                                </tbody>--}}

{{--                                <tfoot>--}}
{{--                                <tr>--}}
{{--                                    <td><button name="action" value="approve" class="btn btn-info btn-approve btn-block" type="submit">Approve</button></td>--}}
{{--                                    <td class="text-right"><button name="action" value="reject" class="btn btn-danger btn-block" type="submit">Reject</button></td>--}}
{{--                                </tr>--}}
{{--                                </tfoot>--}}
{{--                            </table>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class=" col-md-7" style="border-right: solid; overflow: scroll; height: 400px">--}}
{{--                <div class="card">--}}
{{--                    <div class="card-body">--}}
{{--                        <!-- Default form contact -->--}}
{{--                        <table class="table table-bordered table-hover table-responsive" id="delivery-table">--}}
{{--                            <thead>--}}
{{--                            <tr class="table-primary">--}}
{{--                                <th>Product</th>--}}
{{--                                <th>Lot No</th>--}}
{{--                                <th>Bale No</th>--}}
{{--                                <th>Net Weight</th>--}}
{{--                                <th>Gross Weight</th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                            @foreach($delivery->serials as $row)--}}
{{--                                <tr>--}}
{{--                                    <td>{!! $row->history->item->name !!}</td>--}}
{{--                                    <td>{!! $row->history->lot_no !!}</td>--}}
{{--                                    <td>{!! $row->history->bale_no !!}</td>--}}
{{--                                    <td class="text-right">{!! $row->history->quantity_in !!}</td>--}}
{{--                                    <td class="text-right">{!! $row->history->gross_weight !!}</td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}

{{--                            </tbody>--}}
{{--                            <tfoot>--}}
{{--                            <tr class="bg-info">--}}
{{--                                <td colspan="3">Grand Total</td>--}}
{{--                                <td class="text-right">{!! $delivery->serials->sum('history.quantity_in') !!}</td>--}}
{{--                                <td class="text-right">{!! $delivery->serials->sum('history.gross_weight') !!}</td>--}}
{{--                            </tr>--}}
{{--                            </tfoot>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--        </div>--}}
{{--    @endisset--}}
@endsection
@push('scripts')

    <script>
        $(document).on('click', '.btn-danger', function (e) {
            e.preventDefault();
            var $this = $(this);
            $.confirm({
                title: 'Confirm Rejection!',
                content: 'Are You Sure !',
                buttons: {
                    confirm: function () {
                        $('#approve-delivery-form').submit();
                    },
                    cancel: function () {
                        $.alert('Canceled!');
                    },
                }
            });
        });


        $(document).on('click', '.btn-approve', function (e) {
            e.preventDefault();
            var $this = $(this);
            $.confirm({
                title: 'Confirm Approve!',
                content: 'Are You Sure !',
                buttons: {
                    confirm: function () {
                        $('#approve-delivery-form').submit();
                    },
                    cancel: function () {
                        $.alert('Canceled!');
                    },
                }
            });
        });

    </script>

@endpush
