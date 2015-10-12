@extends('manager-master')

@section('title','Ads Management')

@section('head-footer')
    <link rel="stylesheet" type="text/css" href="{{asset('/datatables/datatables.min.css')}}"/>
@endsection

@section('content')
    <div class="row">
            <table id="manage-ads" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Items</th>
                    <th>Areas</th>
                    <th>From</th>
                    <th>To</th>
                    <th>D. Rate</th>
                    <th>D. Value</th>
                    <th>Last Modified</th>
                </tr>
                </thead>
            </table>
    </div>
@endsection

@section('body-footer')
    <script type="text/javascript" src="{{asset('/datatables/datatables.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#manage-ads').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{url('/ads/table')}}"
            });
        });
    </script>
@endsection