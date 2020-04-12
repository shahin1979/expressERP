<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Report</title>

        <link href="public/assets/bootstrap-4.3.1/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <style>
        table.table {
            width:100%;
            margin:0;
            background-color: #ffffff;
        }

        table.order-bank {
            width:100%;
            margin:0;
        }
        table.order-bank th{
            padding:5px;
        }
        table.order-bank td {
            padding:5px;
            background-color: #ffffff;
        }
        tr.row-line th {
            border-bottom-width:1px;
            border-top-width:1px;
            border-right-width:1px;
            border-left-width:1px;
        }
        tr.row-line td {
            border-bottom:none;
            border-bottom-width:1px;
            font-size:10pt;
        }
        th.first-cell {
            text-align:left;
            border:1px solid red;
            color:blue;
        }
        div.order-field {
            width:100%;
            backgroundr: #ffdab9;
            border-bottom:1px dashed black;
            color:black;
        }
        div.blank-space {
            width:100%;
            height: 50%;
            margin-bottom: 100px;
            line-height: 10%;
        }

        div.blank-hspace {
            width:100%;
            height: 25%;
            margin-bottom: 50px;
            line-height: 10%;
        }
    </style>

</head>
<body>



{{--<div >--}}

    <div class="main-content" style="padding-left: 50px">



        <main class="py-4">


            @yield('content')
        </main>
    </div>



{{--</div>--}}


<div class="blank-space"></div>

<script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>
<script type="text/javascript" src="{!! asset('assets/bootstrap-4.3.1/js/bootstrap.min.js') !!}"></script>

</body>
</html>

