@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Sale Items Rate</li>
        </ol>
    </nav>

    <div class="card">

        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead>
                <tr>
                    <th rowspan="2">Product Code</th>
                    <th rowspan="2">Product Name</th>
                    <th rowspan="2">Product Unit</th>
                    <th colspan="2" style="text-align: center">Current Rate</th>
                    <th colspan="2" style="text-align: center">New Rate</th>
                    <th rowspan="2" style="text-align: center">Action</th>
                </tr>
                <tr>
                    <th>Wholesale</th>
                    <th>Retail</th>
                    <th>Wholesale</th>
                    <th>Retail</th>
                </tr>

                </thead>
                <tbody>
                @foreach($products as  $i=> $item)

                    <tr style="background-color: {{ $i % 2 == 0 ? '#ffffff': '#afffff' }};">
                        <td>{{ $item->product_code }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->unit_name }}</td>
                        <td align="right">{{ $item->wholesale_price }}</td>
                        <td align="right">{{ $item->retail_price }}</td>
                        {{--                        @foreach($opnRate as $j=>$item)--}}
                        {{--                            @if($item->itemCode == $itemLists->itemCode)--}}

                        {{--                                <td>{!! Form::text('unitPrice[]', 'Unapproved Rate Exist', array('id' => 'unitPrice', 'class' => 'col-sm-12 control-text','readonly')) !!}</td>--}}
                        {{--                                <?php continue 2; ?>--}}
                        {{--                                --}}{{--@else--}}
                        {{--                                --}}{{--<td align = "right">{{ ($itemLists->avgPrice) }}</td>--}}

                        {{--                            @endif--}}
                        {{--                        @endforeach--}}
                        <td>{!! Form::text('wholesale', 0.00, array('id' => 'wholesale-'.$i, 'class' => 'form-control text-right')) !!}</td>
                        <td>{!! Form::text('retail', 0.00, array('id' => 'retail-'.$i, 'class' => 'form-control text-right')) !!}</td>
                        <td>
                            <button type="submit" id="product-{!! $i !!}" value="{!! $item->id !!}"
                                    class="btn btn-rate-update btn-primary btn-sm">Submit
                            </button>
                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>

        </div>

    </div>




@endsection

@push('scripts')
    <script>


        $(document).on('click', '.btn-rate-update', function () {
            // e.preventDefault();

            input_id = $(this).attr('id').split('-');
            item_id = parseInt(input_id[input_id.length - 1]);
            var $tr = $(this).closest('tr');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = 'updateProductRate';

            // confirm then
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {
                    method: '_POST', submit: true, row_id: $('#product-' + item_id).val(),
                    wholesale:$('#wholesale-' + item_id).val(), retail:$('#retail-' + item_id).val(),
                },
                error: function (request) {
                    alert(request.responseText);
                },
                success: function (data) {
                    alert(data.success);
                    $tr.find('td').fadeOut(1000, function () {
                        $tr.remove();
                    });
                },
            });
        });

        $(function (){
            $(document).on("focus", "input:text", function() {
                $(this).select();
            });
        });
    </script>
@endpush
