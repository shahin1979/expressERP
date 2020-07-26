@extends('layouts.app')

@section('content')
    <script src="{!! asset('src/js/vendor/jquery-3.3.1.min.js') !!}"></script>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color: rgba(44,221,32,0.1); margin-bottom: 0.5rem">
            <li class="breadcrumb-item"><a class="white-text" href="{!! url('home') !!}">Home</a></li>
            <li class="breadcrumb-item active">Approve Export Delivery Products</li>
        </ol>
    </nav>


    <div class="container-fluid">
        <div class="row col-md-12 dataTables_wrapper" style="overflow-x:auto;">
            <table class="table table-bordered table-hover" id="items-table">
                <thead style="background-color: #c4e3f3">
                <tr>
                    <th>Challan</th>
                    <th>Vehicle No</th>
                    <th>Bale No</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th style="text-align:right">Total : </th>
                    <th></th>
                    <th>Balance</th>
                    <th></th>
                    <th>KG</th>
                </tr>
                </tfoot>
            </table>
        </div>

        <div class="row">
            {!! Form::open(['url' => 'delivery.item.post','method'=>'POST']) !!}
            <button name="action" type="submit" class="btn btn-primary btn-post pull-left" value="{!! 0 !!}">APPROVE</button>
            {!! Form::close() !!}
        </div>

    </div>

@endsection

@push('scripts')

    <script>

        var table= $('#items-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: 'delivery/items/approve',
            columns: [
                { data: 'ref_no', name: 'ref_no' },
                { data: 'vehicle_no', name: 'vehicle_no' },
                { data: 'bale_no', name: 'bale_no' },
                { data: 'item.name', name: 'item.name' },
                { data: 'quantity', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2 ), name: 'quantity' },
                { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
            ],
            "fnDrawCallback": function() {
                var api = this.api()
                var json = api.ajax.json();
                $(api.column(1).footer()).html(json.delivered);
                $(api.column(3).footer()).html(json.balance);
            },

            rowCallback: function( row, data, index ) {
                if(index%2 == 0){
                    $(row).removeClass('myodd myeven');
                    $(row).addClass('myodd');
                }else{
                    $(row).removeClass('myodd myeven');
                    $(row).addClass('myeven');
                }
            }

        });

        $('#items-table').on('click', '.btn-delete', function (e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var url = $(this).data('remote');
            // confirm then
            $.ajax({
                url: url,
                type: 'DELETE',
                dataType: 'json',
                data: {method: '_DELETE', submit: true}
            }).always(function (data) {
                $('#items-table').DataTable().draw(false);
            });
        });


    </script>

@endpush
