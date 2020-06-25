<div class="modal fade right" id="modal-new-project" tabindex="-1" role="dialog" aria-labelledby="modal-new-project-label"
     aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-info" role="document">
        <!--Content-->
        <form action="#" id="add-project"  method="post" accept-charset="utf-8">
            {{ csrf_field() }}

            <div class="modal-content">
                <!--Header-->
                <div class="modal-header" style="background-color: rgba(21,107,16,0.59);">
                    <p class="heading">New Project
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
                                        <label for="project_code" class="col-sm-4 col-form-label text-md-right">Project Code</label>
                                        <div class="col-sm-8">
                                            <div class="input-group mb-3">
                                                <input type="text" name="project_code" id="project_code" class="form-control" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row" id="md-name">
                                        <label for="project_name" class="col-sm-4 col-form-label text-md-right">Project Name</label>
                                        <div class="col-sm-8">
                                            <div class="input-group mb-3">
                                                <input type="text" name="project_name" id="project_name" class="form-control" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row" id="md-name">
                                        <label for="project_type" class="col-sm-4 col-form-label text-md-right">Project Type</label>
                                        <div class="col-sm-8">
                                            <div class="input-group mb-3">
                                                {!! Form::select('project_type',['R'=>'Real Estate','T'=>'Transport','I'=>'Industry','O'=>'Others'],'O',array('id'=>'ledger_code','class'=>'form-control','autofocus')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row" id="md-name">
                                        <label for="start_date" class="col-sm-4 col-form-label text-md-right">Start Date</label>
                                        <div class="col-sm-8">
                                            <div class="input-group mb-3">
                                                <input type="text" value="{!! \Carbon\Carbon::now()->format('d-m-Y') !!}" name="start_date" id="start_date" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row" id="md-name">
                                        <label for="end_date" class="col-sm-4 col-form-label text-md-right">End Date</label>
                                        <div class="col-sm-8">
                                            <div class="input-group mb-3">
                                                <input type="text" value="{!! \Carbon\Carbon::now()->format('d-m-Y') !!}" name="end_date" id="end_date" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row" id="md-name">
                                        <label for="budget" class="col-sm-4 col-form-label text-md-right">Project Budget</label>
                                        <div class="col-sm-8">
                                            <div class="input-group mb-3 text-right">
                                                <input type="text" name="budget" id="budget" class="form-control text-right" autocomplete="off" value="0.00" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row" id="md-name">
                                        <label for="description" class="col-sm-4 col-form-label text-md-right">Description</label>
                                        <div class="col-sm-8">
                                            <div class="input-group mb-3">
                                                <textarea class="form-control" name="description" cols="50" rows="4" id="description"></textarea>
                                            </div>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Footer-->
                <div class="modal-footer justify-content-center" style="background-color: rgba(21,107,16,0.59);">
                    <button type="submit" class="btn btn-primary btn-new-project pull-left">Save</button>
                    <a type="button" class="btn btn-danger waves-effect pull-right" data-dismiss="modal">Cancel</a>
                </div>

            </div>
            <!--/.Content-->
        </form>
    </div>
</div>
<!-- Modal: modalAbandonedCart-->
<script>
    $(function (){
        $(document).on("focus", "input:text", function() {
            $(this).select();
        });
    });
</script>
