@extends('layouts.app')

@section('content')
    <script src="{!! asset('assets/js/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Export Report Menus</li>
        </ol>
    </nav>

    <div class="container-fluid">

        <div class="col-sm-3">
            <div class="card">
                @isset($menus)
                    <div class="card-body" style="justify-content: center">
                        <table class="table table-striped table-success">
                            <tbody>
                            @foreach($menus as $menu)
                                <tr>
                                    <td><a href="{!! url(''.$menu->url.'') !!}" class="btn btn-facebook" style="width: 240px">{!! $menu->name !!}</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endisset
            </div>

        </div>
    </div>
@endsection
