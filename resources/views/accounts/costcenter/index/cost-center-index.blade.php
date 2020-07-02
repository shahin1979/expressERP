@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Cost Center</li>
        </ol>
    </nav>

    <div class="container-fluid">

        <div class="row">
            <div class="col-md-6">
                <div class="pull-left">
                    <button type="button" class="btn btn-cost-add btn-primary"><i class="fa fa-plus"></i>New Center</button>

                </div>
            </div>

            <div class="col-md-5">
                <div class="pull-right">
                    <a class="btn btn-primary" href="{!! url('costcenter/costCenterIndex') !!}"> <i class="fa fa-list"></i> Back </a>
                </div>
            </div>
        </div>

        <div class="row col-md-12 dataTables_wrapper" style="overflow-x:auto;">
            <table class="table table-bordered table-hover" id="costs-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Name</th>
                    <th>Already Expense</th>
                    <th>Budget</th>
                    <th>Balance</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>

    @include('accounts.costcenter.form.new-center-form')
    </div>


@endsection

@push('scripts')

    <script>
        $(document).ready(function(){

            $('#new-center').hide();
            $('#edit-budget').hide();

        });

        $(function() {
            var table= $('#costs-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'getCostCenterInfo',
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'expense', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2 ), name: 'expense' },
                    { data: 'current_year_budget', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2 ), name: 'current_year_budget' },
                    { data: 'balance', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2 ), name: 'balance' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
                ]
            });

            $('body').on("click", ".btn-edit", function (e) {
                $('#acc_name').html('Account : '+ $(this).data('number') + ' : ' + $(this).data('name'));
                $('#cyr_total').val($(this).data('bdt00'));
                $('#bgt_01').val($(this).data('bdt01'));
                $('#bgt_02').val($(this).data('bdt02'));
                $('#bgt_03').val($(this).data('bdt03'));
                $('#bgt_04').val($(this).data('bdt04'));
                $('#bgt_05').val($(this).data('bdt05'));
                $('#bgt_06').val($(this).data('bdt06'));
                $('#bgt_07').val($(this).data('bdt07'));
                $('#bgt_08').val($(this).data('bdt08'));
                $('#bgt_09').val($(this).data('bdt09'));
                $('#bgt_10').val($(this).data('bdt10'));
                $('#bgt_11').val($(this).data('bdt11'));
                $('#bgt_12').val($(this).data('bdt12'));

                $('#acc_no').val($(this).data('number'));

                $('#new-budget').show();
                $('#budgets-table').parents('div.dataTables_wrapper').first().hide();

            });

            $( "#cyr_total" ).keyup(function() {
                $('#bgt_01').val($(this).val()/12);
                $('#bgt_02').val($(this).val()/12);
                $('#bgt_03').val($(this).val()/12);
                $('#bgt_04').val($(this).val()/12);
                $('#bgt_05').val($(this).val()/12);
                $('#bgt_06').val($(this).val()/12);
                $('#bgt_07').val($(this).val()/12);
                $('#bgt_08').val($(this).val()/12);
                $('#bgt_09').val($(this).val()/12);
                $('#bgt_10').val($(this).val()/12);
                $('#bgt_11').val($(this).val()/12);
                $('#bgt_12').val($(this).val()/12);
            });

        });


        $(document).on('click', '.btn-cost-add', function (e) {
            $('#new-center').show();
            $('#costs-table').parents('div.dataTables_wrapper').first().hide();
            $('#top-head').hide();
        });


        $(function (){
            $(document).on("focus", "input:text", function() {
                $(this).select();
            });
        });

    </script>

@endpush
