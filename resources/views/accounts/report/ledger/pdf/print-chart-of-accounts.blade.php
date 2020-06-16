@extends('layouts.pdf')
@section('content')
    <table class="table">

        <tr>
            <td width="15%"><img src="{!! public_path('company/'.$users_company->company_id.'_logo.jpg') !!}" style="width:50px;height:50px;"></td>
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
            <td style="text-align: center">Chart of Accounts</td>
        </tr>
        </tbody>

    </table>


    {{--    <div class="blank-space"></div>--}}
    @php($i=1)

    @if(!empty($report))

        <div class="row">
            @foreach($groups as $group)
            <p style="font-size:10pt; font-weight: bold">{!! $group->ledger_code !!} : {!! $group->acc_name !!}</p>
            <table class="table">
                <thead>
                    <tr class="row-line" style="border-bottom: solid; line-height: 200%;">
                        <th width="5%" style="font-size:10pt">SL</th>
                        <th width="10%" style="font-size:10pt">Acc No</th>
                        <th width="50%" style="font-size:10pt">Name</th>
                        <th width="15%" style="font-size:10pt;">Type</th>
                        <th width="20%" style="font-size:10pt;">Sub Type</th>
                    </tr>

                </thead>
                <tbody>

                @foreach($report as $row)
                    @if($row->ledger_code == $group->ledger_code)
                        <tr style="line-height: 200%">
                            <td width="5%" style="border-bottom-width:1px; font-size:8pt;">{!! $i !!}</td>
                            <td width="10%" style="border-bottom-width:1px; font-size:8pt;">{!! $row->acc_no !!}</td>
                            <td width="50%" style="border-bottom-width:1px; font-size:8pt;">{!! $row->acc_name !!}</td>
                            <td width="15%" style="border-bottom-width:1px; font-size:8pt;">{!! $row->acc_type == 'A' ? 'Asset' : ($row->acc_type == 'L' ? 'Liability' : ($row->acc_type == 'I' ? 'Income' : ($row->acc_type == 'E' ? 'Expenditure' : 'Capital'))) !!}</td>
                            <td width="20%" style="border-bottom-width:1px; font-size:8pt;">{!! $row->details->description !!}</td>
                        </tr>
                        @php($i ++ )
                    @endif
                @endforeach


                </tbody>

            </table>
            @endforeach
        </div>
    @endif

@endsection
