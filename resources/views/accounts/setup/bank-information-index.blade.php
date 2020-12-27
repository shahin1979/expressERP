@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="{!! asset('assets/js/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Bank Information</li>
        </ol>
    </nav>

    <div class="container-fluid">

        <div class="row" id="section-top">
            <div class="col-md-6">
                <div class="pull-left">
                    <button type="button" class="btn btn-new-bank btn-success"><i class="fa fa-plus"></i>New Bank</button>
                </div>
            </div>

            <div class="col-md-6">
                <div class="pull-right">
                    <button type="button" class="btn btn-print-division btn-primary"><i class="fa fa-print"></i>Print</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 dataTables_wrapper" style="overflow-x:auto;">
                <table class="table table-bordered table-hover table-striped" id="banks-table">
                    <thead style="background-color: #b0b0b0">
                    <tr>
                        <th>Bank Name</th>
                        <th>Branch Name</th>
                        <th>Acc No</th>
                        <th>Acc Title</th>
                        <th>Swift Code</th>
                        <th>Related GL</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>


        {!! Form::open(['url'=>'setup/saveBankInfo','method'=>'POST']) !!}

        <div id="section-new" class="col-md-12">

            <div class="row">
                <div class="col-md-4">
                    <div class="pull-left">
                        <button type="button" class="btn btn-back btn-primary"><i class="fa fa-backward"></i>Back</button>
                    </div>
                </div>
            </div>

            <table width="100%" class="table table-bordered table-striped table-hover">
                <tbody>

                    <tr>
                        <td><label for="bank_type" class="control-label">Bank Type</label></td>
                        <td>{!! Form::select('bank_type', array('C' => 'Company Bank', 'B'=>'Buyer Bank', 'E' => 'Exporter Bank', 'I' => 'Importer Bank', 'S'=>'Supplier Bank', 'D' => 'Document Bank'), null , array('id' => 'bank_type', 'class' => 'form-control')) !!}</td>
                        <td><label for="bank_name" class="control-label">Bank Name</label></td>
                        <td><input id="bank_name" type="text" class="form-control" name="bank_name" value="" required autofocus></td>
                    </tr>
                    <tr>
                        <td><label for="branch_name" class="control-label">Branch Name</label></td>
                        <td><input id="branch_name" type="text" class="form-control" name="branch_name" value=""></td>
                        <td><label for="bank_acc_name" class="control-label">Bank Account Title</label></td>
                        <td><input id="bank_acc_name" type="text" class="form-control" name="bank_acc_name" value=""></td>
                    </tr>
                    <tr>

                    </tr>

                    <tr>
                        <td><label for="bank_acc_no" class="control-label">Bank Account No</label></td>
                        <td><input id="bank_acc_no" type="text" class="form-control" name="bank_acc_no" value=""></td>
                        <td><label for="gl_account_id" class="control-label">Related Ledger No</label></td>
                        <td>{!! Form::select('gl_account_id',$ledgers, null , array('id' => 'gl_account_id', 'class' => 'form-control')) !!}</td>
                    </tr>
                    <tr>
                        <td><label for="mobile_no" class="control-label">Mobile No</label></td>
                        <td><input id="mobile_no" type="text" class="form-control" name="mobile_no" value=""></td>
                        <td><label for="swift_code" class="control-label">Swift Code</label></td>
                        <td><input id="swift_code" type="text" class="form-control" name="swift_code" value=""></td>
                    </tr>

                    <tr>
                        <td><label for="email" class="control-label">Email</label></td>
                        <td><input id="email" type="text" class="form-control" name="email" value=""></td>
                        <td><label for="address" class="control-label">Address</label></td>
                        <td>{!! Form::textarea('address',null,['id'=>'address','size' => '40x3','class'=>'field']) !!}</td>
                    </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="4"><button type="submit" id="btn-division-save" class="btn btn-primary btn-division-save">Submit</button></td>
                </tr>
                </tfoot>

            </table>

        </div>
        {!! Form::close() !!}

        <div id="section-edit" class="col-md-12">
            <form id="ajax-form" action="#">
                <div class="row">
                    <div class="col-md-4">
                        <div class="pull-left">
                            <button type="button" class="btn btn-back btn-primary"><i class="fa fa-backward"></i>Back</button>
                        </div>
                    </div>
                </div>

                <table class="table table-bordered table-striped table-hover">
                    <tbody>

                    <tr>
                        <td><label for="bank_type" class="control-label">Bank Type</label></td>
                        <td>{!! Form::select('bank_type', array('C' => 'Company Bank', 'B'=>'Buyer Bank', 'E' => 'Exporter Bank', 'I' => 'Importer Bank', 'S'=>'Supplier Bank', 'D' => 'Document Bank'), null , array('id' => 'bank-type', 'class' => 'form-control')) !!}</td>
                        <td><label for="bank_name" class="control-label">Bank Name</label></td>
                        <td><input id="bank-name" type="text" class="form-control" name="bank_name" value="" required autofocus></td>
                    </tr>
                    <tr>
                        <td><label for="branch_name" class="control-label">Branch Name</label></td>
                        <td><input id="branch-name" type="text" class="form-control" name="branch_name" value=""></td>
                        <td><label for="bank_acc_name" class="control-label">Bank Account Title</label></td>
                        <td><input id="bank-acc-name" type="text" class="form-control" name="bank_acc_name" value=""></td>
                    </tr>
                    <tr>

                    </tr>

                    <tr>
                        <td><label for="bank_acc_no" class="control-label">Bank Account No</label></td>
                        <td><input id="bank-acc-no" type="text" class="form-control" name="bank_acc_no" value=""></td>
                        <td><label for="related_gl_id" class="control-label">Related Ledger No</label></td>
                        <td>{!! Form::select('related_gl_id',$ledgers, null , array('id' => 'related-gl-id', 'class' => 'form-control')) !!}</td>
                    </tr>
                    <tr>
                        <td><label for="mobile_no" class="control-label">Mobile No</label></td>
                        <td><input id="mobile-no" type="text" class="form-control" name="mobile_no" value=""></td>
                        <td><label for="swiftcode" class="control-label">Swift Code</label></td>
                        <td><input id="swift-code" type="text" class="form-control" name="swift_code" value=""></td>
                    </tr>

                    <tr>
                        <td><label for="email" class="control-label">Email</label></td>
                        <td><input id="email-update" type="text" class="form-control" name="email" value=""></td>
                        <td><label for="address" class="control-label">Address</label></td>
                        <td>{!! Form::textarea('address',null,['id'=>'address-update','size' => '40x3','class'=>'field']) !!}</td>
                    </tr>
                        <input id="id-update" type="hidden" name="id-update"/>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"><button type="submit" id="btn-bank-update" class="btn btn-primary btn-bank-update">Update</button></td>
                        </tr>
                    </tfoot>
                </table>
            </form>
        </div>



    </div> <!--/.Container-->


