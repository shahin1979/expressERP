@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Item Purchase</li>
        </ol>
    </nav>

    <div class="container-fluid">
        {!! Form::open(['url'=>'purchase/reqPurchase','method'=>'POST']) !!}
        <div class="form-group col-md-12" style="background-color: rgba(177, 245, 174, 0.33)">
            {!! Form::label('items', 'Items', ['class' => 'control-label']) !!}
            <div class="table-responsive">
                <table class="table table-bordered" id="items">
                    <thead>
                    <tr style="background-color: #f9f9f9;">
                        <th width="25%" class="text-left">Product</th>
                        <th width="15%" class="text-left">Req Quantity</th>
                        <th width="15%" class="text-left">Supplier</th>
                        <th width="15%" class="text-center">Quantity</th>
                        <th width="15%" class="text-right">Unit Price</th>
                        <th width="15%" class="text-right">Tax</th>
                        <th width="15%" class="text-right">Sub Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $item_row = 0; ?>

                    @foreach($requisitions->items as $row)

                    <tr id="item-row-{{ $item_row }}">

                        <td>
                            {!! $row->item->name !!} {!! $item_row !!}
                            <input name="item[{{ $item_row }}][item_id]" type="hidden" id="item-id-{{ $item_row }}" value="{!! $row->product_id !!}">
                        </td>
                        <td>
                            {!! $row->quantity !!}
                        </td>

                        <td>
                            {!! Form::select('item[' . $item_row . '][supplier_id]',$suppliers , null, ['id'=> 'item-supplier-id-'. $item_row, 'class' => 'form-control', 'placeholder' => 'Please Select']) !!}
                        </td>

                        <td>
                            <input class="form-control text-right" required="required" name="item[{{ $item_row }}][quantity]" type="text" id="item-quantity-{{ $item_row }}" value="0">
                        </td>
                        <td>
                            <input class="form-control text-right" required="required" name="item[{{ $item_row }}][price]" type="text" id="item-price-{{ $item_row }}" value="0">
                        </td>
                        <td>
                            {!! Form::select('item[' . $item_row . '][tax]',$taxes , null, ['id'=> 'item-tax-'. $item_row, 'class' => 'form-control', 'placeholder' => 'Please Select']) !!}
                            <input name="item[{{ $item_row }}][tax_amt]" type="hidden" id="tax-amt-{{ $item_row }}" value="">
                        </td>
                        <td class="text-right" style="vertical-align: middle;">
                            <span id="item-total-{{ $item_row }}">0</span>
                        </td>
                    </tr>
                    <?php $item_row ++; ?>
                    @endforeach

                    <tr>
                        <td class="text-right" colspan="6"><strong>Purchase Sub Total</strong></td>
                        <td class="text-right"><span id="sub-total">0</span></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="6"><strong>General Taxes</strong></td>
                        <td class="text-right"><span id="tax-total">0</span></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="6"><strong>Purchase Toal</strong></td>
                        <td class="text-right"><span id="grand-total">0</span></td>
                        <input name="purchase_total" type="hidden" id="purchase-amt" value="">
                        <input name="total_tax" type="hidden" id="total-tax" value="">
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="6">{!! Form::submit('SUBMIT',['class'=>'btn btn-primary button-control pull-right']) !!}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {!! Form::close() !!}


    </div>






@endsection

@push('scripts')

    <script>

        $(document).on('change', '#items tbody select', function(){
            totalItem();
        });

        $(document).on('keyup', '#items tbody .form-control', function(){
            totalItem();
        });



        function totalItem() {
            $.ajax({
                url: '{{ url("purchase/itemSummary") }}',
                type: 'POST',
                dataType: 'JSON',
                data: $('#items input[type=\'text\'],#items input[type=\'hidden\'], #items select'),
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(data) {
                    if (data) {
                        $.each( data.items, function( key, value ) {
                            $('#item-total-' + key).html(value);
                        });

                        $.each( data.taxes, function( key, value ) {
                            $('#tax-amt-'+ key).val(value);
                        });

                        $('#sub-total').html(data.sub_total);
                        $('#tax-total').html(data.tax_total);
                        $('#total-tax').val(data.tax_total);
                        $('#grand-total').html(data.grand_total);
                        $('#purchase-amt').val(data.grand_total);

                    }
                }
            });
        }


        $(function (){
            $(document).on("focus", "input:text", function() {
                $(this).select();
            });
        });


    </script>

@endpush
