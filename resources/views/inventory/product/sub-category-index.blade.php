@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>
    <link href="{!! asset('assets/bootstrap4-toggle-3.6.1/css/bootstrap4-toggle.min.css') !!}" rel="stylesheet">
    <script src="{!! asset('assets/bootstrap4-toggle-3.6.1/js/bootstrap4-toggle.min.js') !!}"></script>



    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Sub Category</li>
        </ol>
    </nav>

    {{--    <div class="justify-content-center">--}}
    {{--        <img src="{!! asset('assets/images/page-under-construction.jpg') !!}" class="img-responsive">--}}
    {{--    </div>--}}

    <div class="container-fluid">

        <div class="row" id="top-head">

            <div class="col-md-4">
                <div class="pull-left">
                    <button type="button" class="btn btn-sub-category-add btn-primary"><i class="fa fa-plus"></i>New</button>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pull-right">
                    <button type="button" class="btn btn-print btn-primary"><i class="fa fa-print"></i>Print</button>
                </div>
            </div>
        </div>


        <div class="row col-md-12 dataTables_wrapper" style="overflow-x:auto;">
            <table class="table table-bordered table-hover table-responsive" id="sub-categories-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Category</th>
                    <th>Name</th>
                    <th>Stock In Account</th>
                    <th>Stock Out Account</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>

        {!! Form::open(['url'=>'product/subCategoryIndex','method'=>'POST']) !!}
        <div id="new-sub-category" class="col-md-8">
            <table width="50%" class="table table-bordered table-striped table-hover">
                <tbody>
                <tr>
                    <td><label for="name" class="control-label">Category</label></td>
                    <td>{!! Form::select('category_id',$categories,null,array('id'=>'category_id','class'=>'form-control','autofocus')) !!}</td>
                </tr>
                <tr>
                    <td><label for="name" class="control-label">Name</label></td>
                    <td><input id="name" type="text" class="form-control" name="name" value="" required autofocus></td>
                </tr>

                @if($comp_modules->contains('module_id',4))

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

{{--                    <tr>--}}
{{--                        <td><label for="group_name">GL Account No</label></td>--}}
{{--                        <td><input id="acc_no" type="text" class="form-control" name="acc_no" value=""></td>--}}
{{--                    </tr>--}}
                @endif
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
        <div id="edit-sub-category" class="col-md-8">
            <table width="50%" class="table table-bordered table-striped table-hover">
                <tbody>
{{--                <tr>--}}
{{--                    <td><label for="category_id" class="control-label">Category Name</label></td>--}}
{{--                    <td>{!! Form::select('category_id',$categories,null,array('id'=>'category-id-for-edit','class'=>'form-control','autofocus','required')) !!}</td>--}}
{{--                </tr>--}}
                <tr>
                    <td><label for="name" class="control-label">Name</label></td>
                    <td><input id="name-for-edit" type="text" class="form-control" name="name" value="" required autofocus></td>
                </tr>

                @if($comp_modules->contains('module_id',4))

                    <div id="account">
                        <tr class="acc_in">
                            <td><label for="acc-in-stock-edit">Stock In Account No</label></td>
                            <td><input id="acc-in-stock-edit" type="text" class="form-control" name="acc-in-stock-edit" value=""></td>
                        </tr>

                        <tr class="acc_out">
                            <td><label for="acc-out-stock-edit">Stock Out Account</label></td>
                            <td><input id="acc-out-stock-edit" type="text" class="form-control" name="acc-out-stock-edit" value=""></td>
                        </tr>
                    </div>
                @endif
                </tbody>
                <input id="id-for-update" type="hidden" name="id-for-update"/>
                <tfoot>
                <tr>
                    <td colspan="4"><button type="submit" id="btn-sub-category-update" class="btn btn-primary btn-sub-category-update">Update</button></td>
                </tr>
                </tfoot>

            </table>
        </div>
        {{--        </form>--}}

    </div>

@endsection

@push('scripts')

    <script>

        $(document).ready(function(){

            $('#new-sub-category').hide();
            $('#edit-sub-category').hide();

        });

        $(function() {
            var table= $('#sub-categories-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'getSubCategoryData',
                columns: [
                    { data: 'group.name', name: 'group.name' },
                    { data: 'name', name: 'name' },
                    { data: 'acc_in', name: 'acc_in' },
                    { data: 'acc_out', name: 'acc_out' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
                ],
                rowCallback: function( row, data, index ) {
                    if(index%2 == 0){
                        $(row).removeClass('myodd myeven');
                        $(row).addClass('myodd');
                    }else{
                        $(row).removeClass('myodd myeven');
                        $(row).addClass('myeven');
                    }
                }
            });

            $(this).on('click', '.btn-sub-category-edit', function (e) {


                $('#category-id-for-edit').val($(this).data('category'));
                $('#name-for-edit').val($(this).data('name'));
                $('#acc-in-stock-edit').val($(this).data('receive'));
                $('#acc-out-stock-edit').val($(this).data('delivery'));
                $('#id-for-update').val($(this).data('rowid'));

                $('#edit-sub-category').show();
                $('#sub-categories-table').parents('div.dataTables_wrapper').first().hide();
                $('#top-head').hide();
            });


            $(this).on('click', '.btn-sub-category-delete', function (e) {

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
                    $('#sub-categories-table').DataTable().draw(true);
                })
            });


        });



        $(document).on('click', '.btn-sub-category-add', function (e) {
            $('#new-sub-category').show();
            $('#sub-categories-table').parents('div.dataTables_wrapper').first().hide();
            $('#top-head').hide();
        });

        // add new sub Category



        $(document).on('click', '.btn-sub-category-update', function (e) {
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = 'subCategory/update/' + $('#id-for-update').val();

            // confirm then
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',

                data: {method: '_POST', submit: true,
                    acc_out_stock:$('#acc-out-stock-edit').val(),
                    acc_in_stock:$('#acc-in-stock-edit').val(),
                    name:$('#name-for-edit').val(),
                },

                error: function (request, status, error) {
                    var myObj = JSON.parse(request.responseText);
                    alert(myObj.error);
                },

                success: function (data) {
                    $('#edit-sub-category').hide();
                    $('#sub-categories-table').DataTable().draw(false);
                    $('#top-head').show();
                    $('#sub-categories-table').parents('div.dataTables_wrapper').first().show();

                }

            });
        });

    </script>

@endpush
