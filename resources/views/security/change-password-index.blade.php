@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Change Password</li>
        </ol>
    </nav>


    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{!! url('security/changePassword') !!}">
                        @csrf

                        <input type="hidden" name="email" value="{{ \Illuminate\Support\Facades\Auth::user()->email }}">

                        <div class="form-group row">
                            <label for="user" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                {{--                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>--}}

                                <input type="text" class="form-control" name="user" value="{{ \Illuminate\Support\Facades\Auth::user()->email }}" disabled>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="c_password" class="col-md-4 col-form-label text-md-right">Current Password</label>

                            <div class="col-md-6">
                                <input id="c_password" type="password" class="form-control" name="c_password" value="" required autofocus autocomplete="off" placeholder="Current Password">

                                @if ($errors->has('c_password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('c_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required autocomplete="off">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-info btn-block">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
