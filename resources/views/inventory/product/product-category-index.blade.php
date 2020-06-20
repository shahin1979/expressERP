@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>
    <link href="{!! asset('assets/bootstrap4-toggle-3.6.1/css/bootstrap4-toggle.min.css') !!}" rel="stylesheet">
    <script src="{!! asset('assets/bootstrap4-toggle-3.6.1/js/bootstrap4-toggle.min.js') !!}"></script>



        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
                <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
                <li class="breadcrumb-item active">Product Category</li>
            </ol>
        </nav>

{{--    <div class="justify-content-center">--}}
{{--        <img src="{!! asset('assets/images/page-under-construction.jpg') !!}" class="img-responsive">--}}
{{--    </div>--}}

    <div class="container-fluid">

        <div class="row" id="top-head">

            <div class="col-md-4">
                <div class="pull-left">
                    <button type="button" class="btn btn-category-add btn-primary"><i class="fa fa-plus"></i>New Category</button>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pull-right">
                    <button type="button" class="btn btn-print btn-primary"><i class="fa fa-print"></i>Print</button>
                </div>
            </div>
        </div>


        <div class="row col-md-12 dataTables_wrapper" style="overflow-x:auto;">
            <table class="table table-bordered table-hover table-responsive" id="categories-table">
                <thead style="background-color: #b0b0b0">
                    <tr>
                        <th>Name</th>
{{--                        <th>Ledger Code </th>--}}
                        <th>Stock Value</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="col-md-5" id="back-section">
            <div class="pull-left">
                <button type="button" id="btn-back" class="btn btn-primary btn-back"><i class="fa fa-backward"></i>Back</button>
            </div>
        </div>

        {!! Form::open(['url'=>'product/categoryIndex','method'=>'POST']) !!}
        <div id="new-category" class="col-md-8">



            <table width="50%" class="table table-bordered table-striped table-hover">
                <tbody>
                <tr>
                    <td><label for="name" class="control-label">Category Name</label></td>
                    <td><input id="name" type="text" class="form-control" name="name" value="" required autofocus></td>
                </tr>
                <tr>
                    <td><label for="group_name">Has Sub Category ?</label></td>
                    <td><input id="has_sub" type="checkbox" name="sub_category" data-toggle="toggle" data-onstyle="primary" data-on="Yes" data-off="No" checked></td>
                </tr>

{{--                @if($comp_modules->contains('module_id',4))--}}
                    <div id="account">
                        <tr class="acc_in">
                            <td><label for="acc_in_stock">Stock In Account No</label></td>
                            <td><input id="acc_in_stock" type="text" class="form-control" name="acc_in_stock" value=""></td>
                        </tr>

                        <tr class="acc_out">
                            <td><label for="acc_out_stock">Stock Out Account</label></td>
                            <td><input id="acc_out_stock" type="text" class="form-control" name="acc_out_stock" value=""></td>
                        </tr>
                    </div>
{{--                @endif--}}

                </tbody>

                <tfoot>
                <tr>
                    <td colspan="4"><button type="submit" id="btn-category-save" class="btn btn-primary btn-category-save">Submit</button></td>
                </tr>
                </tfoot>

            </table>
        </div>
        {!! Form::close() !!}

{{--        <form action="#"  method="post" accept-charset="utf-8">--}}
        <div id="edit-category" class="col-md-8">
            <table width="50%" class="table table-bordered table-striped table-hover">
                <tbody>
                    <tr>
                        <td><label for="name_for_edit" class="control-label">Category Name</label></td>
                        <td><input id="name_for_edit" type="text" class="form-control" name="name_for_edit" value="" required autofocus></td>
                    </tr>

                </tbody>
                <input id="id_for_update" type="hidden" name="id_for_update"/>
                <tfoot>
                <tr>
                    <td colspan="4"><button type="submit" id="btn-category-update" class="btn btn-primary btn-category-update">Submit</button></td>
                </tr>
                </tfoot>

            </table>
        </div>
{{--        </form>--}}

    </div>

@endsection

@push('scripts')

    <script>

        $('.acc_in').hide();
        $('.acc_out').hide();


        $(function() {
            $('#has_sub').change(function() {
                if ($('#has_sub').is(":checked"))
                {
                    $('.acc_in').hide();
                    $('.acc_out').hide();
                }else{
                    $('.acc_in').show();
                    $('.acc_out').show();
                }
            })
        })


        $(document).ready(function(){

            $('#new-category').hide();
            $('#edit-category').hide();
            // $('#back-section').hide();
        });

        $(function() {
            var table= $('#categories-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'getCategoryData',
                columns: [
                    { data: 'name', name: 'name' },
                    // { data: 'stock_in_acc', name: 'stock_in_acc' },
                    // { data: 'stock_out_acc', name: 'stock_out_acc' },
                    { data: 'acc_balance', name: 'acc_balance' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
                ]
            });

            $(this).on('click', '.btn-category-edit', function (e) {

                $('#name_for_edit').val($(this).data('name'));
                // $('#acc_no_for_edit').val($(this).data('acc-no'));
                $('#id_for_update').val($(this).data('rowid'));

                // $(this).data('sub') == true ? $('#sub_category_for_edit').bootstrapToggle('on') : '';
                // $(this).data('sub') == true ? $("#sub_category_for_edit").prop("checked", true) : '';

                $('#edit-category').show();
                $('#back-section').show();
                $('#categories-table').parents('div.dataTables_wrapper').first().hide();
                $('#top-head').hide();
            });


            $(this).on('click', '.btn-category-delete', function (e) {

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
                        var myObj = JSON.parse(request.responseText);
                        alert(myObj.error);
                    },

                }).always(function (data) {
                    $('#group-table').DataTable().draw(true);
                })
            });


        });



        $(document).on('click', '.btn-category-add', function (e) {
            $('#new-category').show();
            // $('#back-section').show();
            $('#categories-table').parents('div.dataTables_wrapper').first().hide();
            $('#top-head').hide();
        });

        $(document).on('click', '.btn-back', function (e) {
            $('#new-category').hide();
            $('#back-section').hide();
            $('#categories-table').parents('div.dataTables_wrapper').first().show();
            $('#top-head').show();
        });


        $(document).on('click', '.btn-category-update', function (e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = 'category/update/' + $('#id_for_update').val();
            var chk = 0;

            // if ($('#sub_category_for_edit').is(":checked"))
            // {
            //     chk = 1;
            // }

            // confirm then
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',

                data: {method: '_POST', submit: true,
                    name:$('#name_for_edit').val(),
                },

                error: function (request, status, error) {
                    var myObj = JSON.parse(request.responseText);
                    alert(myObj.error);
                },

                success: function (data) {
                    $('#edit-category').hide();
                    $('#categories-table').DataTable().draw(false);
                    $('#top-head').show();
                    $('#categories-table').parents('div.dataTables_wrapper').first().show();

                }

            });
        });

    </script>

@endpush
