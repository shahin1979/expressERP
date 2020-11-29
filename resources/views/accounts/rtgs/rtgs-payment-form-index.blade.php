@extends('layouts.app')

@section('content')
    <script src="{!! asset('assets/js/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">RTGS Payment Form</li>
        </ol>
    </nav>

    <div class="container-fluid">

        {!! Form::open(['url'=>'rtgs/rtgsPayment','method'=>'post']) !!}
        {{ csrf_field() }}

        <div class="card">
            <div class="card-body">
                <table class="table">
                    <tbody>
                    <tr>
                        <td width="15%">Contract Date</td>
                        <td width="35%">{!! Form::text('tr_date', \Carbon\Carbon::now()->format('d-m-Y') , array('id' => 'tr_date', 'class' => 'form-control','required','readonly')) !!}</td>
                        <td width="15%">Document No</td>
                        <td width="35%">{!! Form::text('document_no', '5059448521' , array('id' => 'document_no', 'class' => 'form-control')) !!}</td>
                    </tr>

                    <tr>
                        <td width="15%">Sender Account</td>
                        <td width="35%">{!! Form::select('sender_acc', $sender , null , array('id' => 'sender_acc', 'class' => 'form-control')) !!}</td>
                        <td width="15%">Beneficiary Account</td>
                        <td width="35%">{!! Form::select('bnf_acc', $beneficiary , null , array('id' => 'bnf_acc', 'class' => 'form-control')) !!}</td>
                    </tr>

{{--                    <tr>--}}
{{--                        <td width="15%">Beneficiary Account</td>--}}
{{--                        <td width="35%">{!! Form::select('bnf_acc', $beneficiary , null , array('id' => 'bnf_acc', 'class' => 'form-control')) !!}</td>--}}
{{--                        <td width="15%">ID No</td>--}}
{{--                        <td width="35%">{!! Form::text('id_no', null , array('id' => 'id_no', 'class' => 'form-control')) !!}</td>--}}

{{--                    </tr>--}}

                    <tr>
                        <td width="15%">Transaction Amount</td>
                        <td width="35%">{!! Form::text('trans_amt', 0 , array('id' => 'trans_amt', 'class' => 'form-control')) !!}</td>
                        <td width="15%">Reason of Payment</td>
                        <td width="35%">{!! Form::text('reason', null , array('id' => 'reason', 'class' => 'form-control')) !!}</td>
                    </tr>

                    <tr>
                        <td width="15%">Ref/Chq/Doc #</td>
                        <td width="35%">{!! Form::text('ref_no', null , array('id' => 'ref_no', 'class' => 'form-control')) !!}</td>
                        <td width="15%">ID No</td>
                        <td width="35%">{!! Form::text('id_no', null , array('id' => 'id_no', 'class' => 'form-control')) !!}</td>
                    </tr>


                    <tr>
                        <td width="15%">Description</td>
                        <td colspan="3" width="85%">{!! Form::textarea('description', null , ['id'=>'description','size' => '60x4','class'=>'field pull-left']) !!}</td>
                    </tr>

                    </tbody>

                    <tfoot>
                    <tr>
                        <td colspan="2">{!! Form::submit('SUBMIT',['class'=>'btn btn-primary button-control pull-right']) !!}</td>
                    </tr>


                    </tfoot>
                </table>

                {!! Form::close() !!}
            </div>
        </div>



    </div>


@endsection

@push('scripts')

    <script>

        $( "#tr_date" ).datetimepicker({
            format:'d-m-Y',
            timepicker: false,
            closeOnDateSelect: true,
            scrollInput : false,
            inline:false
        });



        $(function (){
            $(document).on("focus", "input:text", function() {
                $(this).select();
            });
        });

    </script>

@endpush
