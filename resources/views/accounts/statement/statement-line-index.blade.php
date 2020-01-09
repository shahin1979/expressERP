@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Create Statement Lines</li>
        </ol>
    </nav>

    <div class="container spark-screen selection-area">

        <div class="row">
            <div class="col-md-8 col-md-offset-2" >

                <div class="div">
                    <br/>
                    {!! Form::open(['url'=>'statement/lineStatementIndex', 'method' => 'GET']) !!}

                    <table width="90%" class="table table-responsive table-hover" >

                        <tr>
                            <td width="25%"><label for="acc_no" class="control-label" >Select File</label></td>
                            <td width="55%">{!! Form::select('input_file',$fileList, null  , array('id' => 'input_file', 'class' => 'form-control','required')) !!}</td>
                            <td  width="20%"><button name="action" type="submit" value="preview" class="btn btn-info btn-reject pull-left">Submit</button></td>
                        </tr>


                    </table>

                    {!! Form::close() !!}

                </div>
            </div>

            <div style="width: 5px"></div>

        </div>
    </div>

    @if(isset($param['file_no']))
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
                    <th>Line</th>
                    <th>Text</th>
                    <th>Ac1 Start</th>
                    <th>Ac1 End</th>
                    <th>Ac2 Start</th>
                    <th>Ac2 End</th>
                    <th>Sub Total</th>
                    <th>Formula</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>



        {!! Form::open(['url'=>'statement/lineStatementIndex','method'=>'POST']) !!}

        <div id="new-statement" class="col-md-10">
            <div class="card">
                <div class="card-header bg-success">Insert Statement Details</div>

                <div class="card-body">

                    <table width="100%" class="table table-bordered table-striped table-hover">
                        <tbody>

                        <tr>
                            <td><label for="file_no" class="control-label">STATEMENT ID :</label></td>
                            <td><input id="file_no" type="text" class="form-control text-right" name="file_no" value="{!! $param['file_no'] ?? null !!}" required readonly></td>
                            <td><label for="file_desc" class="control-label">TITLE :</label></td>
                            <td><input id="file_desc" type="text" class="form-control text-right" name="file_desc" value="{!! $param['file_desc'] ?? null !!}" required readonly></td>
                        </tr>

                        <tr>
                            <td><label for="import_line" class="control-label">LINE NO:</label></td>
                            <td><input id="line_no" type="text" class="form-control text-right" name="line_no" value="{!! $param['max_line'] ?? null !!}"></td>
                            <td><label for="text_position" class="control-label">TEXT POSITION :</label></td>
                            <td>{!! Form::select('text_position',[5 => '5', 10 => '10',15 => '15'],15,array('id'=>'text_position','class'=>'form-control')) !!}</td>
                        </tr>

                        <tr>
                            <td><label for="font_size" class="control-label">FONT SIZE:</label></td>
                            <td>{!! Form::select('font_size',[10 => '10', 12 => '12', 14 => '14'],null,array('id'=>'font_size','class'=>'form-control')) !!}</td>
                            <td><label for="texts" class="control-label">TEXT :</label></td>
                            <td><input id="texts" type="text" class="form-control text-right" name="texts" value=""></td>
                        </tr>

                        <tr>
                            <td><label for="acc_type" class="control-label">ACC TYPE:</label></td>
                            <td>{!! Form::select('acc_type',$types,null,array('id'=>'acc_type','class'=>'form-control','placeholder'=>'Please Select')) !!}</td>
                            <td><label for="note" class="control-label">NOTE SL :</label></td>
                            <td><input id="note" type="text" class="form-control text-right" name="note" value=""></td>
                        </tr>

                        <tr>
                            <td><label for="ac11" class="control-label">FIRST ACC FROM:</label></td>
                            <td><input id="ac11" type="text" class="form-control text-right" name="ac11" value=""></td>
                            <td><label for="ac12" class="control-label">FIRST ACC TO:</label></td>
                            <td><input id="ac12" type="text" class="form-control text-right" name="ac12" value=""></td>
                        </tr>

                        <tr>
                            <td><label for="ac21" class="control-label">2ND ACC FROM:</label></td>
                            <td><input id="ac21" type="text" class="form-control text-right" name="ac21" value=""></td>
                            <td><label for="ac22" class="control-label">2ND ACC TO:</label></td>
                            <td><input id="ac22" type="text" class="form-control text-right" name="ac22" value=""></td>
                        </tr>

                        <tr>
                            <td><label for="sub_total" class="control-label">SUB TOTAL:</label></td>
                            <td><input id="sub_total" type="text" class="form-control text-right" name="sub_total" value=""></td>
                            <td><label for="formula" class="control-label">FORMULA:</label></td>
                            <td><input id="formula" type="text" class="form-control text-right" name="formula" value=""></td>
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
                <div class="card-header bg-success">Insert Statement Details</div>

                <div class="card-body">

                    <table width="100%" class="table table-bordered table-striped table-hover">
                        <tbody>

                        <tr>
                            <td><label for="file_no" class="control-label">STATEMENT ID :</label></td>
                            <td><input id="u_file_no" type="text" class="form-control text-right" name="file_no" value="" required readonly></td>
                            <td><label for="file_desc" class="control-label">TITLE :</label></td>
                            <td><input id="u_file_desc" type="text" class="form-control text-right" name="file_desc" value="" required readonly></td>
                        </tr>

                        <tr>
                            <td><label for="import_line" class="control-label">LINE NO:</label></td>
                            <td><input id="u_line_no" type="text" class="form-control text-right" name="line_no" value=""></td>
                            <td><label for="text_position" class="control-label">TEXT POSITION :</label></td>
                            <td>{!! Form::select('text_position',[5 => '5', 10 => '10',15 => '15'],15,array('id'=>'u_text_position','class'=>'form-control')) !!}</td>
                        </tr>

                        <tr>
                            <td><label for="font_size" class="control-label">FONT SIZE:</label></td>
                            <td>{!! Form::select('font_size',[10 => '10', 12 => '12', 14 => '14'],null,array('id'=>'u_font_size','class'=>'form-control')) !!}</td>
                            <td><label for="texts" class="control-label">TEXT :</label></td>
                            <td><input id="u_texts" type="text" class="form-control text-right" name="texts" value=""></td>
                        </tr>

                        <tr>
                            <td><label for="acc_type" class="control-label">ACC TYPE:</label></td>
                            <td>{!! Form::select('acc_type',$types,null,array('id'=>'u_acc_type','class'=>'form-control','placeholder'=>'Please Select')) !!}</td>
                            <td><label for="note" class="control-label">NOTE SL :</label></td>
                            <td><input id="u_note" type="text" class="form-control text-right" name="note" value=""></td>
                        </tr>

                        <tr>
                            <td><label for="ac11" class="control-label">FIRST ACC FROM:</label></td>
                            <td><input id="u_ac11" type="text" class="form-control text-right" name="ac11" value=""></td>
                            <td><label for="ac12" class="control-label">FIRST ACC TO:</label></td>
                            <td><input id="u_ac12" type="text" class="form-control text-right" name="ac12" value=""></td>
                        </tr>

                        <tr>
                            <td><label for="ac21" class="control-label">2ND ACC FROM:</label></td>
                            <td><input id="u_ac21" type="text" class="form-control text-right" name="ac21" value=""></td>
                            <td><label for="ac22" class="control-label">2ND ACC TO:</label></td>
                            <td><input id="u_ac22" type="text" class="form-control text-right" name="ac22" value=""></td>
                        </tr>

                        <tr>
                            <td><label for="sub_total" class="control-label">SUB TOTAL:</label></td>
                            <td><input id="u_sub_total" type="text" class="form-control text-right" name="sub_total" value=""></td>
                            <td><label for="formula" class="control-label">FORMULA:</label></td>
                            <td><input id="u_formula" type="text" class="form-control text-right" name="formula" value=""></td>
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

    @endif





