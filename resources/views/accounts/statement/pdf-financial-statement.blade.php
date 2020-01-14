@extends('layouts.pdf')
@section('content')
    <table class="table">

        <tr>
            <td width="15%"><img src="{!! public_path('company/'.$users_company->company_id.'_logo.jpg') !!}" style="width:250px;height:60px;"></td>
            <td width="50%" style="text-align: left"><span style="font-family:times;font-weight:bold; padding-right: 100px; line-height: 130%; height: 300%; font-size:15pt;color:black;">{!! $users_company->company->name !!}</span></td>
            <td width="35%" style="text-align: right"><span style="font-family:times;font-weight:bold; padding-right: 100px; line-height: 130%; height: 300%; font-size:12pt;color:black;">{!! $users_company->company->address !!}</span></td>

        </tr>
        <hr style="height: 2px">
    </table>

    <div class="blank-space"></div>
    <div class="blank-space"></div>

    <table class="table table-responsive">
        <tbody>
        <tr>
            <td style="text-align: center">{!! $statement->file_no !!} : {!! $statement->file_desc !!} As On {!! \Carbon\Carbon::parse($statement->value_date)->format('d-m-Y') !!}</td>
        </tr>
        </tbody>

    </table>


    {{--    <div class="blank-space"></div>--}}


    @if(!empty($data))

        <div class="row">
                <table class="table">
                    <thead>
                    <tr class="row-line" style="border-bottom: solid; line-height: 200%;">
                        <th colspan="3" width="60%" style="font-size:10pt;">Particulars</th>
                        <th width="10%" style="font-size:10pt;">Note</th>
                        <th width="30%" style="font-size:10pt; text-align:center">Amount</th>
                    </tr>

                    </thead>
                    <tbody>
                    @foreach($data as $i => $row)
                        <tr style="line-height: 200%;">

                            @if($row->text_position == 5)
                                <td colspan="3" width="60%" style="border-bottom-width:0.2px; font-size:12pt;">{!! $row->texts !!}</td>
                                <td width="10%" style="border-bottom-width:0.2px; font-size:12pt;">{!! $row->note !!}</td>
                                @if($row->text_position ==60)
                                    <td width="25%" align="right" style="border-bottom-width:0.2px; font-size:12pt;">{!! (number_format(abs($row->print_val),2)) !!}</td>
                                @endif
                            @endif

                            @if($row->text_position == 10)
                                <td width="10%"></td>
                                <td colspan="2" width="50%" style="border-bottom-width:0.2px; font-size:10pt; font-weight: bold">{!! $row->texts !!}</td>
                                <td width="10%" style="border-bottom-width:0.2px; font-size:10pt; font-weight: bold">{!! $row->note !!}</td>
                                    <td width="25%" align="right" style="border-bottom-width:0.2px; font-size:10pt; font-weight: bold">{!! (number_format(abs($row->print_val),2)) !!}</td>

{{--                                @if($row->text_position == 60)--}}
{{--                                    @if($row->line_no == 600 and $row->file_no=='PL01' and $row->print_val > 0)--}}
{{--                                        <td width="25%" align="right" style="border-bottom-width:0.2px; font-size:10pt; font-weight: bold">{!! (number_format(abs($row->print_val),2)) !!}(Loss)</td>--}}
{{--                                    @else--}}
{{--                                        <td width="25%" align="right" style="border-bottom-width:0.2px; font-size:10pt; font-weight: bold">{!! (number_format(abs($row->print_val),2)) !!}</td>--}}
{{--                                    @endif--}}
{{--                                @endif--}}

                            @endif

                            @if($row->text_position == 15)
                                <td colspan="2"  width="15%"></td>
                                <td width="45%" style="border-bottom-width:0.2px; font-size:10pt;">{!! $row->texts !!}</td>
                                <td width="10%" style="border-bottom-width:0.2px; font-size:10pt;">{!! $row->note !!}</td>
                                @if($row->figure_position ==60)
                                    <td width="25%" align="right" style="border-bottom-width:0.2px; font-size:10pt;">{!! (number_format(abs($row->print_val),2)) !!}</td>
                                @endif
                            @endif
                        </tr>
                    @endforeach
                    </tbody>



                </table>
        </div>
    @endif

@endsection
