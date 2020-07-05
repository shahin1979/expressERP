@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Memorandum Voucher</li>
        </ol>
    </nav>

    <div>
        <div class="well col-md-12">
            <div class="controls">
                {!! Form::open(array('id'=>'transForm','url'=>'transaction/memorandum/save','method','post')) !!}

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



@endpush
