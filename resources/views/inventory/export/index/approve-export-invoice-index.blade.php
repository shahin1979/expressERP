@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Approve Export Invoice</li>
        </ol>
    </nav>



    @empty($invoice)

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

    @isset($invoice)

        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body">
                        <!-- Default form contact -->
                        <form id="approve-invoice-form" action="{!! url('export/approveExportInvoiceIndex') !!}" method="POST">
                            @csrf

                            <input type="hidden" name="invoice_id" value="{!! $invoice->id !!}">

                            <table class="table table-bordered table-hover table-responsive" id="invoice-table">
                                <tbody>
                                <tr>
                                    <td>Contract No :</td>
                                    <td> {!! $invoice->contract->export_contract_no !!}</td>
                                </tr>
                                <tr>
                                    <td>Invoice No:</td><td>{!! $invoice->invoice_no !!}</td>
                                </tr>
                                <tr>
                                    <td width="40%">Customer :</td>
                                    <td width="60%">{!! $invoice->customer->name !!}</td>
                                </tr>
                                <tr>
                                    <td>product :</td>
                                    <td>
                                        @foreach($invoice->items as $item)
                                            {!! $item->item->name !!} <br/>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td>Net Weight</td>
                                    <td>
                                        @foreach($invoice->items as $item)
                                            {!! number_format($item->quantity,2) !!} {!! $item->item->unit_name !!} <br/>
                                        @endforeach
                                    </td>
                                </tr>

                                <tr>
                                    <td width="40%">Invoice Amount :</td>
                                    <td width="60%">{!! number_format($invoice->fc_amt,2) !!}{!! $invoice->currency !!}</td>
                                </tr>
                                <tr>
                                    <td width="40%">Exchange Rate :</td>
                                    <td width="60%">{!! $invoice->exchange_rate !!}</td>
                                </tr>

                                <tr>
                                    <td width="40%">BTD Amt :</td>
                                    <td width="60%">{!! number_format($invoice->invoice_amt,2) !!}</td>
                                </tr>
                                <tr>
                                    <td>Created By :</td>
                                    <td> {!! $invoice->user->name !!}</td>
                                </tr>
                                </tbody>

                                <tfoot>
                                <tr>
                                    <td><button name="action" value="approve" class="btn btn-info btn-approve btn-block" type="submit">Approve</button></td>
                                    <td class="text-right"><button name="action" value="reject" class="btn btn-danger btn-block" type="submit">Reject</button></td>
                                </tr>
                                </tfoot>
                            </table>
                        </form>
                    </div>
                </div>
            </div>


            <div class="col-md-7">
                <div class="card">
                    <div class="card-body">
                        <!-- Default form contact -->
                        <table class="table table-bordered table-hover table-responsive" id="invoice-table">
                            <thead>
                            <tr>
                                <th>Account</th>
                                <th>Debit Amt</th>
                                <th>Credit Amt</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <td>{!! $param['cr_account'] !!} : {!! get_account_name_from_number($invoice->company_id,$param['cr_account']) !!}</td>
                                <td class="text-right">0.00</td>
                                <td class="text-right">{!! number_format($invoice->invoice_amt,2) !!}</td>
                            </tr>

                            <tr>
                                <td>{!! $invoice->customer->ledger_acc_no !!} : {!! get_account_name_from_number($invoice->company_id,$invoice->customer->ledger_acc_no) !!}</td>
                                <td class="text-right"> {!! number_format($invoice->invoice_amt,2) !!}</td>
                                <td class="text-right">0.00</td>
                            </tr>

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
        $(document).on('click', '.btn-danger', function (e) {
            e.preventDefault();
            var $this = $(this);
            $.confirm({
                title: 'Confirm Rejection!',
                content: 'Are You Sure !',
                buttons: {
                    confirm: function () {
                        $('#approve-invoice-form').submit();
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
                        $('#approve-invoice-form').submit();
                    },
                    cancel: function () {
                        $.alert('Canceled!');
                    },
                }
            });
        });

    </script>

@endpush
