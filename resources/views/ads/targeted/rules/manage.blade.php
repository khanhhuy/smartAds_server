@extends('manager-master')

@section('title','Targeted Rule Management')

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
        var myTableURL = "{{url('/ads/targeted/rules/table')}}";
        var myOrder = [[7, 'desc']];
        var myDeleteURL='{{route('ads.deleteMulti')}}';
        var myColumns =
                [
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
                ];
    </script>

    @include('partials.manage-footer-script')
@endsection
