@extends('ads.partials.manage-master')

@section('title','Manage Targeted Ads')

@section('table-header')
    <tr>
        <th id="select-all-chkbox"></th>
        <th>ID</th>
        <th>Title</th>
        <th>Areas</th>
        <th>Targeted Customers</th>
        <th>From</th>
        <th>To</th>
        <th>Updated At</th>
        <th>Action</th>
    </tr>
@endsection

@section('body-footer')
    <script>
        var myTableURL = "{{url('/ads/targeted/table')}}";
        var myOrder = [[7, 'desc']];
        var myDeleteURL = '{{route('ads.deleteMulti')}}';
        var myColumns =
                [
                    {
                        data: 0,
                    },
                    {
                        "width": "230px",
                        data: 1,
                    },
                    {
                        data: 2,
                    },
                    {
                        data: 3,
                    },
                    {
                        "className": "dt-right",
                        data: 4,
                    },
                    {
                        "className": "dt-right",
                        data: 5,
                    },
                    {
                        data: 6,
                    },
                    {
                        orderable: false,
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
