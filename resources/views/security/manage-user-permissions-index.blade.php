@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">User permission</li>
        </ol>
    </nav>

    <div class="justify-content-center">
        <img src="{!! asset('assets/images/page-under-construction.jpg') !!}" class="img-responsive">
    </div>


    {{--    <div class="row">--}}
    {{--        <div class="col-md-6">--}}
    {{--            <div class="pull-left">--}}
    {{--                <button type="button" class="btn btn-project btn-success" data-toggle="modal" data-target="#modal-new-project"><i class="fa fa-plus"></i>New Project</button>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div class="col-md-6">--}}
    {{--            <div class="pull-right">--}}
    {{--                <button type="button" class="btn btn-print-project btn-success"><i class="fa fa-print"></i>Print</button>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}

    {{--    <div class="row">--}}
    {{--        <div class="col-md-12" style="overflow-x:auto;">--}}
    {{--            <table class="table table-bordered table-hover table-responsive" id="project-table">--}}
    {{--                <thead style="background-color: #b0b0b0">--}}
    {{--                <tr>--}}
    {{--                    <th>Code</th>--}}
    {{--                    <th>Name</th>--}}
    {{--                    <th>Type</th>--}}
    {{--                    <th>Status</th>--}}
    {{--                    <th>Start</th>--}}
    {{--                    <th>End</th>--}}
    {{--                    <th>Budget</th>--}}
    {{--                    <th>Expensed</th>--}}
    {{--                    <th>Action</th>--}}
    {{--                </tr>--}}
    {{--                </thead>--}}
    {{--            </table>--}}
    {{--        </div>--}}
    {{--    </div>--}}


@endsection

@push('scripts')

    <script>
        $(function() {
            var table= $('#project-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'projectData',
                columns: [
                    { data: 'project_code', name: 'project_code' },
                    { data: 'project_name', name: 'project_name' },
                    { data: 'project_type', name: 'project_type' },
                    { data: 'status', name: 'status' },
                    { data: 'start_date', name: 'start_date' },
                    { data: 'end_date', name: 'end_date'},
                    { data: 'budget', name: 'budget'},
                    { data: 'expense', name: 'expense'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
                ]
            });
        });

        // add new project

        $(document).on('click', '.btn-new-project', function (e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = 'newProjectSave';
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',

                data: $('#add-project').serialize(),

                error: function (request, status, error) {
                    alert(request.responseText);
                },

                success: function (data) {

                    alert(data.success);
                    $('#modal-new-project').modal('hide');
                    $('#project-table').DataTable().draw(false);
                }
            });
        });

    </script>

@endpush