@endsection

@push('scripts')

    <script>

        $(document).ready(function(){

            $('#new-statement').hide();
            $('#edit-statement').hide();

        });

        $(document).on('click', '.btn-statement-add', function (e) {
            $('#new-statement').show();
            $('#statements-table').parents('div.dataTables_wrapper').first().hide();
            $('#top-head').hide();
        });

        $(function() {
            var table= $('#statements-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'getStatementLineData/' + $('#file_no').val(),
                columns: [
                    { data: 'line_no', name: 'line_no' },
                    { data: 'texts', name: 'texts' },
                    { data: 'ac11', name: 'ac11' },
                    { data: 'ac12', name: 'ac12' },
                    { data: 'ac21', name: 'ac21' },
                    { data: 'ac22', name: 'ac22' },
                    { data: 'sub_total', name: 'sub_total' },
                    { data: 'formula', name: 'formula' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
                ]
            });

            $(this).on('click', '.btn-statement-edit', function (e) {

                $('#u_file_no').val($(this).data('file'));
                $('#u_file_desc').val($(this).data('name'));
                $('#u_line_no').val($(this).data('line'));
                $('#u_texts').val($(this).data('texts'));
                $('#u_font_size').val($(this).data('fontSize'));
                $('#u_acc_type').val($(this).data('accType'));
                $('#u_ac11').val($(this).data('ac11'));
                $('#u_ac12').val($(this).data('ac12'));
                $('#u_ac21').val($(this).data('ac21'));
                $('#u_ac22').val($(this).data('ac22'));
                $('#u_sub_total').val($(this).data('subTotal'));
                $('#u_formula').val($(this).data('formula'));
                $('#u_id').val($(this).data('rowId'));

                $('#edit-statement').show();
                $('#statements-table').parents('div.dataTables_wrapper').first().hide();
                $('#top-head').hide();
            });
        });

    </script>


@endpush
