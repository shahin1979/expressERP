<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>ABC Company Limited</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="favicon.ico" type="image/x-icon" />


    <link rel="stylesheet" href="{!! asset('plugins/bootstrap/dist/css/bootstrap.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('plugins/fontawesome-free/css/all.min.css') !!}">
    <link rel="stylesheet" href="plugins/icon-kit/dist/css/iconkit.min.css">
    <link rel="stylesheet" href="plugins/ionicons/dist/css/ionicons.min.css">
    <link rel="stylesheet" href="plugins/perfect-scrollbar/css/perfect-scrollbar.css">
    <link rel="stylesheet" href="plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap.css">
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="plugins/weather-icons/css/weather-icons.min.css">
    <link rel="stylesheet" href="plugins/c3/c3.min.css">
    <link rel="stylesheet" href="plugins/owl.carousel/dist/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="plugins/owl.carousel/dist/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="dist/css/theme.min.css">
    <script src="src/js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<div class="wrapper">

    @include('partials.header')

    <div class="page-wrap">

        @include('menus.menubar')

        <div class="main-content">

                <header class="page-header">
                    <div class="container-fluid">
                        @yield('pagetitle')

                    </div>
                </header>


                <main class="py-4">
                    @include('partials.flash-message')

                    @yield('content')
                </main>


        </div>




{{--        <aside class="right-sidebar">--}}
{{--            <div class="sidebar-chat" data-plugin="chat-sidebar">--}}
{{--                <div class="sidebar-chat-info">--}}
{{--                    <h6>Chat List</h6>--}}
{{--                    <form class="mr-t-10">--}}
{{--                        <div class="form-group">--}}
{{--                            <input type="text" class="form-control" placeholder="Search for friends ...">--}}
{{--                            <i class="ik ik-search"></i>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--                <div class="chat-list">--}}
{{--                    <div class="list-group row">--}}
{{--                        <a href="javascript:void(0)" class="list-group-item" data-chat-user="Gene Newman">--}}
{{--                            <figure class="user--online">--}}
{{--                                <img src="img/users/1.jpg" class="rounded-circle" alt="">--}}
{{--                            </figure><span><span class="name">Gene Newman</span>  <span class="username">@gene_newman</span> </span>--}}
{{--                        </a>--}}
{{--                        <a href="javascript:void(0)" class="list-group-item" data-chat-user="Billy Black">--}}
{{--                            <figure class="user--online">--}}
{{--                                <img src="img/users/2.jpg" class="rounded-circle" alt="">--}}
{{--                            </figure><span><span class="name">Billy Black</span>  <span class="username">@billyblack</span> </span>--}}
{{--                        </a>--}}
{{--                        <a href="javascript:void(0)" class="list-group-item" data-chat-user="Herbert Diaz">--}}
{{--                            <figure class="user--online">--}}
{{--                                <img src="img/users/3.jpg" class="rounded-circle" alt="">--}}
{{--                            </figure><span><span class="name">Herbert Diaz</span>  <span class="username">@herbert</span> </span>--}}
{{--                        </a>--}}
{{--                        <a href="javascript:void(0)" class="list-group-item" data-chat-user="Sylvia Harvey">--}}
{{--                            <figure class="user--busy">--}}
{{--                                <img src="img/users/4.jpg" class="rounded-circle" alt="">--}}
{{--                            </figure><span><span class="name">Sylvia Harvey</span>  <span class="username">@sylvia</span> </span>--}}
{{--                        </a>--}}
{{--                        <a href="javascript:void(0)" class="list-group-item active" data-chat-user="Marsha Hoffman">--}}
{{--                            <figure class="user--busy">--}}
{{--                                <img src="img/users/5.jpg" class="rounded-circle" alt="">--}}
{{--                            </figure><span><span class="name">Marsha Hoffman</span>  <span class="username">@m_hoffman</span> </span>--}}
{{--                        </a>--}}
{{--                        <a href="javascript:void(0)" class="list-group-item" data-chat-user="Mason Grant">--}}
{{--                            <figure class="user--offline">--}}
{{--                                <img src="img/users/1.jpg" class="rounded-circle" alt="">--}}
{{--                            </figure><span><span class="name">Mason Grant</span>  <span class="username">@masongrant</span> </span>--}}
{{--                        </a>--}}
{{--                        <a href="javascript:void(0)" class="list-group-item" data-chat-user="Shelly Sullivan">--}}
{{--                            <figure class="user--offline">--}}
{{--                                <img src="img/users/2.jpg" class="rounded-circle" alt="">--}}
{{--                            </figure><span><span class="name">Shelly Sullivan</span>  <span class="username">@shelly</span></span>--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </aside>--}}

