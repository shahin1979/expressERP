@extends('layouts.app')

@section('content')
    <script src="{!! asset('assets/js/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Set Current Production</li>
        </ol>
    </nav>

    @isset($production)

    <div class="card card-default padding-left">
{{--        <div class="card-header">Current Products</div>--}}
        <div class="card-body">
            <table class="table table-bordered table-hover padding" id="view-table">
                <thead>
                <th>Line</th>
                <th>Item Name</th>
                <th>Danier</th>
                <th>Length</th>
                <th>TR Weight</th>
                <th>Lot No</th>
                <th>Bale No</th>
                {{--            <th>Status</th>--}}
                </thead>
                <tbody>
                @foreach($production as $i=>$item)
                    <tr class="new-row {!! $i%2== 0 ? 'table-success' : 'table-primary' !!}">
                        <td>{!! $item->line_no !!}</td>
                        <td>{!! $item->item->name !!}</td>
                        <td>{!! $item->item->model->name !!}</td>
                        <td>{!! $item->item->size->size !!}</td>
                        <td>{!! $item->tr_weight !!}</td>
                        <td>{!! $item->lot_no !!}</td>
                        <td>{!! $item->bale_no !!}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
    @endisset


{{--    {!! Form::open(['url'=>'production/setCurrentProduction']) !!}--}}

    <div class="card">
{{--        <div class="card-header">SET NEW PRODUCTION PROPERTIES</div>--}}
        <div class="card-body">
            <table class="table table-bordered table-hover padding" >
                <thead>
                    <th width="20%">Line</th>
                    <th width="20%">Item Name</th>
                    <th>TR Weight</th>
                    <th>Lot No</th>
                    <th>Bale SL</th>
                    <th>Action</th>
                </thead>
                <tbody>
                <tr style="background-color: #afffff;">
                    <td>{!! Form::select('line_no', ['1'=>'Production Line One','2'=>'Production Line Two'], null, array('id' => 'line_no', 'class' => 'form-control')) !!}</td>
                    <td>{!! Form::select('product_id', $items, null, array('id' => 'product_id', 'class' => 'form-control')) !!}</td>
                    <td>{!! Form::text('tr_weight', 1.50, array('id' => 'tr_weight', 'class' => 'form-control')) !!}</td>
                    <td>{!! Form::text('lot_no', 100001 , array('id' => 'lot_no','class' => 'form-control','autocomplete'=>'off','maxlength'=>'6')) !!}</td>
                    <td>{!! Form::text('bale_sl', 1 , array('id' => 'bale_sl','class' => 'form-control','autocomplete'=>'off','maxlength'=>'2')) !!}</td>
                    <td>{!! Form::submit('SUBMIT',['class'=>'btn  btn-primary btn-submit form-control','id'=>'SUBMIT']) !!}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <br>

{{--    {!! Form::close() !!}--}}

@endsection
@push('scripts')

    <script>


        // $('#something').click(function() {
        //     location.reload();
        // });

        // $(document).on('click', '.btn-submit', function (e) {
        //
        //     $(this).attr("disabled", true);
        //     document.getElementsByName("grossWeight").clear();
        // });


        //    $(document).ready ( function () {
        //        //replace document below with enclosing container but below will work too
        //        $(document).on('click', '.btn-submit', function () {
        //            window.location.href = "home";
        //        });
        //    });



        $(document).on('click', '.btn-submit', function (e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = 'setCurrentProduction';
            // confirm then
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {method: '_POST', submit: true, lot_no:$('#lot_no').val(), line_no:$('#line_no').val(), product_id:$('#product_id').val(),tr_weight:$('#tr_weight').val(), bale_sl:$('#bale_sl').val() },

                error: function (request, status, error) {
                    alert(request.responseText);
                },

                success: function (data) {
                    $(".new-row").remove();

                    var trHTML = '';
                    $.each(data.output, function (i, item) {

                        trHTML += '<tr class="new-row">' +
                            '<td>' + item.line_no +'</td>' +
                            '<td>' + item.item.name +'</td>' +
                            '<td>' + item.item.model.name +'</td>' +
                            '<td>' + item.item.size.size +'</td>' +
                            '<td>' + item.tr_weight +'</td>' +
                            '<td align="right">' + item.lot_no +'</td>' +
                            '<td align="right">' + item.bale_no +'</td>' +
                            '</tr>';
                    });
//
                    $('#view-table').append(trHTML);

                    alert(data.success);
                }


            });
        });




        //    function showInput() {
        //
        //        document.getElementById("qtyIn").value = ((document.getElementById("grossWeight").value - document.getElementById("trWeight").value).toFixed(2));
        //
        //    }


    </script>

@endpush
