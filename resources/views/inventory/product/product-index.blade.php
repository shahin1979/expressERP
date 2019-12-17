@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>
    <link href="{!! asset('assets/bootstrap4-toggle-3.6.1/css/bootstrap4-toggle.min.css') !!}" rel="stylesheet">
    <script src="{!! asset('assets/bootstrap4-toggle-3.6.1/js/bootstrap4-toggle.min.js') !!}"></script>

    <link href="{!! asset('assets/css/bootstrap-imageupload.css') !!}" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="{!! asset('assets/js/bootstrap-imageupload.js') !!}"></script>



    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Product</li>
        </ol>
    </nav>

    {{--    <div class="justify-content-center">--}}
    {{--        <img src="{!! asset('assets/images/page-under-construction.jpg') !!}" class="img-responsive">--}}
    {{--    </div>--}}

    <div class="container-fluid">

        <div class="row" id="top-head">

            <div class="col-md-4">
                <div class="pull-left">
                    <button type="button" class="btn btn-product-add btn-primary"><i class="fa fa-plus"></i>New Product</button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="pull-right">
                    <button type="button" class="btn btn-print btn-primary"><i class="fa fa-print"></i>Print</button>
                </div>
            </div>
        </div>


        <div class="row col-md-12 dataTables_wrapper" style="overflow-x:auto;">
            <table class="table table-bordered table-hover table-responsive" id="products-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Code</th>
                    <th>Category</th>
                    <th>Sub Category</th>
                    <th>Name</th>
                    <th>Unit</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>

        @include('inventory.product.partials.new-product-form')
        @include('inventory.product.partials.edit-product-form')

{{--        {!! Form::open(['url'=>'product/itemUnitIndex','method'=>'POST']) !!}--}}

{{--        <div id="new-unit" class="col-md-8">--}}
{{--            <table width="50%" class="table table-bordered table-striped table-hover">--}}
{{--                <tbody>--}}
{{--                <tr>--}}
{{--                    <td><label for="name" class="control-label">Unit Name</label></td>--}}
{{--                    <td><input id="name" type="text" class="form-control" name="name" value="" required autofocus></td>--}}
{{--                </tr>--}}

{{--                <tr>--}}
{{--                    <td><label for="formal_name" class="control-label">Unit Formal Name</label></td>--}}
{{--                    <td><input id="formal_name" type="text" class="form-control" name="formal_name" value=""></td>--}}
{{--                </tr>--}}

{{--                <tr>--}}
{{--                    <td><label for="no_of_decimal_places" class="control-label">No of decimal Places</label></td>--}}
{{--                    <td><input id="no_of_decimal_places" type="text" class="form-control" name="no_of_decimal_places" value=""></td>--}}
{{--                </tr>--}}

{{--                </tbody>--}}

{{--                <tfoot>--}}
{{--                <tr>--}}
{{--                    <td colspan="4"><button type="submit" id="btn-unit-save" class="btn btn-primary btn-unit-save">Submit</button></td>--}}
{{--                </tr>--}}
{{--                </tfoot>--}}

{{--            </table>--}}
{{--        </div>--}}
{{--        {!! Form::close() !!}--}}

        {{--        <form action="#"  method="post" accept-charset="utf-8">--}}
{{--        <div id="edit-product" class="col-md-8">--}}
{{--            <table width="50%" class="table table-bordered table-striped table-hover">--}}
{{--                <tbody>--}}
{{--                <tr>--}}
{{--                    <td><label for="name" class="control-label">Unit Name</label></td>--}}
{{--                    <td><input id="name-for-edit" type="text" class="form-control" name="name-for-edit" value="" required autofocus></td>--}}
{{--                </tr>--}}

{{--                <tr>--}}
{{--                    <td><label for="name" class="control-label">Formal Name</label></td>--}}
{{--                    <td><input id="formal-name-for-edit" type="text" class="form-control" name="formal-name-for-edit" value=""></td>--}}
{{--                </tr>--}}

