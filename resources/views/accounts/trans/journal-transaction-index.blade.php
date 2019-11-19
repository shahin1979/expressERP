@extends('layouts.app')

@section('content')


    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <script type='text/javascript'>

        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     }
        // });

        var counter = 0;
        var debit = 0;
        var credit = 0;

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
            }).on('click','.btn-save', function (event) {

                $("#transForm").find('select').each(function () { this.disabled=false });

                $("#transForm").find('input.debitamount').each(function () {
                    debit += parseInt($(this).val()||0); });

                $("#transForm").find('input.creditamount').each(function () {
                    credit += parseInt($(this).val()||0); });
                if(debit != credit)
                {
                    alert('Transaction Debit & Credit Amount are not same');
                    debit  = 0;
                    credit = 0;
                    return false;
                }
                else
                {
                    debit  = 0;
                    credit = 0;
                    return true;
                }
            });
        });



        jQuery(document).ready(function ($) {
            var $select = $('[name="grpDebit[]"]');

            $('select[name="grpDebit[]"]').eq(counter).change(function () {

                $.get("{!! url('bp.debit.head')  !!}", {option: $('select[name="grpDebit[]"]').eq(counter).val()},
                    function (data) {
                        var accDr = $('select[name="accDr[]"]').eq(counter);
                        accDr.empty();
                        $.each(data, function (key, value) {
                            accDr.append($("<option></option>")
                                .attr("value", key)
                                .text(value));
                        });
                    });
            });
        });

        jQuery(document).ready(function ($) {
            var $select = $('[name="grpCredit[]"]');

            $('select[name="grpCredit[]"]').eq(counter).change(function () {

                $.get("{!! url('br.credit.head')  !!}", {option: $('select[name="grpCredit[]"]').eq(counter).val()},
                    function (data) {
                        var accCr = $('select[name="accCr[]"]').eq(counter);
                        accCr.empty();
                        $.each(data, function (key, value) {
                            accCr.append($("<option></option>")
                                .attr("value", key)
                                .text(value));
                        });
                    });
            });
        });

        function showInput() {
            document.getElementById('crAmt').value = document.getElementById('drAmt').value;
        }

        jQuery(document).ready(function($) {
            $('#dr_acc').change(function(){
                $.get("{!! url('newJournal/getDebitBall')  !!}", { option: $('#dr_acc').val() },
                    function(data) {
                        var debitBall = $('#debitBall');
                        debitBall.empty();
                        $.each(data, function(key, value) {
                            debitBall.val(value);
                        });
                    });
            });
        });

        jQuery(document).ready(function($) {
            $('#cr_acc').change(function(){
                $.get("{!! url('newJournal/getCreditBall')  !!}", { option: $('#cr_acc').val() },
                    function(data) {
                        var creditBall = $('#creditBall');
                        creditBall.empty();
                        $.each(data, function(key, value) {
                            creditBall.val(value);
                        });
                    });
            });
        });

        $(function(){
            $('#cr_acc').change(function(e) {
                document.getElementById('creditBall').style.color="magenta";
            });
        });

        $(function(){
            $('#dr_acc').change(function(e) {
                document.getElementById('debitBall').style.color="magenta";
            });
        });

    </script>


    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Journal Voucher Entry</li>
        </ol>
    </nav>



    <div>
        <div class="well col-md-12">
            <div class="controls">
                {!! Form::open(array('id'=>'transForm','url'=>'transaction/journal/save','method','post')) !!}

                <input type="hidden" name="minDate" id="minDate" value="{!! $min_date !!}"/>
                <input type="hidden" name="maxDate" id="maxDate" value="{!! $max_date !!}"/>

                <table class="table table-hover">

                    <tbody>
                    <tr>
                        <td>Project</td>
                        <td>{!! Form::select('project_id',$projects, null, array('id' => 'project_id', 'class' => 'form-control','placeholder'=>'Please Select')) !!}</td>
                        <td>{!! Form::label('date','Date:', array('class' => 'col-md-1 control-label')) !!}</td>
                        <td>{!! Form::text('trans_date', Carbon\Carbon::now()->format('d-m-Y') , array('id' => 'trans_date', 'class' => 'form-control','readonly' => 'true', 'data-mg-required' => '')) !!}</td>
                    </tr>

                    <tr>
                        <td>Trans Type</td>
                        <td>{!! Form::select('type_id',$trans_types, 6, array('id' => 'type_id', 'class' => 'form-control')) !!}</td>
                        {{--                        <td>Description</td>--}}
                        <td colspan="3">{!! Form::text('trans_desc', null , array('id' => 'trans_desc', 'class' => 'form-control','data-mg-required' => '','placeholder'=>'Journal Notes')) !!}</td>
                    </tr>


                    </tbody>
                </table>

                <br>


                <div class="entry">
                    <div class="col-sm-12 panel panel-default padding-left" style="overflow-x:auto;">
                        {{--<div class="panel-heading">Debit."                 ". Credit</div>--}}
                        <table class="table table-bordered table-hover padding table-responsive" >

                            <th colspan="3" style="background-color: #66afe9"><strong>DEBIT ENTRY</strong></th>
                            <th colspan="3" style="background-color: #8eb4cb"><strong>CREDIT ENTRY</strong></th>
                            <tr>
                                <td style="background-color: #66afe9">{!! Form::select('grpDebit[]', $groupList, null,array('id' => 'grpDebit', 'class' => 'form-control','placeholder'=>'Please Select')) !!}</td>
                                <td style="background-color: #66afe9">{!! Form::select('accDr[]', $accountList, null,array('id' => 'accDr', 'class' => 'form-control', 'placeholder'=>'Select Account')) !!}</td>
                                <td style="background-color: #66afe9">{!! Form::text('drAmt[]', null , array('id' => 'drAmt','onKeyUp'=>'showInput()', 'class' => 'form-control debitamount', 'placeholder'=>'Debit Amount')) !!}</td>

                                <td style="background-color: #8eb4cb">{!! Form::select('grpCredit[]', $groupList, null,array('id' => 'grpCredit', 'class' => 'form-control','placeholder'=>'Please Select')) !!}</td>
                                <td style="background-color: #8eb4cb">{!! Form::select('accCr[]', $accountList, null,array('id' => 'accCr', 'class' => 'form-control', 'placeholder'=>'Select Account')) !!}</td>
                                <td style="background-color: #8eb4cb">{!! Form::text('crAmt[]',null , array('id' => 'crAmt', 'class' => 'form-control creditamount','placeholder'=>'Credit Amount')) !!}</td>
                                {!! Form::hidden('id[]', null, array('id' => 'id')) !!}

                                <td>
                                    <span class="input-group-btn col-xs-1">
                                        <button style="margin: 0 auto" class="btn btn-success btn-add" type="button">
                                          <span class="fa fa-plus"></span>
                                      </button>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <br>
            </div>

            <div class="col-md-6">
                {!! Form::submit('SUBMIT',['class'=>'btn btn-primary btn-save button-control']) !!}
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
