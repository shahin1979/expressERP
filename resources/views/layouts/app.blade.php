<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>ABC Company Limited</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

{{--    <link rel="icon" href="favicon.ico" type="image/x-icon" />--}}

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS-->
    <link href="{!! asset('assets/bootstrap-4.3.1/css/bootstrap.min.css') !!}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome CSS-->
    <link href="{!! asset('assets/font-awesome-4.7.0/css/font-awesome.min.css') !!}" rel="stylesheet" type="text/css" />

    <link href="{!! asset('assets/DataTables-1.10.18/css/jquery.dataTables.min.css') !!}" rel="stylesheet" type="text/css" />
    <link href="{!! asset('assets/jquery-ui-1.12.1/jquery-ui.css') !!}" rel="stylesheet" type="text/css" />

    <link href="{!! asset('assets/css/jquery.datetimepicker.min.css') !!}" rel="stylesheet" type="text/css" />


    <link rel="stylesheet" href="{!! asset('dist/css/theme.min.css') !!}">

    <link href="{!! asset('assets/jquery-confirm-v3.3.4/jquery-confirm.min.css') !!}" rel="stylesheet" type="text/css" />


</head>

<body>

<div class="wrapper">

    @include('partials.page-header')

    <div class="page-wrap">

        <div class="main-content" style="padding-left: 50px">


            <header class="page-header" style="margin-bottom: 0.1rem">
                <div class="container-fluid">
                    @yield('pagetitle')

                </div>
            </header>
            <main class="py-4">

                @include('partials.flash-message')

                @yield('content')
            </main>
        </div>

        <footer class="footer">
            <div class="w-100 clearfix">
                <span class="text-center text-sm-left d-md-inline-block">Copyright © 2019 FM Technologies v1.0. All Rights Reserved.</span>
                <span class="float-none float-sm-right mt-1 mt-sm-0 text-center">Crafted with <i class="fa fa-heart text-danger"></i> by <a href="#" class="text-dark" target="_blank">IT Team</a></span>
            </div>
        </footer>

    </div>
</div>

<script type="text/javascript" src="{!! asset('assets/bootstrap-4.3.1/js/bootstrap.bundle.min.js') !!}"></script>
<script type="text/javascript" src="{!! asset('assets/bootstrap-4.3.1/js/bootstrap.min.js') !!}"></script>
<script type="text/javascript" src="{!! asset('assets/DataTables-1.10.18/js/jquery.dataTables.min.js') !!}"></script>
<script type="text/javascript" src="{!! asset('assets/DataTables-1.10.18/js/dataTables.jqueryui.min.js') !!}"></script>
<script type="text/javascript" src="{!! asset('assets/js/jquery.datetimepicker.js') !!}"></script>
<script src="{!! asset('assets/jquery-confirm-v3.3.4/jquery-confirm.min.js') !!}"></script>

@stack('scripts')

</body>
</html>