{{--        <div class="chat-panel" hidden>--}}
{{--            <div class="card">--}}
{{--                <div class="card-header d-flex justify-content-between">--}}
{{--                    <a href="javascript:void(0);"><i class="ik ik-message-square text-success"></i></a>--}}
{{--                    <span class="user-name">John Doe</span>--}}
{{--                    <button type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>--}}
{{--                </div>--}}
{{--                <div class="card-body">--}}
{{--                    <div class="widget-chat-activity flex-1">--}}
{{--                        <div class="messages">--}}
{{--                            <div class="message media reply">--}}
{{--                                <figure class="user--online">--}}
{{--                                    <a href="#">--}}
{{--                                        <img src="img/users/3.jpg" class="rounded-circle" alt="">--}}
{{--                                    </a>--}}
{{--                                </figure>--}}
{{--                                <div class="message-body media-body">--}}
{{--                                    <p>Epic Cheeseburgers come in all kind of styles.</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="message media">--}}
{{--                                <figure class="user--online">--}}
{{--                                    <a href="#">--}}
{{--                                        <img src="img/users/1.jpg" class="rounded-circle" alt="">--}}
{{--                                    </a>--}}
{{--                                </figure>--}}
{{--                                <div class="message-body media-body">--}}
{{--                                    <p>Cheeseburgers make your knees weak.</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="message media reply">--}}
{{--                                <figure class="user--offline">--}}
{{--                                    <a href="#">--}}
{{--                                        <img src="img/users/5.jpg" class="rounded-circle" alt="">--}}
{{--                                    </a>--}}
{{--                                </figure>--}}
{{--                                <div class="message-body media-body">--}}
{{--                                    <p>Cheeseburgers will never let you down.</p>--}}
{{--                                    <p>They'll also never run around or desert you.</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="message media">--}}
{{--                                <figure class="user--online">--}}
{{--                                    <a href="#">--}}
{{--                                        <img src="img/users/1.jpg" class="rounded-circle" alt="">--}}
{{--                                    </a>--}}
{{--                                </figure>--}}
{{--                                <div class="message-body media-body">--}}
{{--                                    <p>A great cheeseburger is a gastronomical event.</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="message media reply">--}}
{{--                                <figure class="user--busy">--}}
{{--                                    <a href="#">--}}
{{--                                        <img src="img/users/5.jpg" class="rounded-circle" alt="">--}}
{{--                                    </a>--}}
{{--                                </figure>--}}
{{--                                <div class="message-body media-body">--}}
{{--                                    <p>There's a cheesy incarnation waiting for you no matter what you palete preferences are.</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="message media">--}}
{{--                                <figure class="user--online">--}}
{{--                                    <a href="#">--}}
{{--                                        <img src="img/users/1.jpg" class="rounded-circle" alt="">--}}
{{--                                    </a>--}}
{{--                                </figure>--}}
{{--                                <div class="message-body media-body">--}}
{{--                                    <p>If you are a vegan, we are sorry for you loss.</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <form action="javascript:void(0)" class="card-footer" method="post">--}}
{{--                    <div class="d-flex justify-content-end">--}}
{{--                        <textarea class="border-0 flex-1" rows="1" placeholder="Type your message here"></textarea>--}}
{{--                        <button class="btn btn-icon" type="submit"><i class="ik ik-arrow-right text-success"></i></button>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}

        <footer class="footer">
            <div class="w-100 clearfix">
                <span class="text-center text-sm-left d-md-inline-block">Copyright © 2019 FM Technologies v1.0. All Rights Reserved.</span>
                <span class="float-none float-sm-right mt-1 mt-sm-0 text-center">Crafted with <i class="fa fa-heart text-danger"></i> by <a href="#" class="text-dark" target="_blank">IT Team</a></span>
            </div>
        </footer>

    </div>
</div>


@include('partials.quick-search')



{{--<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>--}}
<script>window.jQuery || document.write('<script src="src/js/vendor/jquery-3.3.1.min.js"><\/script>')</script>
<script src="plugins/popper.js/dist/umd/popper.min.js"></script>
<script src="plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
<script src="plugins/screenfull/dist/screenfull.js"></script>
<script src="plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap.min.js"></script>
{{--<script src="plugins/jvectormap/tests/assets/jquery-jvectormap-world-mill-en.js"></script>--}}
<script src="plugins/moment/moment.js"></script>
<script src="plugins/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="plugins/d3/dist/d3.min.js"></script>
<script src="plugins/c3/c3.min.js"></script>
<script src="js/tables.js"></script>
<script src="js/widgets.js"></script>
<script src="js/charts.js"></script>
<script src="dist/js/theme.min.js"></script>
<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
{{--<script>--}}
{{--    (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=--}}
{{--        function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;--}}
{{--        e=o.createElement(i);r=o.getElementsByTagName(i)[0];--}}
{{--        e.src='https://www.google-analytics.com/analytics.js';--}}
{{--        r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));--}}
{{--    ga('create','UA-XXXXX-X','auto');ga('send','pageview');--}}
{{--</script>--}}
</body>
</html>
