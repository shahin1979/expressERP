@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>
    <link href="{!! asset('assets/bootstrap4-toggle-3.6.1/css/bootstrap4-toggle.min.css') !!}" rel="stylesheet">
    <script src="{!! asset('assets/bootstrap4-toggle-3.6.1/js/bootstrap4-toggle.min.js') !!}"></script>
    <script src="{!! asset('assets/js/bootstrap3-typeahead.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Item Purchase</li>
        </ol>
    </nav>

    <div class="col-sm-12 text-left">

        {!! Form::open(['url'=>'purchase/direct/save','id'=>'save-all-rows']) !!}
        {{ csrf_field() }}
        <input name="temp_ref_no" type="hidden" id="temp_ref_no" value="{!! $temp_id !!}">

        <table class="table table-sm table-responsive">
            <tbody>
            <tr>
                <td><label for="relationship_id" class="control-label">Suppliers</label></td>
                <td>{!! Form::select('supplier_id', $suppliers, null , array('id' => 'supplier_id', 'class' => 'form-control')) !!}</td>
                <td><button type="button" class="btn btn-default btn-primary" data-toggle="modal" data-target="#modal-unit"><i class="fa fa-plus"></i></button></td>
                <td><label for="pdate" class="control-label">Date</label></td>
                <td>{!! Form::text('invoice_date', \Carbon\Carbon::now()->format('d-m-Y') , array('id' => 'invoice_date', 'class' => 'form-control','required','readonly')) !!}</td>
                <td align="right"><label for="orderNo" class="control-label">Bill No</label></td>
                <td align="right">{!! Form::text('contra_ref',null , array('id' => 'contra_ref', 'class' => 'form-control')) !!}</td>
            </tr>
            <tr>
                <td align="right"><label for="paid_amt" class="control-label">Payment</label></td>
                <td colspan="2" align="right">{!! Form::text('paid_amt',0.00 , array('id' => 'paid_amt', 'class' => 'form-control text-right','readonly')) !!}</td>
                <td align="right"><label for="discount" class="control-label">Discount</label></td>
                <td align="right">{!! Form::text('discount',0.00 , array('id' => 'discount', 'class' => 'form-control discount text-right')) !!}</td>
                <td align="right"><label for="due_amt" class="control-label">Due</label></td>
                <td class="text-center"><span id="due-amt">0</span></td>
                {{--                <td align="right">{!! Form::text('due_amt',0 , array('id' => 'due_amt', 'class' => 'form-control text-right')) !!}</td>--}}
            </tr>
            <tr>
                <td><label for="description" class="control-label">Remarks</label></td>
                <td colspan="5">{!! Form::text('description', null , array('id' => 'description', 'class' => 'form-control')) !!}</td>
                <td class="text-right">{!! $temp_id !!}</td>
            </tr>
            </tbody>
            <tfoot></tfoot>
        </table>


        <div class="form-group col-md-12" style="background-color: rgba(177, 245, 174, 0.33)">
            {!! Form::label('items', 'Items', ['class' => 'control-label']) !!}
            <div class="table-responsive">
                <table class="table table-bordered" id="items">
                    <thead>
                    <tr style="background-color: #f9f9f9;">
                        <th width="5%"  class="text-center">Action</th>
                        <th width="40%" class="text-left">Product</th>
                        <th width="10%" class="text-center">Quantity</th>
                        <th width="5%" class="text-center">UID?</th>
                        <th width="10%" class="text-right">Unit Price</th>
                        <th width="10%" class="text-right">Tax</th>
                        <th width="10%" class="text-right">Sub Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $item_row = 0; ?>
                    <tr id="item-row-{{ $item_row }}">
                        <td class="text-center" style="vertical-align: middle;">
                            <button type="button" onclick="$('#item-row-{{ $item_row }}').remove(); totalItem();" data-toggle="tooltip" title="{{ trans('general.delete') }}" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                        </td>
                        <td>
                            <input class="form-control typeahead position-relative" required="required" placeholder="Enter Product" name="item[{{ $item_row }}][name]" type="text" id="item-name-{{ $item_row }}" autocomplete="off">

                            <input name="item[{{ $item_row }}][item_id]" type="hidden" id="item-id-{{ $item_row }}">
                        </td>
                        <td>
                            <input class="form-control text-center" required="required" name="item[{{ $item_row }}][quantity]" type="text" id="item-quantity-{{ $item_row }}">
                        </td>
                        <td>
                            <button type="button" class="btn btn-xs btn-secondary btn-unique-id" id="item-unique-id-{{ $item_row }}">UID</button>
                        </td>

                        <td>
                            <input class="form-control text-right" required="required" name="item[{{ $item_row }}][price]" type="text" id="item-price-{{ $item_row }}">
                        </td>
                        <td>
                            {!! Form::select('item[' . $item_row . '][tax]',$taxes , null, ['id'=> 'item-tax-'. $item_row, 'class' => 'form-control']) !!}
                            <input name="item[{{ $item_row }}][tax_amt]" type="hidden" id="tax-amt-{{ $item_row }}" value="">
                        </td>
                        <td class="text-right" style="vertical-align: middle;">
                            <span id="item-total-{{ $item_row }}">0</span>
                        </td>
                    </tr>
                    <?php $item_row++; ?>
                    <tr id="addItem">
                        <td class="text-center"><button type="button" onclick="addItem();" data-toggle="tooltip" title="{{ trans('general.add') }}" class="btn btn-xs btn-primary" data-original-title="{{ trans('general.add') }}"><i class="fa fa-plus"></i></button></td>
                        <td class="text-right" colspan="6"></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="6"><strong>Purchase Sub Total</strong></td>
                        <td class="text-right"><span id="sub-total">0</span></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="6"><strong>{{ trans_choice('general.taxes', 1) }}</strong></td>
                        <td class="text-right"><span id="tax-total">0</span></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="6"><strong>Purchase Total</strong></td>
                        <td class="text-right"><span id="grand-total">0</span></td>
                    </tr>
                    </tbody>

                    <input name="invoice_amt" type="hidden" id="purchase-amt" value="">
                    <input name="total_tax" type="hidden" id="total-tax" value="">


                    <tfoot>
                    <tr>
                        <td>{!! Form::submit('SUBMIT',['class'=>'btn btn-primary button-control pull-right']) !!}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    {!! Form::close() !!}
    </div>

    <!-- Modal -->

    <div class="modal fade" id="product-uid" tabindex="-1" role="dialog" aria-labelledby="product-uid-title" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="prod_name" class="modal-title font-weight-bold colored" style="color: darkred">Identification No For : </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="ajax-items"  method="POST">
                    <div class="modal-body">

                        <table class="table" id="unique-id">
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" name="unique_prod_id" id="unique_prod_id" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-save-id">Save IDs</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <script type="text/javascript">
        var item_row = '{{ $item_row }}';

        function addItem() {
            html  = '<tr id="item-row-' + item_row + '">';
            html += '  <td class="text-center" style="vertical-align: middle;">';
            html += '      <button type="button" onclick="$(\'#item-row-' + item_row + '\').remove(); totalItem();" data-toggle="tooltip" title="{{ trans('general.delete') }}" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>';
            html += '  </td>';
            html += '  <td>';
            html += '      <input class="form-control typeahead position-relative" required="required" placeholder="Product" name="item[' + item_row + '][name]" type="text" id="item-name-' + item_row + '" autocomplete="off">';
            html += '      <input name="item[' + item_row + '][item_id]" type="hidden" id="item-id-' + item_row + '">';
            html += '  </td>';

            html += '  <td>';
            html += '      <input class="form-control text-center" required="required" name="item[' + item_row + '][quantity]" type="text" id="item-quantity-' + item_row + '">';
            html += '  </td>';

            html += '  <td>';
            html += '     <button type="button" class="btn btn-xs btn-secondary btn-unique-id" id="item-unique-id-' + item_row + '">UID</button>';
            html += '  </td>';

            html += '  <td>';
            html += '      <input class="form-control text-right" required="required" name="item[' + item_row + '][price]" type="text" id="item-price-' + item_row + '">';
            html += '  </td>';

            html += '  <td>';
            html += '      <select class="form-control select" name="item[' + item_row + '][tax]" id="item-tax-' + item_row + '">';

            @foreach($taxes as $tax_key => $tax_value)
                html += '         <option value="{{ $tax_key }}">{{ $tax_value }}</option>';
            @endforeach
                html += '      </select>';

            html += '      <input name="item[' + item_row + '][tax_amt]" type="hidden" id="tax-amt-' + item_row + '" >';
            html += '  </td>';

            html += '  <td class="text-right" style="vertical-align: middle;">';
            html += '      <span id="item-total-' + item_row + '">0</span>';
            html += '  </td>';

            $('#items tbody #addItem').before(html);
            //$('[rel=tooltip]').tooltip();

            $('[data-toggle="tooltip"]').tooltip('hide');

                item_row++;
        }

        var autocomplete_path = "{{ url('purchase/productList') }}";

        $(document).on('click', '.form-control.typeahead', function() {
            input_id = $(this).attr('id').split('-');

            item_id = parseInt(input_id[input_id.length-1]);

            $(this).typeahead({
                minLength: 1,
                displayText:function (data) {
                    return data.name;
                },
                source: function (query, process) {
                    $.ajax({
                        url: autocomplete_path,
                        type: 'GET',
                        dataType: 'JSON',
                        data: 'query=' + query ,
                        success: function(data) {
                            return process(data);
                        }
                    });
                },
                afterSelect: function (data) {
                    $('#item-id-' + item_id).val(data.item_id);
                    $('#item-quantity-' + item_id).val('1');
                    $('#item-price-' + item_id).val(data.unit_price);
                    $('#item-tax-' + item_id).val(data.tax_id);
                    totalItem();
                }
            });
        });


        $(document).on('change', '#items tbody select', function(){
            totalItem();
        });

        $(document).on('change', '#supplier_id', function(){
            totalItem();
        });



        $(document).on('keyup', '#items tbody .form-control', function(){
            totalItem();
        });

        $(document).on('keyup', ' .form-control.discount', function(){
            totalItem();
        });

        // Product Unique ID
        var button_id = item_row -1;

        $(function() {
            $(document).on('click', '.btn-unique-id', function() {

                input_id = $(this).attr('id').split('-');
                item_id = parseInt(input_id[input_id.length-1]);

                if($('#item-id-'+ item_id).val() === '') {
                    $.alert({
                        title: 'Alert!',
                        content: 'Please Select Product & Quantity First',
                    });

                    return false;
                }

                $('#prod_name').html($('#item-name-' + item_id).val());
                $('#unique_prod_id').val($('#item-id-' + item_id).val());


                var trdHTML = '';
                $(".item_ids").remove();

                    var count = $('#item-quantity-'+ item_id).val();
                    var i;
                    for(i=0; i<count; i++)
                    {
                        trdHTML += '<tr class="item_ids">' +
                            '<td>Unique ID</td>' +
                            '<td align="right"><input name="product[' + i + '][unique_code]" class="form-control text-right" type="text"></td>' +
                            '</tr>';
                    }
                    $('#unique-id').append(trdHTML);
                    $('#product-uid').modal('show')

                    // $('#product-uid').show();

            })
        })

        $('#ajax-items').submit(function(e) {
            // Stop the browser from submitting the form.
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = 'saveProductsUniqueId/'+ $('#temp_ref_no').val();

            // confirm then
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: $('#ajax-items').serialize(),
                error: function (request, status, error) {
                    var myObj = JSON.parse(request.responseText);

                    $.alert({
                        title: 'Alert!',
                        content: myObj.message + ' ' + myObj.error,
                    });
                },

                success: function (data) {
                    $('#product-uid').modal('hide');
                },
            });
        });


        // $(document).on('click', '.btn-save-id', function() {
        //     // $('#item-unique-'+ button_id).bootstrapToggle('off')
        //     $('#product-uid').hide();
        // })

        $(document).on('click', '.btn-modal-dismiss', function() {
            // $('#item-unique-'+ button_id).bootstrapToggle('off')
            $('#product-uid').hide();
        })



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

                        if($('#supplier_id').val() === 1)
                        {
                            // document.getElementById("paid_amt").readOnly = true;
                            var payment = data.grand_total - $('#discount').val();
                            $('#paid_amt').val(payment);
                        }

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
