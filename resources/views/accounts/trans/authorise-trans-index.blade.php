@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Authorise Transactions</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 dataTables_wrapper" style="overflow-x:auto;">
            <table class="table table-bordered table-striped table-hover table-responsive" id="trans-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Voucher No</th>
                    <th>Trans date</th>
                    <th>Account</th>
                    <th>Debit <br/>Amt</th>
                    <th>Credit<br/>Amt</th>
                    <th>Amount</th>
                    <th>Pending For</th>
                    <th>Created By</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>


{{--    <div id="trans-details" class="col-md-8">--}}
{{--        <table width="50%" class="table table-bordered table-striped table-hover">--}}
{{--            <tbody>--}}
{{--            <tr>--}}
{{--                <td><label for="name" class="control-label">Category Name</label></td>--}}
{{--                <td></td>--}}
{{--            </tr>--}}
{{--            <tr>--}}
{{--                <td><label for="group_name">Has Sub Category ?</label></td>--}}
{{--                <td><input type="checkbox" id="sub_category_for_edit" name="sub_category_for_edit" data-toggle="toggle" data-onstyle="primary"></td>--}}
{{--            </tr>--}}

{{--            </tbody>--}}
{{--            <input id="id_for_update" type="hidden" name="id_for_update"/>--}}
{{--            <tfoot>--}}
{{--            <tr>--}}
{{--                <td colspan="4"><button type="submit" id="btn-category-update" class="btn btn-primary btn-category-update">Submit</button></td>--}}
{{--            </tr>--}}
{{--            </tfoot>--}}

{{--        </table>--}}
{{--    </div>--}}


@endsection

@push('scripts')

<script>
    $(function() {
        var table= $('#trans-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: 'getUnAuthVoucherData',
            columns: [
                { data: 'voucher', name: 'voucher'},
                { data: 'trans_date', name: 'trans_date' },
                { data: 'ledger', name: 'ledger' },
                { data: 'dr_amt', className: 'dt-right', name: 'dr_amt' },
                { data: 'cr_amt', className: 'dt-right', name: 'cr_amt' },
                { data: 'trans_amt', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2 ), name: 'trans_amt' },
                { data: 'pending', name: 'pending' },
                { data: 'user.name', name: 'user.name' },
                { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}

            ],
            order: [[ 2, "asc" ]]
        });



        $(this).on('click', '.btn-approve', function (e) {
            e.preventDefault();

            var url = $(this).data('remote');
            // confirm then
            $.ajax({
                beforeSend: function (request) {
                    return confirm("Are you sure ?");
                },
                url: url,
                type: 'GET',
                dataType: 'json',
                data: {method: '_GET', submit: true},

                error: function (request, status, error) {
                    alert(request.responseText);
                },

            }).always(function (data) {

                alert(data);
                $('#trans-table').DataTable().draw(true);
            });

            // $('#acc_name_for_edit').val($(this).data('name'));
            // $('#id_for_update').val($(this).data('rowid'));
            //
            // $('#trans-details').show();
            // $('#trans-table').parents('div.dataTables_wrapper').first().hide();
            // $('#top-head').hide();

        });

        $(this).on('click', '.btn-delete', function (e) {
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
                $('#head-table').DataTable().draw(true);
            })
        });

    });
</script>

@endpush
