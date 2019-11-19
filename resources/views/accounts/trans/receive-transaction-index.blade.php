@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <script type='text/javascript'>

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var counter = 0;
        var transAmount = 0;
        var cashbal = 0;

        $(function () {

            $(document).on('click', '.btn-add', function (e) {
                e.preventDefault();

                var controlForm = $('.controls form:first'),
                    currentEntry = $(this).parents('.entry:first'),
                    newEntry = $(currentEntry.clone(true)).appendTo(controlForm);
                counter++;
                currentEntry.find('select').each(function () { this.disabled=true

                });

                newEntry.find('input').val('');
                newEntry.find('select').val('');

                controlForm.find('.entry:not(:last) .btn-add')
                    .removeClass('btn-add').addClass('btn-remove')
                    .removeClass('btn-success').addClass('btn-danger')
                    .html('<span class="fa fa-minus"></span>');
            }).on('click', '.btn-remove', function (e) {
                $(this).parents('.entry:first').remove();

                e.preventDefault();
                return false;
            }).on('click','.btn-primary', function (event) {


                $("#transForm").find('select').each(function () { this.disabled=false });
            });
        });

        //START DROPDOWN DT

        jQuery(document).ready(function ($) {
            var $select = $('[name="grpCredit[]"]');

            $('select[name="grpCredit[]"]').eq(counter).change(function () {

//                alert("You have selected the - "+ $(this));

                $.get("{!! url('transaction/credit/head')  !!}", {option: $('select[name="grpCredit[]"]').eq(counter).val()},
                    function (data) {
                        var accDr = $('select[name="accCr[]"]').eq(counter);
                        accDr.empty();
                        $.each(data, function (key, value) {
                            accDr.append($("<option></option>")
                                .attr("value", key)
                                .text(value));
                        });
                    });
            });
        });

        // GET SELECTED DEVIT HEAD BALANCE

        jQuery(document).ready(function($) {
            $('#acc_dr').change(function(){
                $.get("{!! url('transaction/debitBalance')  !!}", { option: $('#acc_dr').val() },
                    function(data) {
                        (document).getElementById('curr_bal').value = data;
                        document.getElementById('curr_bal').style.color="magenta";

                    });
            });
        })

    </script>


    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Receive Voucher</li>
        </ol>
    </nav>


    <div>
        <div class="well col-md-12">
            <div class="controls">
                {!! Form::open(array('id'=>'transForm','url'=>'transaction/receive/save','method','post')) !!}

                <input type="hidden" name="minDate" id="minDate" value="{!! $min_date !!}"/>
                <input type="hidden" name="maxDate" id="maxDate" value="{!! $max_date !!}"/>

                <table class="table table-hover">

                    <tbody>
                    <tr>
                        <td>Debit</td>
                        <td>{!! Form::select('acc_dr',$debits, '10112101', array('id' => 'acc_dr', 'class' => 'form-control')) !!}</td>
                        <td>{!! Form::text('curr_bal', (get_account_balance('10112101',$company->company_id)) , array('id' => 'curr_bal', 'class' => 'form-control head-balance text-right', 'readonly' => 'true')) !!}</td>
                        <td>{!! Form::label('date','Date:', array('class' => 'col-md-1 control-label')) !!}</td>
                        <td>{!! Form::text('trans_date', Carbon\Carbon::now()->format('d-m-Y') , array('id' => 'trans_date', 'class' => 'form-control','readonly' => 'true', 'data-mg-required' => '')) !!}</td>
                    </tr>

                    <tr>
                        <td>CHK No</td>
                        <td>{!! Form::text('chk_no', null , array('id' => 'chk_no', 'class' => 'form-control')) !!}</td>
                        {{--                        <td>Description</td>--}}
                        <td colspan="3">{!! Form::text('trans_desc2', null , array('id' => 'trans_desc2', 'class' => 'form-control','data-mg-required' => '','placeholder'=>'Payment Notes')) !!}</td>
                    </tr>


                    </tbody>
                </table>

                <br>


                <table class="entry table table-responsive table-bordered padding table-hover" >

                    <tr>
                        @if($company->project == true)
                            <td>{!! Form::select('project_id[]',$projects, null, array('id' => 'project_code', 'class' => 'col-sm-12 form-control', 'placeholder'=>'Select Project')) !!}</td>
                        @else
                            {!! Form::hidden('project_id[]', null, array('id' => 'project_id')) !!}
                        @endif

                        <td>{!! Form::select('grpCredit[]', $grp_credits, null,array('id' => 'grpCredit', 'class' => 'col-sm-12 form-control','placeholder'=>'Please Select Group')) !!}</td>
                        <td>{!! Form::select('accCr[]', $credits, null,array('id' => 'accCr', 'class' => 'col-sm-12 form-control','placeholder'=>'Select Account')) !!}</td>
                        <td>{!! Form::text('transAmt[]', null , array('id' => 'transAmt',  'class' => 'col-sm-12 form-control transaction text-right', 'required' => 'true','placeholder'=>'Amount')) !!}</td>
                        <td>{!! Form::text('transDesc[]', null , array('id' => 'transDesc', 'class' => 'col-sm-12 form-control', 'placeholder'=>'Description')) !!}</td>
                        {!! Form::hidden('id[]', null, array('id' => 'id')) !!}
                        <td>
                                <span class="input-group-btn col-xs-1">
                                  <button style="margin: 0 auto" class="btn btn-success btn-add" type="button">
                                      <span class="fa fa-plus"></span>
                                  </button>
                                </span>
                        </td>
                        {{--@else--}}
                        {{--<td>{!! Form::select('grpDebit[]',$groupList, null,array('id' => 'grpDebit', 'class' => 'col-sm-12 control-text')) !!}</td>--}}
                        {{--<td>{!! Form::select('accDr[]', array('' => 'Please Select Debit Head'), null,array('id' => 'accDr', 'class' => 'col-sm-12 control-text', 'data-mg-required' => 'true')) !!}</td>--}}
                        {{--<td>{!! Form::text('transAmt[]', null , array('id' => 'transAmt', 'class' => 'col-sm-12 control-text', 'data-mg-required' => 'true', 'placeholder'=>'Amount')) !!}</td>--}}
                        {{--<td>{!! Form::text('transDesc1[]', null , array('id' => 'transDesc1', 'class' => 'col-sm-12 control-text', 'placeholder'=>'Description')) !!}</td>--}}
                        {{--@endif--}}
                    </tr>

                </table>

                <br>
            </div>

            <div class="col-md-6">
                {!! Form::submit('SUBMIT',['class'=>'btn btn-primary button-control']) !!}
            </div>

            {!! Form::close() !!}



        </div>
    </div>

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

    </script>
@endpush
