@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Sale Rate Approve</li>
        </ol>
    </nav>

    <div class="card">

        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead>
                <tr>
                    <th rowspan="2">Product Code</th>
                    <th rowspan="2">Product Name</th>
                    <th rowspan="2">Product Unit</th>
                    <th colspan="2" style="text-align: center">Current Rate</th>
                    <th colspan="2" style="text-align: center">Updated Rate</th>
                    <th rowspan="2" style="text-align: center">Action</th>
                </tr>
                <tr>
                    <th>Wholesale</th>
                    <th>Retail</th>
                    <th>Wholesale</th>
                    <th>Retail</th>
                </tr>

                </thead>
                <tbody>
                @foreach($products as  $i=> $item)

                    <tr style="background-color: {{ $i % 2 == 0 ? '#ffffff': '#afffff' }};">
                        <td>{{ $item->item->id }}</td>
                        <td>{{ $item->item->name }}</td>
                        <td>{{ $item->item->unit_name }}</td>
                        <td align="right">{{ $item->item->wholesale_price }}</td>
                        <td align="right">{{ $item->item->retail_price }}</td>
                        <td align="right">{{ $item->wholesale_price }}</td>
                        <td align="right">{{ $item->retail_price }}</td>
                        <td>
                            <button type="submit" id="product-{!! $i !!}" value="{!! $item->id !!}"
                                    class="btn btn-rate-approve btn-primary btn-sm">Approve
                            </button>
                            <button type="submit" id="product-{!! $i !!}" value="{!! $item->id !!}"
                                    class="btn btn-rate-reject btn-danger btn-sm">Reject
                            </button>
                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>

        </div>

    </div>




@endsection

@push('scripts')
    <script>


        $(document).on('click', '.btn-rate-approve', function () {
            // e.preventDefault();

            input_id = $(this).attr('id').split('-');
            item_id = parseInt(input_id[input_id.length - 1]);
            var $tr = $(this).closest('tr');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = 'approveProductRate';

            // confirm then
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {
                    method: '_POST', submit: true, row_id: $('#product-' + item_id).val(),
                },
                error: function (request) {
                    alert(request.responseText);
                },
                success: function (data) {
                    alert(data.success);
                    $tr.find('td').fadeOut(1000, function () {
                        $tr.remove();
                    });
                },
            });
        });

        $(document).on('click', '.btn-rate-reject', function () {
            // e.preventDefault();

            input_id = $(this).attr('id').split('-');
            item_id = parseInt(input_id[input_id.length - 1]);
            var $tr = $(this).closest('tr');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = 'rejectProductRate';

            // confirm then
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {
                    method: '_POST', submit: true, row_id: $('#product-' + item_id).val(),
                },
                error: function (request) {
                    alert(request.responseText);
                },
                success: function (data) {
                    alert(data.success);
                    $tr.find('td').fadeOut(1000, function () {
                        $tr.remove();
                    });
                },
            });
        });


    </script>
@endpush
