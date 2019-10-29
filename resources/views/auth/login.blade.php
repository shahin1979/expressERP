<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login</title>

    <!-- Styles -->
    {{--<link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('assets/css/login.css') }}" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="{!! asset('assets/bootstrap-4.3.1/css/bootstrap.min.css') !!}" rel="stylesheet">
    <script src="{!! asset('assets/bootstrap-4.3.1/js/bootstrap.min.js') !!}"></script>
</head>

<style>
    #text {display:none;color:red}
</style>

<body>


<div class="container">

    <div class="row">
        <div class="col-8 mx-auto">
            {{--<h1><strong>Welcome </strong>{!! get_company_name() !!}</h1>--}}
            {{--<div class="description">--}}
            {{--<p>--}}
            {{--Please fill necessary input--}}
            {{--</p>--}}
            {{--</div>--}}
        </div>
    </div>

    <div class="row">
        <div class="col-8 mx-auto">
            <h3 class="text-center login-title">Login To Continue</h3>

            @include('partials.flash-message')

            <div class="account-wall">
                <img class="profile-img" src="{!! asset('assets/images/sign.png') !!}"  alt="Key Image">
{{--                @if (count($errors) > 0)--}}
{{--                    <div class="alert alert-danger">--}}
{{--                        <strong>Whoops!</strong> There were some problems with your input.<br><br>--}}

{{--                    </div>--}}
{{--                @endif--}}

                {{--<form class="form-signin">--}}
                <form class="form-signin" role="form" method="POST" action="{{ route('login') }}">
                    {{--<form class="form-horizontal" method="POST" action="{{ route('login') }}">--}}
                    {{--{!! Form::open(['url' => route('checkout.createAccount'), 'id' => 'guestCreateAccount']) !!}--}}

                    {{ csrf_field() }}
                    {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}

                    {!! Form::text('name', null , array('id' => 'name', 'class' => 'col-sm-12 form-control','placeholder' => 'name', 'required')) !!}


                    {!! Form::password('password', array('class' => 'form-control','placeholder' => 'Password', 'required','id'=>'password')) !!}
                    <p id="text">WARNING! Caps lock is ON.</p>
                    {{ $errors->has('password') ? ' has-error' : '' }}
                    @if ($errors->has('password'))
                        <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                    @endif

                    <button class="btn btn-lg btn-primary btn-block" type="submit">
                        Sign in</button>
                </form>

                {{--<form class="form-signin" role="form" method="GET" action="{!! url('register') !!}" >--}}

                {{--<button class="btn btn-lg btn-info btn-block" type="submit">--}}
                {{--Register</button>--}}
                {{--</form>--}}

            </div>
        </div>
    </div>
</div>
{{--@include('partials.flash-message')--}}

<script>
    var input = document.getElementById("password");
    var text = document.getElementById("text");
    input.addEventListener("keyup", function(event) {

        if (event.getModifierState("CapsLock")) {
            text.style.display = "block";
        } else {
            text.style.display = "none"
        }
    });
</script>

</body>
</html>
