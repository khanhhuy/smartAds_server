@extends('manager-master')

@section('title','Ads Management')

@section('head-footer')
    <link rel="stylesheet" type="text/css" href="{{asset('/datatables/datatables.min.css')}}"/>
@endsection

@section('content')
    <table id="manage-ads" class="display" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Areas</th>
            <th>Items</th>
            <th>From</th>
            <th>To</th>
            <th>D. Rate</th>
            <th>D. Value</th>
            <th>Added/Updated</th>
        </tr>
        </thead>
    </table>
@endsection

@section('body-footer')
    <script type="text/javascript" src="{{asset('/datatables/datatables.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#manage-ads').DataTable({
                "processing": true,
                "serverSide": true,
{{--                "ajax": "{{url('/tar'}}}"--}}
            });
        });
    </script>
@endsection