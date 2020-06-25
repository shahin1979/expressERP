@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            {{--            @foreach(Request::segments() as $segment)--}}
            {{--                <li class="breadcrumb-item active">{!! $segment !!}--}}
            {{--                    --}}{{--                            <a href="#">{{$segment}}</a>--}}
            {{--                </li>--}}
            {{--            @endforeach--}}

            <li class="breadcrumb-item active">Fiscal Period</li>
        </ol>
    </nav>

    <div class="container">
        <div class="row">

            <div class="col-md-7">
                <div style="overflow-x:auto;">
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

            <div class="col-md 4">
                <div style="overflow-x:auto;">
                    <table class="table table-bordered table-hover table-responsive" id="codes-table">
                        <thead style="background-color: #b0b0b0">
                        <tr>
                            <th>Trans Code</th>
                            <th>Trans Name</th>
                            <th>Last Id</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>

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
                    { data: 'fp_no', name: 'fp_no' },
                    { data: 'fiscal_year', name: 'fiscal_year' },
                    { data: 'month_name', name: 'month_name' },
                    { data: 'start_date', name: 'start_date' },
                    { data: 'end_date', name: 'end_date'},
                    { data: 'depreciation', name: 'depreciation', orderable: false, searchable: false, printable: false}
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



            var table= $('#codes-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: 'trCodeData',
                columns: [
                    { data: 'trans_code', name: 'trans_code' },
                    { data: 'trans_name', name: 'trans_name' },
                    { data: 'last_trans_id', name: 'last_trans_id' },
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

        });

    </script>

@endpush
