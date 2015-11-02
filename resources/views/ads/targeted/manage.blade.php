@extends('ads.partials.manage-master')

@section('title','Manage Targeted Ads')

@section('table-header')
    <thead>
    <tr>
        <th id="select-all-chkbox"></th>
        <th>ID</th>
        <th>Title</th>
        <th>Areas</th>
        <th>Targeted Customers</th>
        <th>From</th>
        <th>To</th>
        <th>Action</th>
    </tr>
    <tr>
        <td></td>
        {!! Utils::genSearchCell('id') !!}
        {!! Utils::genSearchCell('title') !!}
        {!! Utils::genSearchCell('areas') !!}
        {!! Utils::genSearchCell('targeted_customers') !!}
        @include('partials.search.start-end-date')
        @include('partials.search.action-group')
    </tr>
    </thead>
@endsection

@section('body-footer')
    <script>
        var myTableURL = "{{url('/ads/targeted/table')}}";
        var myOrder = [];
        var myDom = "<'row'<'col-sm-7'lB><'col-sm-5'i>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12'p>>";
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
                        orderable: false,
                    },
                    {
                        data: 4,
                    },
                    {
                        data: 5,
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

    <script>
        var COLS = ["id", "title", "areas", "targeted_customers", "from", "to"];
        var divider = 5;
    </script>
    @include('partials.search.footer-script')
@endsection
