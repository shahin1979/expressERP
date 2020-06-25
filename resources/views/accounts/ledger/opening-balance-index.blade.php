@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Opening Balance</li>
        </ol>
    </nav>
    @isset($ledgers)

    <div class="container-fluid">

        <table>
            <tbody>
            <td width="25%"></td>
            <td width="50%">
                <table class="table table-bordered table-striped table-responsive">
                    <thead style="background-color: rgba(44,221,32,0.1)">
                    <th>Name</th>
                    <th style="text-align: right">Debit Amt</th>
                    <th style="text-align: right">Credit Amt</th>
                    </thead>
                    <tbody>
                    @foreach($ledgers as $i=>$row)
                        <tr style="background-color: {!! $i % 2 === 0 ? '#ffffff': '#a9cce3'  !!};">
                            <td>{!! $row->acc_no !!} : {!! $row->acc_name !!}</td>
                            <td style="text-align: right">{!! number_format($row->opn_dr,2) !!}</td>
                            <td style="text-align: right">{!! $row->opn_cr !!}</td>
                        </tr>
                    </tbody>
                    @endforeach
                    <tfoot>
                    <tr>
                        <td style="color: darkred; font-weight: bold">Total</td>
                        <td style="color: darkred; font-weight: bold">{!! number_format($ledgers->sum('opn_dr'),2) !!}</td>
                        <td style="color: darkred; font-weight: bold">{!! number_format($ledgers->sum('opn_cr'),2) !!}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><button type="submit" name="action" id="action" value="approve" class="btn btn-primary btn-approve {!! $ledgers->sum('opn_dr') == $ledgers->sum('opn_cr') ? '' : 'disabled' !!} ">Post</button></td>
{{--                        <td><button type="submit" class="btn btn-danger btn-back pull-right">Back</button></td>--}}
                    </tr>
                    </tfoot>
                </table>
            </td>
            <td width="25%"></td>

            </tbody>
        </table>
    </div>
    @endisset


@endsection

@push('scripts')

<script>
    $(document).on('click', '.btn-back', function (e) {
        window.location = 'home';
    });

    $('.btn-approve').prop("disabled", true).click(function() {

        var r = confirm('Are You Sure ?')
        if(r){window.location = 'openingPost';}else{return false;}

        // console.log('Moving forward...');
    })

    // $(document).on('click', '.btn-approve', function (e) {
    //
    //
    // });

</script>

@endpush
