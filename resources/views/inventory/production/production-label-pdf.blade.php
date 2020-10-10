@extends('layouts.pdf')
@section('content')

    <table border="0" cellpadding="0">

        <tr>
            <td width="33%"><img src="{!! public_path('company/mumanu_b.jpg') !!}"></td>
            <td width="2%"></td>
            <td width="65%"><span style="font-family:times;font-weight:bold; padding-left: 100px; line-height: 130%; height: 300%; font-size:46pt;color:black;">Polyester Industries Ltd.</span></td>

        </tr>
        <tr>
            <td colspan="3"><span style="line-height: 60%; text-align:center; font-family:times;font-weight:bold;font-size:25pt;color:black;">Panishail, Singair, Manikganj, Bangladesh</span></td>
        </tr>
        <hr style="height: 2px">

    </table>
    <div style="width: 5px"></div>
    <div style="width: 5px"></div>

    <div>
        <table style="width:100%">
            <tr>
                <td style="width:20%"></td>
                <td style="width:60%">
                    <table border="1" style="width:100%">
                        <thead>
                        <tr>
                            <td style="width:100%" colspan="2"><span style="text-align:center; border: #000000; font-family:times;font-weight:bold;font-size:35pt;color:#000000;">Polyester Staple Fiber (PSF)</span></td>
                        </tr>
                        </thead>
                    </table>
                </td>
                <td style="width:20%"></td>
            </tr>
        </table>
    </div>

    <div>
        <table cellpadding="3">

            <tr>
                <td colspan="2" width="130mm"><span style="font-family:times;font-weight:bold;font-size:30pt;color:black;">1.  Bale No</span></td>
                <td width="10mm"><span style="font-family:times;font-weight:bold;font-size:30pt;color:black;">:</span></td>
                <td colspan="2"><span style="font-family:times;font-weight:bold;font-size:30pt;color:black;">{!! $item->bale_no !!}</span></td>
            </tr>
            <tr style="line-height: 100%">
                <td width="130mm"><span style="font-family:times;font-weight:bold;font-size:30pt;color:black;">2.  Specification</span></td>
                <td width="10mm"><span style="font-family:times;font-weight:bold;font-size:30pt;color:black;">:</span></td>
                <td width="35mm"><span style="font-family:times;font-weight:bold;font-size:30pt;color:black;">{!! $item->item->model->name !!}</span></td>
                <td width="15mm"><span style="font-family:times;font-weight:bold;font-size:30pt;color:black;"> X</span></td>
                <td width="50mm"><span style="font-family:times;font-weight:bold;font-size:30pt;color:black;">{!! $item->item->size->size !!}</span></td>
            </tr>

            <tr style="line-height: 100%">
                <td width="140mm"></td>
                <td width="140mm" colspan="3" style="padding-left: 100px"><span style="text-align: left; font-family:times;font-weight:bold;font-size:20pt;color:black;">(Semi &nbsp; Dull &nbsp; Raw &nbsp; White)</span></td>
            </tr>


            <tr style="line-height: 100%">
                <td colspan="2" width="130mm"><span style="font-family:times;font-weight:bold;font-size:30pt;color:black;">3.  Gross Weight</span></td>
                <td width="10mm"><span style="font-family:times;font-weight:bold;font-size:30pt;color:black;">:</span></td>
                <td width="40mm"><span style="font-family:times;font-weight:bold;font-size:30pt;color:black;">{!! number_format($item->gross_weight,2) !!}</span></td>
                <td><span style="font-family:times;font-weight:bold;font-size:30pt;color:black;">KG</span></td>
            </tr>
            <tr style="line-height: 100%">
                <td colspan="2" width="130mm"><span style="font-family:times;font-weight:bold;font-size:30pt;color:black;">4.  Net Weight</span></td>
                <td width="10mm"><span style="font-family:times;font-weight:bold;font-size:30pt;color:black;">:</span></td>
                <td width="40mm"><span style="font-family:times;font-weight:bold;font-size:30pt;color:black;">{!! number_format($item->quantity_in,2) !!}</span></td>
                <td><span style="font-family:times;font-weight:bold;font-size:30pt;color:black;">KG</span></td>
            </tr>
            <tr style="line-height: 100%">
                <td colspan="2" width="130mm"><span style="font-family:times;font-weight:bold;font-size:30pt;color:black;">5.  Manufacturing Date</span></td>
                <td width="10mm"><span style="font-family:times;font-weight:bold;font-size:30pt;color:black;">:</span></td>
                <td width="60mm" colspan="2"><span style="font-family:times;font-weight:bold;font-size:30pt;color:black;">{!! \Carbon\Carbon::parse($item->tr_date)->format('d/m/Y')!!}</span></td>
            </tr>



        </table>

    </div>


@endsection
