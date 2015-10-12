@extends('manager-master')

@section('title','Ads Management')

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
                <th></th>
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
            </thead>
        </table>
    </div>
@endsection

@section('body-footer')
    <script type="text/javascript" src="{{asset('/datatables/datatables.min.js')}}"></script>
    <script>
        $('#manage-table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{url('/ads/table')}}",
            "columns": [
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
                    "width": "330px",
                    render: "[, ]",
                    data: 1,
                },
                {
                    "width": "120px",
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
                    render: function (data, type, row, meta) {
                        return '<a class="my-edit-btn" role="button" href="ads/' + row[0] + '/edit">' +
                                '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit</a>';
                    }
                }
            ],
            select: {
                style: 'os',
                selector: 'td:first-child'
            },
            order: [[8, 'desc']],
            dom: "<'row'<'col-sm-6'lB><'col-sm-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            lengthChange: true,
            buttons: {
                buttons: [
                    {
                        extend: 'selected',
                        text: '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete',
                        action: function (e, dt, button, config) {
                            var anSelected = fnGetSelected( dt );
                            $(anSelected).remove();
//                            dt.row('.selected').remove().draw( false );
                        },
                    }
                ],
                dom:{
                    container:{
                        className:'dt-buttons btn-group del-container'
                    },
                    button:{
                        className:'btn btn-default'
                    }
                }
            }
        });
    </script>
@endsection