@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Print Statement</li>
        </ol>
    </nav>

    <div class="container spark-screen" id="date-selection">
        <div class="row">
            <div class="col-md-8 col-md-offset-1" >
                <div class="div">
                    <br/>
{{--                    {!! Form::open(['url'=>'statement/prepareStatementIndex', 'method' => 'GET']) !!}--}}

                    <table width="80%" class="table table-responsive table-hover" >

                        <tr>
                            <td width="25%"><label for="date_from" class="control-label" >Statement Date</label></td>
                            <td width="50%">{!! Form::text('statement_date', Carbon\Carbon::now()->format('d-m-Y') , array('id' => 'statement_date', 'class' => 'form-control','required','readonly')) !!}</td>
                            <td><button name="action" type="submit" value="preview" class="btn btn-info btn-date-submit pull-left">Submit</button></td>
                        </tr>
                    </table>

{{--                    {!! Form::close() !!}--}}

                </div>
            </div>
        </div>
    </div>

    <div class="container spark-screen" id="statement-selection">

        <div class="row">
            <div class="col-md-8 col-md-offset-2" >

                <div class="div">
                    <br/>
                    {!! Form::open(['url'=>'statement/showStatementIndex', 'method' => 'GET']) !!}

                    <table width="90%" class="table table-responsive table-hover" >

                        <tr>
                            <td width="25%"><label for="acc_no" class="control-label" >Select File</label></td>
                            <td width="55%">{!! Form::select('input_file',$fileList, null  , array('id' => 'input_file', 'class' => 'form-control','required')) !!}</td>
                            <td  width="20%"><button name="action" type="submit" value="preview" class="btn btn-info btn-reject pull-left">Submit</button></td>
                        </tr>
                    </table>

                    {!! Form::close() !!}
                </div>
            </div>
            <div style="width: 5px"></div>
        </div>
    </div>


@endsection

@push('scripts')
<script>

    $(document).ready(function(){

        $( "#statement_date" ).datetimepicker({
            format:'d-m-Y',
            timepicker: false,
            closeOnDateSelect: true,
            scrollInput : false,
            inline:false
        });

        $('#statement-selection').hide();
        $('#date-selection').show();
    });

    $(document).on('click', '.btn-date-submit', function (e) {
        e.preventDefault();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var url = 'prepareStatementIndex';

        // confirm then
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',

            data: {method: '_POST', submit: true,
                statement_date:$('#statement_date').val(),
            },

            error: function (request, status, error) {
                alert(request.responseText);
            },

            success: function (data) {
               alert(data.success);
                $('#date-selection').hide();
                $('#statement-selection').show();
            }
        });


    });

</script>


@endpush
