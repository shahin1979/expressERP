@extends('layouts.master')



@section('content')
<div class="container">
    <div class="row justify-content-left">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <div>{!! $activity !!}</div>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Last Used Menu</div>
                @if(!empty($user_activities))
                <div class="card-body">
                    <table class="table table-striped table-success">
                        <tbody>
                            @foreach($user_activities as $menu)
                            <tr>
                                <td><a href="{!! url(''.$menu->menus->url.'') !!}" class="btn btn-facebook">{!! $menu->menus->name !!}</a></td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                @endif
                </div>
            </div>
        </div>
    </div>

    <div>
        @if($user->isOnline())
            user is online!!
        @endif
    </div>
</div>
@endsection
