@extends('layouts.app')

@section('content')
    <script src="{!! asset('assets/js/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
                <li class="breadcrumb-item active">Production From Factory</li>
        </ol>
    </nav>

    <div class="col-sm-12 text-left col-sm-offset-1">
        <div class="controls">

            {!! Form::open(['url'=>'production/productionFromFactory','method' => 'POST']) !!}
            {{ csrf_field() }}

            <input type="hidden" name="relationship_id" value="12">


            {{--</div>--}}
            <div class="form-group col-md-12" style="background-color: rgba(177, 245, 174, 0.33)">
                {!! Form::label('items', 'Items', ['class' => 'control-label']) !!}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-fixed" id="items">
                        <thead>
                        <tr style="background-color: #f9f9f9;">
                            <th width="5%"  class="text-center">Action</th>
                            <th width="25%" class="text-left">Product</th>
                            <th width="15%" class="text-center">Quantity</th>
                            <th width="10%" class="text-center">Unit</th>
                            <th width="15%" class="text-center">Cost Price</th>
                            <th width="20%" class="text-right">Remarks</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $item_row = 0; ?>
                        <tr id="item-row-{{ $item_row }}">
                            <td class="text-center">
                                <button style="margin: 0 auto" type="button" onclick="$('#item-row-{{ $item_row }}').remove();" data-toggle="tooltip" title="delete-item" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                            </td>


{{--                            <td>--}}
{{--                                <input class="form-control typeahead position-relative" required="required" placeholder="Enter Product" name="item[{{ $item_row }}][name]" type="text" id="item-name-{{ $item_row }}" autocomplete="off">--}}
{{--                                <input name="item[{{ $item_row }}][item_id]" type="hidden" id="item-id-{{ $item_row }}">--}}
{{--                            </td>--}}

                            <td>
                                {!! Form::select("item[$item_row][product_id]", $products, null , array('class' => 'form-control','id'=>'product_id')) !!}
                            </td>

                            <td>
                                <input class="form-control text-center" required="required" name="item[{{ $item_row }}][quantity]" type="text" id="item-quantity-{{ $item_row }}">
                            </td>
                            <td id="item-unit-{{ $item_row }}">

                            </td>

                            <td>
                                <input class="form-control text-center" required="required" name="item[{{ $item_row }}][price]" type="text" value="0" id="item-price-{{ $item_row }}">
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
            html += '      <button style="margin: 0 auto" type="button" onclick="$(\'#item-row-' + item_row + '\').remove(); totalItem();" data-toggle="tooltip" title="{{ trans('general.delete') }}" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>';
            html += '  </td>';

            html += '  <td>';
            html += '      <select class="form-control select" name="item[' + item_row + '][product_id]" id="item-product-id-' + item_row + '">';
            @foreach($products as $for_key => $for_value)
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
            html += '      <input class="form-control text-center" required="required" name="item[' + item_row + '][price]" type="text" value="0" id="item-price-' + item_row + '">';
            html += '  </td>';

            html += '  <td>';
            html += '      <input class="form-control" name="item[' + item_row + '][remarks]" type="text" id="item-remarks-' + item_row + '">';
            html += '  </td>';

            $('#items tbody #addItem').before(html);
            //$('[rel=tooltip]').tooltip();

            $('[data-toggle="tooltip"]').tooltip('hide');

            item_row++;
        }

        $(document).on("focus", "input:text", function() {
            $(this).select();
        });

    </script>

@endpush
