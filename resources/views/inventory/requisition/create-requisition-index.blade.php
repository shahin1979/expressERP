@extends('layouts.app')

@section('content')
    <script src="{!! asset('assets/js/jquery-3.3.1.min.js') !!}"></script>
    <script src="{!! asset('assets/js/bootstrap3-typeahead.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Requisition</li>
        </ol>
    </nav>

    <div class="col-sm-12 text-left col-sm-offset-1">
        <div class="controls">

            {!! Form::open(['url'=>'requisition/createReqIndex','method' => 'POST']) !!}
            {{ csrf_field() }}

            {{--<div class="row col-md-6 col-md-offset-1" style="border-right: solid">--}}
            <table class="table table-sm table-responsive table-primary">
                <tbody>
                <tr>
                    <td><label for="req_type" class="control-label">Requisition Type</label></td>
                    <td>{!! Form::select('req_type', array('0' => 'Please Select', 'P' => 'Purchase', 'C' => 'Consumption'), null , array('id' => 'req_type', 'class' => 'form-control')) !!}</td>
                    <td><label for="req_date" class="control-label">Date</label></td>
                    <td>{!! Form::text('req_date', \Carbon\Carbon::now()->format('d-m-Y') , array('id' => 'req_date', 'class' => 'form-control','required','readonly')) !!}</td>
                    <td><label for="refno" class="control-label">Requisition No</label></td>
                    <td>{!! Form::text('ref_no','RQ' , array('id' => 'ref_no', 'class' => 'form-control')) !!}</td>
                </tr>
{{--                <tr>--}}
{{--                    <td><label for="requisition_for" class="control-label">Requisition For</label></td>--}}
{{--                    <td>{!! Form::select('requisition_for', $locations, null , array('id' => 'requisition_for', 'class' => 'form-control')) !!}</td>--}}
{{--                    --}}
{{--                </tr>--}}
                </tbody>
                <tfoot></tfoot>
            </table>

            {{--</div>--}}
            <div class="form-group col-md-12" style="background-color: rgba(177, 245, 174, 0.33)">
                {!! Form::label('items', 'Items', ['class' => 'control-label']) !!}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-fixed" id="items">
                        <thead>
                        <tr style="background-color: #f9f9f9;">
                            <th width="5%"  class="text-center">Action</th>
                            <th width="25%" class="text-left">Product</th>
                            <th width="25%" class="text-left">Requisition For</th>
                            <th width="15%" class="text-center">Quantity</th>
                            <th width="10%" class="text-center">Unit</th>
                            <th width="20%" class="text-right">Remarks</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $item_row = 0; ?>
                        <tr id="item-row-{{ $item_row }}">
                            <td class="text-center">
                                <button style="margin: 0 auto" type="button" onclick="$(this).tooltip('destroy'); $('#item-row-{{ $item_row }}').remove();" data-toggle="tooltip" title="delete-item" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                            </td>


                            <td>
                                <input class="form-control typeahead position-relative" required="required" placeholder="Enter Product" name="item[{{ $item_row }}][name]" type="text" id="item-name-{{ $item_row }}" autocomplete="off">
                                <input name="item[{{ $item_row }}][item_id]" type="hidden" id="item-id-{{ $item_row }}">
                            </td>

                            <td>
                                {!! Form::select("item[$item_row][requisition_for]", $costs, null , array('class' => 'form-control')) !!}
                            </td>

                            <td>
                                <input class="form-control text-center" required="required" name="item[{{ $item_row }}][quantity]" type="text" id="item-quantity-{{ $item_row }}">
                            </td>
                            <td id="item-unit-{{ $item_row }}">

                            </td>
                            <td>
                                <input class="form-control" name="item[{{ $item_row }}][remarks]" type="text" id="item-remarks-{{ $item_row }}">
                            </td>
                        </tr>
                        <?php $item_row++; ?>
                        <tr id="addItem">
                            <td class="text-center"><button style="margin: 0 auto" type="button" onclick="addItem();" data-toggle="tooltip" title="add new item" class="btn btn-xs btn-primary" data-original-title="{{ trans('general.add') }}"><i class="fa fa-plus"></i></button></td>
                            <td class="text-right" colspan="5"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>


        <div class="col-md-12">
            {!! Form::submit('SUBMIT',['class'=>'btn btn-primary btn-create button-control pull-right']) !!}
        </div>
        {!! Form::close() !!}

    </div>



@endsection

@push('scripts')
    <script type="text/javascript">
        var item_row = '{{ $item_row }}';

        function addItem() {
            html  = '<tr id="item-row-' + item_row + '">';

            html += '  <td class="text-center" style="vertical-align: middle;">';
            html += '      <button style="margin: 0 auto" type="button" onclick="$(this).tooltip(\'destroy\'); $(\'#item-row-' + item_row + '\').remove(); totalItem();" data-toggle="tooltip" title="{{ trans('general.delete') }}" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>';
            html += '  </td>';

            html += '  <td>';
            html += '      <input class="form-control typeahead position-relative" required="required" autocomplete="off" placeholder="{{ trans('Product Name', ['field' => trans_choice('invoices.item_name', 1)]) }}" name="item[' + item_row + '][name]" type="text" id="item-name-' + item_row + '">';
            html += '      <input name="item[' + item_row + '][item_id]" type="hidden" id="item-id-' + item_row + '">';
            html += '  </td>';



            html += '  <td>';
            html += '      <select class="form-control select" name="item[' + item_row + '][requisition_for]" id="item-requisition-' + item_row + '">';
            // html += '         <option selected="selected" value="">Please Select</option>';
            @foreach($costs as $for_key => $for_value)
                html += '         <option value="{{ $for_key }}">{{ $for_value }}</option>';
            @endforeach
                html += '      </select>';
            html += '  </td>';


            html += '  <td>';
            html += '      <input class="form-control text-center" required="required" name="item[' + item_row + '][quantity]" type="text" id="item-quantity-' + item_row + '">';
            html += '  </td>';

            html += '  <td id="item-unit-' + item_row + '">';
            html += '  </td>';


            html += '  <td>';
            html += '      <input class="form-control" name="item[' + item_row + '][remarks]" type="text" id="item-remarks-' + item_row + '">';
            html += '  </td>';

            $('#items tbody #addItem').before(html);
            //$('[rel=tooltip]').tooltip();

            $('[data-toggle="tooltip"]').tooltip('hide');

            item_row++;
        }

        $(document).ready(function(){
            //Date picker
            $('#req_date').datepicker({
                format: 'dd-mm-yy',
                autoclose: true
            });
        });


        var autocomplete_path = "{{ url('requisition/productList') }}";

        $(document).on('click', '.form-control.typeahead', function() {

            input_id = $(this).attr('id').split('-');
            item_id = parseInt(input_id[input_id.length-1]);

            $(this).typeahead({
                minLength: 2,
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
                    $('#item-unit-' + item_id).html(data.unit_name);
                }
            });
        });

        // Check Requisition For Is Selected

        $(document).on('click', '.btn-create', function (e) {
            if($('#req_type').val() == 0)
            {
                alert('Please Select Requisition Type');
                return false
            }

            return true;
        });

        ///


        $(function (){
            $(document).on("focus", "input:text", function() {
                $(this).select();
            });
        });


    </script>
@endpush
