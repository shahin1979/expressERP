@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Approve Export Delivery Products</li>
        </ol>
    </nav>



    @empty($delivery)

        <div class="container-fluid spark-screen">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="search" id="search">
                        <br/>
                        {!! Form::open(['url'=>'export/approveExportDeliveryIndex', 'method' => 'GET']) !!}

                        <table width="50%" class="table table-responsive table-hover" >

                            <tr>
                                <td width="15%"><label for="challan_id" class="control-label">Delivery Challan No</label></td>
                                <td width="30%">{!! Form::select('challan_id',$selections, null , array('id' => 'challan_id', 'class' => 'form-control')) !!}</td>
                                <td width="10%"><button type="submit" class="btn btn-primary pull-right">Submit</button></td>
                            </tr>

                        </table>

                        {!! Form::close() !!}

                    </div>
                </div>

            </div>
        </div>

    @endempty

    @isset($delivery)
        <div class="justify-content-center">
            <table class="table table-bordered table-hover table-responsive" id="delivery-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Contract No</th>
                    <th>Challan No</th>
                    <th>Customer</th>
                    <th>product</th>
                    <th>Quantity</th>
                    <th>Delivered By</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{!! $delivery->contract->export_contract_no !!}</td>
                        <td>{!! $delivery->challan_no !!}</td>
                        <td>{!! $delivery->customer->name !!}</td>
                        <td>
                        @foreach($delivery->items as $item)
                               {!! $item->item->name !!} <br/>
                        @endforeach
                        </td>

                        <td>
                            @foreach($delivery->items as $item)
                                {!! $item->quantity !!} {!! $item->item->unit_name !!} <br/>
                            @endforeach
                        </td>
                        <td>{!! $delivery->user->name !!}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    <div class="row" id="top-head">
        <div class="col-md-4">
            <div class="pull-left">
                <button type="button" class="btn btn-back btn-primary"><i class="fa fa-backward"></i>Back</button>
            </div>
        </div>
    </div>

    @endisset
{{--    --}}{{--    <form id="ajax-items">--}}
{{--    <div class="row" id="data-section">--}}
{{--        <div class="col-sm-3">--}}
{{--            <div class="card">--}}
{{--                <div class="card-body">--}}
{{--                    <h5 class="card-title">Delivery Challan Info</h5>--}}
{{--                    <table id="delivery-main" class="table table-striped delivery-main">--}}

{{--                    </table>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <input name="challan_no" type="hidden" id="challan_no" value="">--}}

{{--        <div class="col-sm-9" >--}}
{{--            <div class="card">--}}
{{--                <div class="card-body">--}}
{{--                    <h5 class="card-title">Product Info</h5>--}}
{{--                    <table id="delivery-items" class="table table-striped table-info table-bordered delivery-items">--}}
{{--                        <thead>--}}
{{--                        <tr>--}}
{{--                            <th>Item</th>--}}
{{--                            <th style="text-align: right">In<br/>Stock</th>--}}
{{--                            <th style="text-align: right">Delivery Qty</th>--}}
{{--                        </tr>--}}
{{--                        </thead>--}}

{{--                        <tbody>--}}

{{--                        </tbody>--}}

{{--                        <tfoot>--}}
{{--                        <tr style="background-color: rgba(224,229,229,0.96)">--}}
{{--                            <td colspan="2"><button type="submit" name="action" value="approve" id="action" class="btn btn-primary btn-approve">Approve</button></td>--}}
{{--                            <td colspan="2"><button type="submit" name="action" value="reject" id="action" class="btn btn-danger btn-approve pull-right">Reject</button></td>--}}
{{--                        </tr>--}}
{{--                        </tfoot>--}}

{{--                    </table>--}}

{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}


{{--        <div class="col-sm-8">--}}
{{--            <div class="card">--}}
{{--                <div class="card-body">--}}
{{--                    <h5 class="card-title">Stock Inventory Voucher</h5>--}}
{{--                    <table id="trans-items" class="table table-striped table-info table-bordered">--}}
{{--                        <thead>--}}
{{--                        <tr>--}}
{{--                            <th>GL Head</th>--}}
{{--                            <th style="text-align: right">Debit Amount {!! $users_company->currency !!}</th>--}}
{{--                            <th style="text-align: right">Credit Amount {!! $users_company->currency !!}</th>--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}

{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}


{{--    </div>--}}


    {{--    </form>--}}





@endsection

@push('scripts')
    <script>
        $(function (){
            $(document).on("focus", "input:text", function() {
                $(this).select();
            });
        });
    </script>

@endpush
