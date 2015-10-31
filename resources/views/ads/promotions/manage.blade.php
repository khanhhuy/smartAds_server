@extends('ads.partials.manage-master')

@section('title','Manage Promotions')

@section('table-header')
    <thead>
    <tr>
        <th id="select-all-chkbox"></th>
        <th class="sorting">ID</th>
        <th>Items</th>
        <th>Areas</th>
        <th>From</th>
        <th>To</th>
        <th>D. Rate</th>
        <th>D. Value</th>
        <th>Action</th>
    </tr>
    <tr>
        <td></td>
        {!! Utils::genSearchCell('id') !!}
        {!! Utils::genSearchCell('items') !!}
        {!! Utils::genSearchCell('areas') !!}

        @include('ads.partials.search-from-to')
        <td>
            <div>
                <input type="number" name="search_rate_from" id="search_rate_from" ,
                       class="form-control table-search search_rate" min="0.01" step="0.01" max="100" ,
                       placeholder=" From"/>
            </div>
            <input type="number" name="search_rate_to" id="search_rate_to" ,
                   class="form-control table-search search_rate" min="0.01" step="0.01" max="100" ,
                   placeholder=" To"/>
        </td>
        <td>
            <div>
                <input type="number" name="search_value_from" id="search_value_from" ,
                       class="form-control table-search search_value" min="0.001" ,
                       placeholder=" From"/>
            </div>
            <input type="number" name="search_value_to" id="search_value_to" ,
                   class="form-control table-search search_value" min="0.001" ,
                   placeholder=" To"/>
        </td>
        @include('ads.partials.search-action-group')
    </tr>
    </thead>

@endsection

@section('body-footer')
    <script>
        var myTableURL = "{{url('/ads/promotions/table')}}";
        var myOrder = [];
        var myDom = "<'row'<'col-sm-7'lB><'col-sm-5'i>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12'p>>";
        var myDeleteURL = '{{route('ads.deleteMulti')}}';
        var myColumns = [
            {
                data: 0,
            },
            {
                "width": "260px",
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
                orderable: false,
                searchable: false,
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
        var COLS = ["id", "items", "areas", "from", "to", "rate", "value"];
        var divider = 4;
    </script>
    @include('ads.partials.search-footer-script')
@endsection