@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: plum; margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            {{--            @foreach(Request::segments() as $segment)--}}
            {{--                <li class="breadcrumb-item active">{!! $segment !!}--}}
            {{--                    --}}{{--                            <a href="#">{{$segment}}</a>--}}
            {{--                </li>--}}
            {{--            @endforeach--}}

            <li class="breadcrumb-item active">Fiscal Period</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8 col-md-offset-2" style="overflow-x:auto;">
            <table class="table table-bordered table-hover table-responsive" id="fiscal-table">
                <thead style="background-color: #b0b0b0">
                <tr>
                    <th>FP No</th>
                    <th>FP Year</th>
                    <th>Month</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Depreciation</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

@endsection

@push('scripts')

    <script>
        $(function() {
            var table= $('#fiscal-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'fiscalData',
                columns: [
                    { data: 'fpno', name: 'fpno' },
                    { data: 'fiscalyear', name: 'fiscalyear' },
                    { data: 'monthname', name: 'monthname' },
                    { data: 'startdate', name: 'startdate' },
                    { data: 'enddate', name: 'enddate'},
                    { data: 'depreciation', name: 'depreciation', orderable: false, searchable: false, printable: false}
                ]
            });
        });

    </script>

@endpush
