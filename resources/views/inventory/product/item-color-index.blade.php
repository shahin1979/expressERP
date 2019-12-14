@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Item Colors</li>
        </ol>
    </nav>

    <div class="justify-content-center">
        <img src="{!! asset('assets/images/page-under-construction.jpg') !!}" class="img-responsive">
    </div>


@endsection

@push('scripts')

@endpush