{{--                <tr>--}}
{{--                    <td><label for="name" class="control-label">Decimal Places</label></td>--}}
{{--                    <td><input id="decimal-for-edit" type="text" class="form-control" name="decimal-for-edit" value="" required></td>--}}
{{--                </tr>--}}

{{--                </tbody>--}}
{{--                <input id="id-for-update" type="hidden" name="id-for-update"/>--}}
{{--                <tfoot>--}}
{{--                <tr>--}}
{{--                    <td colspan="4"><button type="submit" id="btn-unit-update" class="btn btn-primary btn-unit-update">Submit</button></td>--}}
{{--                </tr>--}}
{{--                </tfoot>--}}

{{--            </table>--}}
{{--        </div>--}}
        {{--        </form>--}}

    </div>

@endsection

@push('scripts')

    <script>

//Generate Auto sku
        $(function() {
            $('#auto_sku').change(function(e) {
                if($(this).is(":checked"))
                {
                    e.preventDefault();

                    var url = 'itemSKU';

                    // confirm then
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',

                        data: {
                            method: '_GET', submit: true, category:$("#category_id :selected").text(),
                        },

                        error: function (request, status, error) {
                            alert(request.responseText);
                        },

                        success: function (data) {
                            $('#sku').val(data);
                        }
                    });
                }else{
                    $('#sku').val("");
                }
            })
        });


        $('.imageupload').imageupload({
            allowedFormats: [ "jpg", "jpeg", "png" ],
            previewWidth: 250,
            previewHeight: 250,
            maxFileSizeKb: 2048
        });



        $(document).ready(function(){

            $('#new-product').hide();
            $('#edit-product').hide();

        });

        $(function() {
            var table= $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'getProductDBData',
                columns: [
                    { data: 'product_code', name: 'product_code' },
                    { data: 'category.name', name: 'category.name' },
                    { data: 'subcategory.name', name: 'subcategory.name' },
                    { data: 'name', name: 'name' },
                    { data: 'unit_name', name: 'unit_name' },
                    { data: 'on_hand', name: 'on_hand' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
                ]
            });

            $(this).on('click', '.btn-product-edit', function (e) {

                // $('#name-for-edit').val($(this).data('name'));
                // $('#formal-name-for-edit').val($(this).data('formal'));
                // $('#decimal-for-edit').val($(this).data('decimal'));
                // $('#id-for-update').val($(this).data('rowid'));

                $('#edit-product').show();
                $('#products-table').parents('div.dataTables_wrapper').first().hide();
                $('#top-head').hide();
            });


            $(this).on('click', '.btn-unit-delete', function (e) {

                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var url = $(this).data('remote');
                // confirm then
                $.ajax({
                    beforeSend: function (request) {
                        return confirm("Are you sure?");
                    },
                    url: url,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {method: '_DELETE', submit: true},

                    error: function (request, status, error) {
                        alert(request.responseText);
                    },

                }).always(function (data) {
                    $('#products-table').DataTable().draw(true);
                })
            });


        });



        $(document).on('click', '.btn-product-add', function (e) {
            $('#new-product').show();
            $('#products-table').parents('div.dataTables_wrapper').first().hide();
            $('#top-head').hide();
        });



        $(document).on('click', '.btn-unit-update', function (e) {
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = 'itemUnit/update/' + $('#id-for-update').val();

            // confirm then
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',

                data: {method: '_POST', submit: true, name:$('#name-for-edit').val(),
                    formal_name:$('#formal-name-for-edit').val(),
                    no_of_decimal_places:$('#decimal-for-edit').val(),
                },

                error: function (request, status, error) {
                    alert(request.responseText);
                },

                success: function (data) {
                    $('#edit-unit').hide();
                    $('#products-table').DataTable().draw(false);
                    $('#top-head').show();
                    $('#products-table').parents('div.dataTables_wrapper').first().show();

                }

            });
        });

    </script>

@endpush
