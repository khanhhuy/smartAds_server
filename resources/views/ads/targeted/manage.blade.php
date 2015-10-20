@extends('manager-master')

@section('title','Targeted Ads Management')

@section('head-footer')
    <link rel="stylesheet" type="text/css" href="{{asset('/datatables/datatables.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('/css/manage.css')}}"/>
@endsection

@section('content')
    <br/>
    <div class="row" id="manage-ads">
        <table id="manage-table" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
            <thead>
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
            </thead>
        </table>
    </div>
@endsection

@section('body-footer')
    <script>
        var myTableURL = "{{url('/ads/targeted/table')}}";
        var myOrder = [[7, 'desc']];
        var myColumns =
                [
                    {
                        sortable: false,
                        className: 'select-checkbox',
                        defaultContent: "",
                        data: null,
                        width: "15px"
                    },
                    {
                        data: 0,
                    },
                    {
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
                            return '<a class="my-edit-btn" role="button" href="' + row[0] + '/edit">' +
                                    '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit</a>';
                        }
                    }
                ];
    </script>

    @include('ads.partials.manage-footer-script')
@endsection
