<div class="modal fade right" id="modal-new-gh-head" tabindex="-1" role="dialog" aria-labelledby="modal-new-gh-head-label"
     aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-info" role="document">
        <!--Content-->
        <form action="#"  method="post" accept-charset="utf-8">
            {{ csrf_field() }}

            <div class="modal-content">
                <!--Header-->
                <div class="modal-header" style="background-color: rgba(44,221,32,0.29);">
                    <p class="heading">New Ledger
                    </p>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>

                <!--Body-->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card text-primary bg-gray border-primary">

                                <div class="card-body">

                                    <div class="form-group row" id="md-name">
                                        <label for="ledger_code" class="col-sm-4 col-form-label text-md-right">Group</label>
                                        <div class="col-sm-8">
                                            <div class="input-group mb-3">
                                                {!! Form::select('ledger_code',$groups,null,array('id'=>'ledger_code','class'=>'form-control','autofocus')) !!}
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group row" id="md-name">
                                        <label for="acc_name" class="col-sm-4 col-form-label text-md-right">Ledger Name</label>
                                        <div class="col-sm-8">
                                            <div class="input-group mb-3">
                                                <input type="text" name="acc_name" id="acc_name" class="form-control" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Footer-->
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-primary btn-new-head pull-left">Save</button>
                    <a type="button" class="btn btn-danger waves-effect pull-right" data-dismiss="modal">Cancel</a>
                </div>

            </div>
            <!--/.Content-->
        </form>
    </div>
</div>
<!-- Modal: modalAbandonedCart-->
