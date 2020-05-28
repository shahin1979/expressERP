<div id="new-center" class="col-md-8">
    {!! Form::open(['url'=>'costcenter/saveNewCenterIndex','method'=>'POST']) !!}
    <table width="50%" class="table table-bordered table-striped table-hover">
        <tbody>
        <tr>
            <td><label for="name" class="control-label">Name</label></td>
            <td><input id="name" type="text" class="form-control" name="name" value="" required autofocus></td>
            <td><label for="cyr_total" class="control-label">Current Year</label></td>
            <td><input id="cyr_total" style="background-color: #f1f1f1" type="text" class="form-control text-right" name="cyr_total" value=""></td>
        </tr>
        <tr>
            <td><label for="bgt_01" class="control-label">{!! $months[0]->month_name !!}</label></td>
            <td><input id="bgt_01" type="text" class="form-control text-right" name="bgt_01" value="0" required></td>
            <td><label for="bgt_02" class="control-label">{!! $months[1]->month_name !!}</label></td>
            <td><input id="bgt_02" type="text" class="form-control text-right" name="bgt_02" value="0" required></td>
        </tr>

        <tr>
            <td><label for="bgt_03" class="control-label">{!! $months[2]->month_name !!}</label></td>
            <td><input id="bgt_03" type="text" class="form-control text-right" name="bgt_03" value="0" required></td>
            <td><label for="bgt_04" class="control-label">{!! $months[3]->month_name !!}</label></td>
            <td><input id="bgt_04" type="text" class="form-control text-right" name="bgt_04" value="0" required></td>
        </tr>

        <tr>
            <td><label for="bgt_05" class="control-label">{!! $months[4]->month_name !!}</label></td>
            <td><input id="bgt_05" type="text" class="form-control text-right" name="bgt_05" value="0" required></td>
            <td><label for="bgt_06" class="control-label">{!! $months[5]->month_name !!}</label></td>
            <td><input id="bgt_06" type="text" class="form-control text-right" name="bgt_06" value="0" required></td>
        </tr>

        <tr>
            <td><label for="bgt_07" class="control-label">{!! $months[6]->month_name !!}</label></td>
            <td><input id="bgt_07" type="text" class="form-control text-right" name="bgt_07" value="0" required></td>
            <td><label for="bgt_08" class="control-label">{!! $months[7]->month_name !!}</label></td>
            <td><input id="bgt_08" type="text" class="form-control text-right" name="bgt_08" value="0" required></td>
        </tr>

        <tr>
            <td><label for="bgt_09" class="control-label">{!! $months[8]->month_name !!}</label></td>
            <td><input id="bgt_09" type="text" class="form-control text-right" name="bgt_09" value="0" required></td>
            <td><label for="bgt_10" class="control-label">{!! $months[9]->month_name !!}</label></td>
            <td><input id="bgt_10" type="text" class="form-control text-right" name="bgt_10" value="0" required></td>
        </tr>

        <tr>
            <td><label for="bgt_11" class="control-label">{!! $months[10]->month_name !!}</label></td>
            <td><input id="bgt_11" type="text" class="form-control text-right" name="bgt_11" value="0" required></td>
            <td><label for="bgt_12" class="control-label">{!! $months[11]->month_name !!}</label></td>
            <td><input id="bgt_12" type="text" class="form-control text-right" name="bgt_12" value="0" required></td>
        </tr>

        </tbody>

        <tfoot>
        <tr>
            <td colspan="4"><button type="submit" id="btn-category-save" class="btn btn-primary btn-category-save">Submit</button></td>
        </tr>
        </tfoot>

    </table>
    {!! Form::close() !!}
</div>
