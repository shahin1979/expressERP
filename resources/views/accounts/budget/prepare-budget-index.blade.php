@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Budget Preparation</li>
        </ol>
    </nav>

    <div class="container-fluid">

        <div class="row">
            <div class="col-md-6">
                <div class="pull-left">
                    <a class="btn btn-primary" href="{!! url('budget/prepareBudgetIndex') !!}"> <i class="fa fa-list"></i> Back </a>
                </div>
            </div>

            <div class="col-md-5">
                <div class="pull-right">
                    <a class="btn btn-primary" href="#"> <i class="fa fa-print"></i> print </a>
                </div>
            </div>
        </div>

        <div class="row col-md-12 dataTables_wrapper" style="overflow-x:auto;">
            <table class="table table-bordered table-hover" id="budgets-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Acc No</th>
                    <th>Account Name</th>
                    <th>Account Type</th>
                    <th>Already Expense</th>
                    <th>Budget</th>
                    <th>Balance</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>

        {!! Form::open(['url'=>'budget/prepareBudgetIndex','method'=>'POST']) !!}

        <input name="acc_no" id="acc_no" type="hidden">

        <div id="new-budget" class="col-md-8">
            <table width="50%" class="table table-bordered table-striped table-hover">
                <tbody>
                <tr>
                    <td colspan="2" id="acc_name" style="color: #a94442"></td>
                    <td><label for="name" class="control-label">Current Year</label></td>
                    <td colspan="3"><input id="cyr_total" style="background-color: #f1f1f1" type="text" class="form-control text-right" name="cyr_total" value="" required autofocus></td>
                </tr>
                <tr>
                    <td><label for="bgt_01" class="control-label">{!! $months[0]->month_name !!}</label></td>
                    <td><input id="bgt_01" type="text" class="form-control text-right" name="bgt_01" value="0" required></td>
                    <td><label for="bgt_02" class="control-label">{!! $months[1]->month_name !!}</label></td>
                    <td><input id="bgt_02" type="text" class="form-control text-right" name="bgt_02" value="0" required></td>
                </tr>

                <tr>
                    <td><label for="bgt_03" class="control-label">{!! $months[2]->month_name !!}</label></td>
                    <td><input id="bgt_03" type="text" class="form-control text-right" name="bgt_03" value="0" required></td>
                    <td><label for="bgt_04" class="control-label">{!! $months[3]->month_name !!}</label></td>
                    <td><input id="bgt_04" type="text" class="form-control text-right" name="bgt_04" value="0" required></td>
                </tr>

                <tr>
                    <td><label for="bgt_05" class="control-label">{!! $months[4]->month_name !!}</label></td>
                    <td><input id="bgt_05" type="text" class="form-control text-right" name="bgt_05" value="0" required></td>
                    <td><label for="bgt_06" class="control-label">{!! $months[5]->month_name !!}</label></td>
                    <td><input id="bgt_06" type="text" class="form-control text-right" name="bgt_06" value="0" required></td>
                </tr>

                <tr>
                    <td><label for="bgt_07" class="control-label">{!! $months[6]->month_name !!}</label></td>
                    <td><input id="bgt_07" type="text" class="form-control text-right" name="bgt_07" value="0" required></td>
                    <td><label for="bgt_08" class="control-label">{!! $months[7]->month_name !!}</label></td>
                    <td><input id="bgt_08" type="text" class="form-control text-right" name="bgt_08" value="0" required></td>
                </tr>

                <tr>
                    <td><label for="bgt_09" class="control-label">{!! $months[8]->month_name !!}</label></td>
                    <td><input id="bgt_09" type="text" class="form-control text-right" name="bgt_09" value="0" required></td>
                    <td><label for="bgt_10" class="control-label">{!! $months[9]->month_name !!}</label></td>
                    <td><input id="bgt_10" type="text" class="form-control text-right" name="bgt_10" value="0" required></td>
                </tr>

                <tr>
                    <td><label for="bgt_11" class="control-label">{!! $months[10]->month_name !!}</label></td>
                    <td><input id="bgt_11" type="text" class="form-control text-right" name="bgt_11" value="0" required></td>
                    <td><label for="bgt_12" class="control-label">{!! $months[11]->month_name !!}</label></td>
                    <td><input id="bgt_12" type="text" class="form-control text-right" name="bgt_12" value="0" required></td>
                </tr>

                </tbody>

                <tfoot>
                <tr>
                    <td colspan="4"><button type="submit" id="btn-category-save" class="btn btn-primary btn-category-save">Submit</button></td>
                </tr>
                </tfoot>

            </table>
        </div>
        {!! Form::close() !!}
    </div>


@endsection

@push('scripts')

<script>
    $(document).ready(function(){

        $('#new-budget').hide();
        $('#edit-budget').hide();

    });

    $(function() {
        var table= $('#budgets-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: 'getBudgetInfo',
            columns: [
                { data: 'acc_no', name: 'acc_no' },
                { data: 'acc_name', name: 'acc_name' },
                { data: 'details.description', name: 'details.description' },
                { data: 'curr_bal', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2 ), name: 'curr_bal' },
                { data: 'cyr_bgt_tr', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2 ), name: 'cyr_bgt_tr' },
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


    // $(document).on('click', '.btn-budget-add', function (e) {
    //     $('#new-budget').show();
    //     $('#budget-table').parents('div.dataTables_wrapper').first().hide();
    //     $('#top-head').hide();
    // });


    $(function (){
        $(document).on("focus", "input:text", function() {
            $(this).select();
        });
    });

</script>

@endpush
