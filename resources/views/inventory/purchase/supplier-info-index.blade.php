@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Supplier Information</li>
        </ol>
    </nav>

    <div class="container-fluid">

        <div class="row" id="top-head">
            <div class="col-md-6">
                <div class="pull-left">
                    <button type="button" class="btn btn-supplier-add btn-primary"><i class="fa fa-plus"></i>New Supplier</button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="pull-right">
                    <button type="button" class="btn btn-print btn-primary"><i class="fa fa-print"></i>Print</button>
                </div>
            </div>
        </div>

{{--        <div class="row col-md-12 dataTables_wrapper" style="overflow-x:auto;">--}}
{{--            <table class="table table-bordered table-hover table-responsive" id="units-table">--}}
        <div class="row col-md-12 dataTables_wrapper" style="overflow-x:auto;">
            <table class="table table-bordered table-hover" id="suppliers-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Name</th>
                    <th>GL Account</th>
                    <th>Address </th>
                    <th>City</th>
                    <th>Phone No</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>

        {!! Form::open(['url'=>'purchase/supplierInfoIndex','method'=>'POST']) !!}
        <div id="new-supplier" class="col-md-12">
            <table width="100%" class="table table-bordered table-striped table-hover table-info">
                <tbody>
                <tr>
                    <td><label for="name" class="control-label">Supplier Name</label></td>
                    <td><input id="name" type="text" class="form-control" name="name" value="" required autofocus></td>
                    <td><label for="tax_number" class="control-label">Supplier Tax Number</label></td>
                    <td><input id="tax_number" type="text" class="form-control" name="tax_number" value=""></td>

                </tr>
                <tr>
                    <td><label for="email">E Mail</label></td>
                    <td><input id="email" type="email" class="form-control" name="email" value=""></td>
                    <td><label for="phone_number">Phone No</label></td>
                    <td><input id="phone_number" type="text" class="form-control" name="phone_number" value=""></td>
                </tr>

                <tr>
                    <td><label for="address">Address</label></td>
                    <td><input id="address" type="text" class="form-control" name="address" value=""></td>
                    <td><label for="city">City</label></td>
                    <td><input id="city" type="text" class="form-control" name="city" value=""></td>
                </tr>

                <tr>
                    <td><label for="state">State</label></td>
                    <td><input id="state" type="text" class="form-control" name="state" value=""></td>
                    <td><label for="zip_code">Post Code</label></td>
                    <td><input id="zip_code" type="text" class="form-control" name="zip_code" value=""></td>
                </tr>


                    <tr>
                        <td><label for="country">Country</label></td>
                        <td><input id="country" type="text" class="form-control" name="country" value=""></td>
                        <td><label for="ledger_acc_no">GL Account No</label></td>
                        <td><input id="ledger_acc_no" type="text" class="form-control" name="ledger_acc_no" value=""></td>
                    </tr>
                </tbody>

                <tfoot>
                <tr>
                    <td colspan="4"><button type="submit" id="btn-category-save" class="btn btn-primary btn-supplier-save">Submit</button></td>
                </tr>
                </tfoot>

            </table>
        </div>
        {!! Form::close() !!}

        {{--        <form action="#"  method="post" accept-charset="utf-8">--}}
        <div id="edit-supplier" class="col-md-8">
            <table width="50%" class="table table-bordered table-striped table-hover">
                <tbody>
                <tr>
                    <td><label for="name" class="control-label">Category Name</label></td>
                    <td><input id="name_for_edit" type="text" class="form-control" name="name_for_edit" value="" required autofocus></td>
                </tr>
                <tr>
                    <td><label for="group_name">Has Sub Category ?</label></td>
                    <td><input type="checkbox" id="sub_category_for_edit" name="sub_category_for_edit" data-toggle="toggle" data-onstyle="primary"></td>
                </tr>
                @if($comp_modules->contains('module_id',4))
                    <tr>
                        <td><label for="group_name">GL Account No</label></td>
                        <td><input id="acc_no_for_edit" type="text" class="form-control" name="acc_no_for_edit" value=""></td>
                    </tr>
                @endif
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

        $(document).ready(function(){

            $('#new-supplier').hide();
            $('#edit-supplier').hide();

        });


        $(function() {
            var table= $('#suppliers-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'getSupplierInfo',
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'ledger_acc_no', name: 'ledger_acc_no' },
                    { data: 'address', name: 'address' },
                    { data: 'city', name: 'city' },
                    { data: 'phone_number', name: 'phone_number' },
                    { data: 'status', name: 'status'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
                ]
            });
        });





        $(document).on('click', '.btn-supplier-add', function (e) {
            $('#new-supplier').show();
            $('#suppliers-table').parents('div.dataTables_wrapper').first().hide();
            $('#top-head').hide();
        });



    </script>

@endpush
