@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Edit Un Authorised Voucher</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header" style="background-color: rgba(5,133,83,0.34)">Insert Voucher No for update</div>

                <div class="card-body">

                    <form class="form-inline" id="search-form-date" method="get" action="{{ url('transaction/editUnAuthVoucherIndex') }}">

{{--                        <div class="form-group row">--}}

                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>Voucher No</td>
                                        <td><input type="text" name="voucher_no" id="voucher_no" class="form-control" required autofocus /></td>
                                        <td><button type="submit" class="btn btn-primary mb-2"><i class="fa fa-search">Submit</i></button></td>
                                    </tr>
                                </tbody>
                            </table>
                    </form>


                </div>
            </div>
        </div>
    </div>



    @if(!empty($data))
{{--        <form id="search-form-date" method="get" action="{{ url('transaction/updateUnAuthVoucherIndex') }}">--}}

{{--            <table class="table table-bordered table-responsive">--}}
{{--                <thead>--}}
{{--                <tr>--}}
{{--                    <th style="width: 25%">Ledger</th>--}}
{{--                    <th style="width: 15%">Debit</th>--}}
{{--                    <th style="width: 15%">Credit</th>--}}
{{--                    <th style="width: 35%">Description</th>--}}
{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tbody>--}}
{{--                @foreach($data as $row)--}}
{{--                    <tr>--}}
{{--                        <td >{!! Form::select('acc_no',$glheads, $row->acc_no, array('id' => 'acc_no', 'class' => 'form-control')) !!}</td>--}}
{{--                        <td ><input type="text" name="debit_amt" id="debit_amt" value="{!! $row->dr_amt !!}" class="form-control" required /></td>--}}
{{--                        <td ><input type="text" name="credit_amt" id="credit_amt" value="{!! $row->cr_amt !!}" class="form-control" required /></td>--}}
{{--                        <td ><input type="text" name="description" id="description" value="{!! $row->trans_desc1 !!}" class="form-control" required /></td>--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
{{--                </tbody>--}}
{{--                </tbody>--}}
{{--            </table>--}}
{{--        </form>--}}

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: rgba(5,133,83,0.34)">Update Voucher : {!! $data->unique('voucher_no')->first()->voucher_no !!}</div>

                <div class="card-body">

                    <form method="post" action="{{ url('transaction/updateUnAuthVoucherIndex') }}">


                        <input type="hidden" name="maxDate" id="maxDate" value="{!! \Carbon\Carbon::now() !!}"/>
                        <input type="hidden" name="minDate" id="minDate" value="{!! $min_date !!}"/>

                        @csrf

                        <table class="table table-bordered table-responsive">
                            <thead>
                            <tr>
                                <th style="width: 15%">Date</th>
                                <th style="width: 25%">Chk No</th>
                                <th style="width: 60%; text-align: right">User Created</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td ><input type="text" name="trans_date" id="trans_date" value="{!! $data->unique('trans_date')->first()->trans_date !!}" class="form-control text-right" required readonly /></td>
                                    <td ><input type="text" name="cheque_no" id="cheque_no" value="{!! $data->contains('cheque_no') ? $data->where('cheque_no','<>',null)->first()->cheque_no : null !!}" class="form-control text-right" /></td>
                                    <td style="text-align: right">{!! $data->unique('user_id')->first()->user->name !!}</td>
                                    <input type="hidden" name="voucher_no" id="minDate" value="{!! $data->unique('voucher_no')->first()->voucher_no !!}"/>
                                </tr>
                            </tbody>
                        </table>



                        <table class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th style="width: 30%">Ledger</th>
                                    <th style="width: 15%; text-align: right">Debit</th>
                                    <th style="width: 15%; text-align: right">Credit</th>
                                    <th style="width: 40%">Description</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $row)
                                {!! Form::hidden('id[]', $row->id, array('id' => 'id')) !!}
                            <tr>
                                <td >{!! Form::select('acc_no[]',$glheads, $row->acc_no, array('id' => 'acc_no', 'class' => 'form-control')) !!}</td>
                                <td ><input type="text" name="dr_amt[]" id="debit_amt" value="{!! $row->dr_amt !!}" class="form-control text-right" required /></td>
                                <td ><input type="text" name="cr_amt[]" id="credit_amt" value="{!! $row->cr_amt !!}" class="form-control text-right" required /></td>
                                <td ><input type="text" name="description[]" id="description" value="{!! $row->trans_desc1 !!}" class="form-control" required /></td>
                            </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4"><button type="submit" class="btn btn-primary mb-2"><i class="fa fa-search">Submit</i></button></td>
                                </tr>
                            </tfoot>
                        </table>
                    </form>


                </div>
            </div>
        </div>
    </div>

    @endif

@endsection

@push('scripts')
    <script>

        $(document).ready(function(){

            var max_date = new Date();
            var min_date = new Date(document.getElementById('minDate').value);

            $( "#trans_date" ).datetimepicker({
                format:'d-m-Y',
                timepicker: false,
                closeOnDateSelect: true,
                scrollInput : false,
                inline:false,
                maxDate: new Date(max_date.getFullYear(),max_date.getMonth(),max_date.getDate()+1),
                minDate: new Date(min_date.getFullYear(),min_date.getMonth(),min_date.getDate()+1)
            });
        });


        $(function (){
            $(document).on("focus", "input:text", function() {
                $(this).select();
            });
        });
    </script>
@endpush