@endsection

@push('scripts')



    <script>
        $(document).ready(function(){
            $('#section-new').hide();
            $('#section-edit').hide();
        });


        $(function() {
            var table= $('#banks-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'getBanks',
                columns: [
                    { data: 'bank_name', name: 'bank_name' },
                    { data: 'branch_name', name: 'branch_name' },
                    { data: 'bank_acc_no', name: 'bank_acc_no', defaultContent:'' },
                    { data: 'bank_acc_name', name: 'bank_acc_name' },
                    { data: 'swift_code', name: 'swift_code' },
                    { data: 'account.acc_no', name: 'account.acc_no' },
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

            $(this).on("click", ".btn-bank-edit", function (e) {


                $('#id-update').val($(this).data('rowid'));
                $('#bank-type').val($(this).data('type'))
                $('#bank-name').val($(this).data('name'));
                $('#branch-name').val($(this).data('branch'));
                $('#bank-acc-name').val($(this).data('title'));
                $('#bank-acc-no').val($(this).data('account'));
                $('#swift-code').val($(this).data('swift'))
                $('#email-update').val($(this).data('email'))
                $('#related-gl-id').val($(this).data('ledger'))
                $('#mobile-no').val($(this).data('mobile'))
                $('#address-update').val($(this).data('address'))

                $('#section-top').hide();
                $('#banks-table').parents('div.dataTables_wrapper').first().hide();
                $('#section-edit').show();

            });


            $(this).on('click', '.btn-delete', function (e) {
                e.preventDefault();

                if($(this).data('permission') == false) {
                    $.alert({
                        title: 'Alert!',
                        content: 'You Do Not Have Permission',
                    });
                    return false }

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
                        $.alert({
                            title: 'Alert!',
                            content: myObj.message + ' ' + myObj.error,
                        });
                    },

                }).always(function (data) {
                    $(this).DataTable().draw(true);
                })
            });

        });

        $(document).on("click", ".btn-new-bank", function (e) {
            $('#section-new').show();
            $('#section-top').hide();
            $('#banks-table').parents('div.dataTables_wrapper').first().hide();
        });

        $(document).on('click', '.btn-back', function (e) {
            $('#section-new').hide();
            $('#section-edit').hide();
            $('#section-top').show();
            $('#banks-table').parents('div.dataTables_wrapper').first().show();
        });

        // Patient Name Update

        $('#ajax-form').submit(function(e) {

            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = 'update/' + $('#id-update').val();

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: $('#ajax-form').serialize(),

                error: function (request, status, error) {
                    var myObj = JSON.parse(request.responseText);
                    $.alert({
                        title: 'Alert!',
                        content: myObj.message + ' ' + myObj.error,
                    });
                },

                success: function (data) {
                    $.alert({
                        title: 'Alert!',
                        content: 'Bank Updated',
                    });

                    $('#section-edit').hide();
                    $('#banks-table').parents('div.dataTables_wrapper').first().show();
                    $('#banks-table').DataTable().draw(true);

                }

            });
        });


        $(document).on('click', '.btn-print-bank', function (e) {
            window.location = 'print'
        });

        $(function (){
            $(document).on("focus", "input:text", function() {
                $(this).select();
            });
        });
    </script>
@endpush
