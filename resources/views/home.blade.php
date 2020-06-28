@extends('layouts.master')



@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-9">
                <table class="table">
                    <tbody>
                    <tr>
                        <td>
                            <table class="table">
                                <tbody>
                                @foreach($accounts as $row)
                                    @if($row->acc_type === 'A')
                                    <tr>
                                        <td>{!! $row->acc_name !!}</td>
                                        <td style="text-align: right">{!! number_format($row->curr_bal,0) !!}</td>
                                    </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </td>

                        <td>
                            <table class="table">
                                <tbody>
                                @foreach($accounts as $row)
                                    @if($row->acc_type === 'L' OR $row->acc_type === 'C')
                                        <tr>
                                            <td>{!! $row->acc_name !!}</td>
                                            <td style="text-align: right">{!! number_format(abs($row->curr_bal),2) !!}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </td>

                    </tr>



                    <tr>
                        <td>
                            <table class="table">
                                <tbody>
                                @foreach($accounts as $row)
                                    @if($row->acc_type === 'I')
                                        <tr>
                                            <td>{!! $row->acc_name !!}</td>
                                            <td style="text-align: right">{!! number_format(abs($row->curr_bal),2) !!}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </td>

                        <td>
                            <table class="table">
                                <tbody>
                                @foreach($accounts as $row)
                                    @if($row->acc_type === 'E')
                                        <tr>
                                            <td>{!! $row->acc_name !!}</td>
                                            <td style="text-align: right">{!! number_format(abs($row->curr_bal),2) !!}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </td>

                    </tr>


                    </tbody>
                </table>


            </div>
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-header">Last Used Menu</div>
                    @if(!empty($user_activities))
                        <div class="card-body" style="justify-content: center">
                            <table class="table table-striped table-success">
                                <tbody>
                                @foreach($user_activities as $menu)
                                    <tr>
                                        <td><a href="{!! url(''.$menu->menus->url.'') !!}" class="btn btn-facebook" style="width: 240px">{!! $menu->menus->name !!}</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            @endif
                        </div>
                </div>

            </div>
        </div>

    </div>













{{--<div class="container">--}}


{{--    <div id="new-product" class="row col-md-8" style="border-right: solid; overflow: scroll; height: 600px">--}}
{{--        <table class="table table-striped table-striped table-responsive table-success">--}}
{{--            <tbody>--}}
{{--            <tr>--}}
{{--                <td><label for="name" class="control-label">Name <span style="color: red">*</span></label></td>--}}
{{--                <td colspan="3"><input id="name" type="text" class="form-control" name="name" value=""  autocomplete="off"></td>--}}
{{--            </tr>--}}

{{--            <tr>--}}
{{--                <td><label for="reorder_point" class="control-label">Reorder Level</label></td>--}}
{{--                <td><input id="reorder_point" type="text" class="form-control" name="reorder_point" value="{!! 0 !!}" required></td>--}}
{{--                <td><label for="expiry_date" class="control-label" style="text-align: left">Expiry Date</label></td>--}}
{{--                <td><input id="expiry_date" type="text" class="form-control" name="expiry_date" value="{!! \Carbon\Carbon::now()->format('d-m-Y') !!}" readonly></td>--}}
{{--            </tr>--}}

{{--            <tr>--}}
{{--                <td><label for="description_short" class="control-label">Short Description</label></td>--}}
{{--                <td>{!! Form::textarea('description_short',null,['id'=>'description_short','size' => '20x3','class'=>'field']) !!}</td>--}}
{{--                <td><label for="description_long" class="control-label">Long Description</label></td>--}}
{{--                <td>{!! Form::textarea('description_long',null,['id'=>'description_long','size' => '20x3','class'=>'field']) !!}</td>--}}
{{--            </tr>--}}

{{--            </tbody>--}}
{{--        </table>--}}
{{--    </div>--}}

{{--    <div style="width: 5px"></div>--}}

{{--    <div class="row col-md-2">--}}
{{--        <article>--}}
{{--            <h1>Help Tips</h1>--}}
{{--            <div class="card">--}}
{{--                <div class="card-header">Last Used Menu</div>--}}
{{--                @if(!empty($user_activities))--}}
{{--                    <div class="card-body" style="justify-content: center">--}}
{{--                        <table class="table table-striped table-success">--}}
{{--                            <tbody>--}}
{{--                            @foreach($user_activities as $menu)--}}
{{--                                <tr>--}}
{{--                                    <td><a href="{!! url(''.$menu->menus->url.'') !!}" class="btn btn-facebook" style="width: 240px">{!! $menu->menus->name !!}</a></td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--            </div>--}}
{{--        </article>--}}
{{--    </div>--}}


















{{--    <table width="100%">--}}
{{--        <tbody>--}}
{{--            <tr>--}}
{{--                <td width="70%">--}}
{{--                    <div class="col-md-12">--}}
{{--                        <div class="card">--}}
{{--                            <div class="card-header">Dashboard</div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </td>--}}

{{--                <td width="30%">--}}
{{--                    <div class="col-md-12">--}}
{{--                        <div class="card">--}}
{{--                            <div class="card-header">Last Used Menu</div>--}}
{{--                            @if(!empty($user_activities))--}}
{{--                                <div class="card-body" style="justify-content: center">--}}
{{--                                    <table class="table table-striped table-success">--}}
{{--                                        <tbody>--}}
{{--                                        @foreach($user_activities as $menu)--}}
{{--                                            <tr>--}}
{{--                                                <td><a href="{!! url(''.$menu->menus->url.'') !!}" class="btn btn-facebook" style="width: 240px">{!! $menu->menus->name !!}</a></td>--}}
{{--                                            </tr>--}}
{{--                                        @endforeach--}}
{{--                                        </tbody>--}}
{{--                                    </table>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </td>--}}
{{--            </tr>--}}
{{--            <tr>--}}

{{--                <td width="70%">--}}

{{--                    <div class="table-responsive">--}}
{{--                        <table class="table">--}}
{{--                            <tbody>--}}
{{--                                <td>--}}
{{--                                    <div class="col-md-12">--}}
{{--                                        <div class="card">--}}
{{--                                            <div class="card-header">Asset</div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </td>--}}

{{--                                <td>--}}
{{--                                    <div class="col-md-12">--}}
{{--                                        <div class="card">--}}
{{--                                            <div class="card-header">Liability</div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </td>--}}

{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}


{{--                </td>--}}

{{--                <td width="30%">--}}


{{--                </td>--}}


{{--            </tr>--}}
{{--        </tbody>--}}
{{--    </table>--}}
{{--</div>--}}
@endsection
