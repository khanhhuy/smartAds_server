@extends('ads.partials.manage-master')

@section('title','Manage Promotions')

@section('table-header')
    <tr>
        <th id="select-all-chkbox"></th>
        <th>ID</th>
        <th>Items</th>
        <th>Areas</th>
        <th>From</th>
        <th>To</th>
        <th>D. Rate</th>
        <th>D. Value</th>
        <th>Updated At</th>
        <th>Action</th>
    </tr>
@endsection

@section('body-footer')
    <script>
        var myTableURL = "{{url('/ads/promotions/table')}}";
        var myOrder = [[8, 'desc']];
        var myDeleteURL = '{{route('ads.deleteMulti')}}';
        var myColumns = [
            {
                data: 0,
            },
            {
                "width": "300px",
                render: "[, ]",
                data: 1,
            },
            {
                "width": "150px",
                render: "[, ]",
                data: 2,
            },
            {
                data: 3,
            },
            {
                data: 4,
            },
            {
                "className": "dt-right",
                data: 5,
            },
            {
                "className": "dt-right",
                data: 6,
            },
            {
                data: 7,
            },
            {
                orderable: false,
                searchable:false,
                render: function (data, type, row, meta) {
                    return '<a class="my-manage-edit-btn" role="button" href="' + row[0] + '/edit">' +
                            '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit</a>';
                }
            }
        ];
        @include('partials.fixed-pos-message-script')
    </script>

    @include('partials.manage-footer-script')
@endsection