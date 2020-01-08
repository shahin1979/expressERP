@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Statement File</li>
        </ol>
    </nav>

    <div class="container-fluid">

        <div class="row" id="top-head">
            <div class="col-md-6">
                <div class="pull-left">
                    <button type="button" class="btn btn-statement-add btn-success"><i class="fa fa-plus"></i>New</button>
                </div>
            </div>
            <div class="col-md-5">
                <div class="pull-right">
                    <button type="button" class="btn btn-department btn-success"><i class="fa fa-print"></i>Print</button>
                </div>
            </div>
        </div>

        <div class="row col-md-12 dataTables_wrapper" style="overflow-x:auto;">
            <table class="table table-bordered table-hover" id="statements-table">
                <thead style="background-color: #b0b0b0">
                <tr class="table-success">
                    <th>File No</th>
                    <th>Name</th>
                    <th>Import From</th>
                    <th>Import Line</th>
                    <th>Put Line</th>
                    <th>Value</th>
                    <th>Value Date</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>



        {!! Form::open(['url'=>'statement/createFileIndex','method'=>'POST']) !!}

        <div id="new-statement" class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary">Insert Statement Details</div>

                <div class="card-body">

                    <table width="100%" class="table table-bordered table-striped table-hover">
                        <tbody>

                        <tr>
                            <td><label for="file_no" class="control-label">STATEMENT ID :</label></td>
                            <td><input id="file_no" type="text" class="form-control text-right" name="file_no" value="" required></td>
                            <td><label for="file_desc" class="control-label">TITLE :</label></td>
                            <td><input id="file_desc" type="text" class="form-control text-right" name="file_desc" value="" required></td>
                        </tr>

                        <tr>
                            <td><label for="import_file" class="control-label">FROM FILE :</label></td>
                            <td>{!! Form::select('import_file',$fileList,null,array('id'=>'import_file','class'=>'form-control','placeholder'=>'Please Select')) !!}</td>
                            <td><label for="import_line" class="control-label">FROM LINE :</label></td>
                            <td><input id="import_line" type="text" class="form-control text-right" name="import_line" value=""></td>
                        </tr>

                        <tr>
                            <td><label for="into_line" class="control-label">TO LINE :</label></td>
                            <td><input id="into_line" type="text" class="form-control text-right" name="into_line" value=""></td>
                        </tr>

                        </tbody>

                        <tfoot>
                        <tr>
                            <td colspan="4"><button type="submit" id="btn-statement-save" class="btn btn-primary btn-statement-save">Submit</button></td>
                        </tr>
                        </tfoot>

                    </table>

                </div>
            </div>
        </div>
        {!! Form::close() !!}


        <div id="edit-statement" class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary">Insert Statement Details</div>

                <div class="card-body">

                    <table width="100%" class="table table-bordered table-striped table-hover">
                        <tbody>

                        <tr>
                            <td><label for="file_no" class="control-label">STATEMENT ID :</label></td>
                            <td><input id="u_file_no" type="text" class="form-control text-right" name="file_no" value="" required readonly></td>
                            <td><label for="file_desc" class="control-label">TITLE :</label></td>
                            <td><input id="u_file_desc" type="text" class="form-control text-right" name="file_desc" value="" required></td>
                        </tr>

                        <tr>
                            <td><label for="import_file" class="control-label">FROM FILE :</label></td>
                            <td>{!! Form::select('import_file',$fileList,null,array('id'=>'u_import_file','class'=>'form-control','placeholder'=>'Please Select')) !!}</td>
                            <td><label for="import_line" class="control-label">FROM LINE :</label></td>
                            <td><input id="u_import_line" type="text" class="form-control text-right" name="import_line" value=""></td>
                        </tr>

                        <tr>
                            <td><label for="into_line" class="control-label">TO LINE :</label></td>
                            <td><input id="u_into_line" type="text" class="form-control text-right" name="into_line" value=""></td>
                        </tr>

                        </tbody>

                        <input id="u_id" type="hidden" name="u_id"/>

                        <tfoot>
                        <tr>
                            <td colspan="4"><button type="submit" id="btn-statement-update" class="btn btn-primary btn-statement-update">Update</button></td>
                        </tr>
                        </tfoot>

                    </table>

                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')

    <script>

        $(document).ready(function(){

            $('#new-statement').hide();
            $('#edit-statement').hide();

        });


        $(function() {
            var table= $('#statements-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'statementFileList',
                columns: [
                    { data: 'file_no', name: 'file_no' },
                    { data: 'file_desc', name: 'file_desc' },
                    { data: 'import_file', name: 'import_file' },
                    { data: 'import_line', name: 'import_line' },
                    { data: 'into_line', name: 'into_line' },
                    { data: 'import_value', name: 'import_value' },
                    { data: 'value_date', name: 'value_date' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
                ]
            });

            $(this).on('click', '.btn-statement-edit', function (e) {

                $('#u_file_no').val($(this).data('file'));
                $('#u_file_desc').val($(this).data('name'));
                $('#u_import_file').val($(this).data('fImport'));
                $('#u_import_line').val($(this).data('fLine'));
                $('#u_into_line').val($(this).data('tLine'));
                $('#u_id').val($(this).data('rowId'));

                $('#edit-statement').show();
                $('#statements-table').parents('div.dataTables_wrapper').first().hide();
                $('#top-head').hide();
            });


        });

        $(document).on('click', '.btn-statement-update', function (e) {
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = 'updateFileIndex';

            // confirm then
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',

                data: {method: '_POST', submit: true,
                    file_no:$('#u_file_no').val(),
                    file_desc:$('#u_file_desc').val(),
                    import_file:$('#u_import_file').val(),
                    import_line:$('#u_import_line').val(),
                    into_line:$('#u_into_line').val(),
                },

                error: function (request, status, error) {
                    alert(request.responseText);
                },

                success: function (data) {
                    $('#edit-statement').hide();
                    $('#statements-table').DataTable().draw(false);
                    $('#top-head').show();
                    $('#statements-table').parents('div.dataTables_wrapper').first().show();

                }

            });
        });

        $('#statements-table').on('click', '.btn-delete[data-remote]', function (e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = $(this).data('remote');
            // confirm then
            $.ajax({
                url: url,
                type: 'DELETE',
                dataType: 'json',
                data: {method: '_DELETE', submit: true}
            }).always(function (data) {
                $('#users-table').DataTable().draw(false);
            });
        });


        $(document).on('click', '.btn-statement-add', function (e) {
            $('#new-statement').show();
            $('#statements-table').parents('div.dataTables_wrapper').first().hide();
            $('#top-head').hide();
        });


    </script>



@endpush
