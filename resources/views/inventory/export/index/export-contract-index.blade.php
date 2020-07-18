@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="{!! asset('assets/js/jquery-3.3.1.min.js') !!}"></script>
    <script src="{!! asset('assets/js/bootstrap3-typeahead.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Export Contract</li>
        </ol>
    </nav>

    <div class="container-fluid">

        {!! Form::open(['url'=>'export/saveExportContract','method'=>'POST']) !!}

        <table width="95%" class="table table-sm table-responsive">
            <tbody>
            <tr>
                <td><label for="customer_id" class="control-label">Customer</label></td>
                <td>{!! Form::select('customer_id', $customers , null , array('id' => 'customer_id', 'class' => 'form-control')) !!}</td>
                <td><label for="contract_date" class="control-label">Contract Date</label></td>
                <td>{!! Form::text('contract_date', \Carbon\Carbon::now()->format('d-m-Y') , array('id' => 'contract_date', 'class' => 'form-control','required','readonly')) !!}</td>
                <td align="right"><label for="export_contract_no" class="control-label">Contract No</label></td>
                <td align="right">{!! Form::text('export_contract_no',null , array('id' => 'export_contract_no', 'class' => 'form-control')) !!}</td>
            </tr>

            <tr>
                <td><label for="signing_date" class="control-label">Signing Date</label></td>
                <td>{!! Form::text('signing_date', \Carbon\Carbon::now()->format('d-m-Y') , array('id' => 'signing_date', 'class' => 'form-control','required','readonly')) !!}</td>
                <td><label for="expiry_date" class="control-label">Expiry Date</label></td>
                <td>{!! Form::text('expiry_date', \Carbon\Carbon::now()->format('d-m-Y') , array('id' => 'expiry_date', 'class' => 'form-control','required','readonly')) !!}</td>
                <td><label for="t_limit" class="control-label">Tolerance Limit (%)</label></td>
                <td align="right">{!! Form::text('tolerance_limit',null , array('id' => 'tolerance_limit', 'class' => 'form-control')) !!}</td>
            </tr>


            <tr>
                <td><label for="loading_port" class="control-label">Port of Loading</label></td>
                <td>{!! Form::text('loading_port', 'Chittagong Sea Port, Bangladesh' , array('id' => 'loading_port', 'class' => 'form-control')) !!}</td>
                <td><label for="dest_port" class="control-label">Port of Destination</label></td>
                <td>{!! Form::text('dest_port', 'Shanghai Port, China' , array('id' => 'dest_port', 'class' => 'form-control')) !!}</td>

                <td><label for="shipment_time" class="control-label">Time of Shipment(Days)</label></td>
                <td>{!! Form::text('shipment_time', null , array('id' => 'shipment_time', 'class' => 'form-control')) !!}</td>

            </tr>

            <tr>
                <td><label for="currency" class="control-label">Description</label></td>
                <td>{!! Form::select('currency', $currencies , null , array('id' => 'currency', 'class' => 'form-control')) !!}</td>
                <td><label for="description" class="control-label">Description</label></td>
                <td colspan="4">{!! Form::textarea('description', null , ['id'=>'description','size' => '65x2','class'=>'field']) !!}</td>
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
                        <th width="15%" class="text-center">Quantity</th>
                        <th width="10%" class="text-right">Unit Price</th>
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
                            {{--<input class="form-control typeahead" required="required" placeholder="{{ trans('general.form.enter', ['field' => trans_choice('invoices.item_name', 1)]) }}" name="item[{{ $item_row }}][name]" type="text" id="item-name-{{ $item_row }}" autocomplete="off">--}}
                            <input class="form-control typeahead position-relative" required="required" placeholder="{{ trans('general.form.enter', ['field' => trans_choice('purchase.item_name', 1)]) }}" name="item[{{ $item_row }}][name]" type="text" id="item-name-{{ $item_row }}" autocomplete="off">
                            <input name="item[{{ $item_row }}][item_id]" type="hidden" id="item-id-{{ $item_row }}">
                        </td>
                        <td>
                            <input class="form-control text-center" required="required" name="item[{{ $item_row }}][quantity]" type="text" id="item-quantity-{{ $item_row }}">
                        </td>
                        <td>
                            <input class="form-control text-right" required="required" name="item[{{ $item_row }}][price]" type="text" id="item-price-{{ $item_row }}">
                        </td>

                        <td class="text-right" style="vertical-align: middle;">
                            <span id="item-total-{{ $item_row }}">0</span>
                        </td>
                    </tr>
                    <?php $item_row++; ?>
                    <tr id="addItem">
                        <td class="text-center"><button type="button" onclick="addItem();" data-toggle="tooltip" title="{{ trans('general.add') }}" class="btn btn-xs btn-primary" data-original-title="{{ trans('general.add') }}"><i class="fa fa-plus"></i></button></td>
                        <td class="text-right" colspan="4"></td>
                    </tr>

                    <tr>
                        <td class="text-right" colspan="4"><strong>Sales Total</strong></td>
                        <td class="text-right"><span id="grand-total">0</span></td>
                    </tr>
                    <input name="contract_amt" type="hidden" id="contract-amt" value="">

                    </tbody>
                    <tfoot>
                    <tr>
                        <td>{!! Form::submit('SUBMIT',['class'=>'btn btn-primary button-control pull-right']) !!}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {!! Form::close() !!}
    </div> <!--/.Container-->


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
            html += '      <input class="form-control typeahead position-relative" required="required" placeholder="{{ trans('general.form.enter', ['field' => trans_choice('purchase.item_name', 1)]) }}" name="item[' + item_row + '][name]" type="text" id="item-name-' + item_row + '" autocomplete="off">';
            html += '      <input name="item[' + item_row + '][item_id]" type="hidden" id="item-id-' + item_row + '">';
            html += '  </td>';
            html += '  <td>';
            html += '      <input class="form-control text-center" required="required" name="item[' + item_row + '][quantity]" type="text" id="item-quantity-' + item_row + '">';
            html += '  </td>';

            html += '  <td>';
            html += '      <input class="form-control text-right" required="required" name="item[' + item_row + '][price]" type="text" id="item-price-' + item_row + '">';
            html += '  </td>';

            html += '  <td class="text-right" style="vertical-align: middle;">';
            html += '      <span id="item-total-' + item_row + '">0</span>';
            html += '  </td>';

            $('#items tbody #addItem').before(html);

            $('[data-toggle="tooltip"]').tooltip('hide');

            item_row++;
        }


        var autocomplete_path = "{{ url('export/exportProducts') }}";

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
                    totalItem();
                }
            });
        });

        $(document).on('change', '#items tbody select', function(){
            totalItem();
        });

        $(document).on('keyup', '#items tbody .form-control', function(){
            totalItem();
        });


        function totalItem() {
            $.ajax({
                url: '{{ url("sales/totalItem") }}',
                type: 'POST',
                dataType: 'JSON',
                data: $('#items input[type=\'text\'],#items input[type=\'hidden\'], #items textarea, #items select'),
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(data) {
                    if (data) {
                        $.each( data.items, function( key, value ) {
                            $('#item-total-' + key).html(value);
                        });

                        $('#grand-total').html(data.grand_total);
                        $('#contract-amt').val(data.grand_total);
                    }
                }
            });
        }

        $(document).ready(function(){

            $( "#contract_date" ).datetimepicker({
                format:'d-m-Y',
                timepicker: false,
                closeOnDateSelect: true,
                scrollInput : false,
                inline:false
            });

            $( "#signing_date" ).datetimepicker({
                format:'d-m-Y',
                timepicker: false,
                closeOnDateSelect: true,
                scrollInput : false,
                inline:false
            });

            $( "#expiry_date" ).datetimepicker({
                format:'d-m-Y',
                timepicker: false,
                closeOnDateSelect: true,
                scrollInput : false,
                inline:false
            });

        });



        $(function (){
            $(document).on("focus", "input:text", function() {
                $(this).select();
            });
        });


    </script>

@endpush
