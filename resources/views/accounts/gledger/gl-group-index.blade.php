@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Group Ledger</li>
        </ol>
    </nav>

    <div class="container-fluid">

        <div class="row">

            <div class="col-md-6">
                <div class="pull-left">
                    <button type="button" class="btn btn-group-add btn-success"><i class="fa fa-plus"></i>New Group</button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="pull-right">
                    <button type="button" class="btn btn-print btn-success"><i class="fa fa-print"></i>Print</button>
                </div>
            </div>
        </div>

        <div class="row col-md-12 dataTables_wrapper" style="overflow-x:auto;">
            <table class="table table-bordered table-hover table-responsive" id="group-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>Ledger Code</th>
                    <th>Account No</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Type Details</th>
                    <th>Balance</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>


        <div id="new-group">
            <table class="table table-bordered table-striped table-hover">
                <tbody>
                <tr>
                    <td><label for="type_code">Group Type</label></td>
                    <td>{!! Form::select('type_code',$types,null,array('id'=>'type_code','class'=>'form-control','autofocus')) !!}</td>
                    <td><label for="group_name">Group Name</label></td>
                    <td><input type="text" name="acc_name" id="acc_name" class="form-control" autocomplete="off" required></td>
                </tr>
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="4"><button type="submit" id="btn-group-save" class="btn btn-primary btn-group-save">Submit</button></td>
                    </tr>
                </tfoot>

            </table>
        </div>

    </div>



@endsection

@push('scripts')

    <script>
        $(function() {
            var table= $('#group-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'GLGroupData',
                columns: [
                    { data: 'ledger_code', name: 'ledger_code' },
                    { data: 'acc_no', name: 'acc_no' },
                    { data: 'acc_name', name: 'acc_name' },
                    { data: 'acc_type', name: 'acc_type' },
                    { data: 'details.description', name: 'details.description'},
                    { data: 'curr_bal', name: 'curr_bal'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}

                ]
            });
        });

        $(document).ready(function(){

            $('#new-group').hide();

        });

        $(document).on('click', '.btn-group-add', function (e) {

            $('#new-group').show();
            $('#group-table').parents('div.dataTables_wrapper').first().hide();
            $('.btn-group-add').hide();

        });

        $(document).on('click', '.btn-group-save', function (e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = 'group/save';

            // confirm then
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',

                data: {method: '_POST', submit: true, TYPE_CODE:$('#type_code').val(),
                    ACC_NAME:$('#acc_name').val(),
                },

                error: function (request, status, error) {
                    alert(request.responseText);
                },

                success: function (data) {

                    alert(data.success + data.acc_name);
                    $('#modal-new-gh-head').modal('hide');
                    $('#head-table').DataTable().draw(false);

                }

            });
        });

    </script>

@endpush
