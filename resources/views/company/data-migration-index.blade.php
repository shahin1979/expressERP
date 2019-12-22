@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Data Migration</li>
        </ol>
    </nav>


    {!! Form::open(['url'=>'company/dataMigrationIndex','method'=>'POST']) !!}
        <div class="justify-content-center">
            <button type="submit" class="btn btn-primary btn-sm">Migrate</button>
        </div>
    {!! Form::close() !!}

@endsection

@push('scripts')

@endpush
