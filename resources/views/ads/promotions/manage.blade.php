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

        <td>
            <form class="search-form">
                <div>
                    <input class="form-control table-search search_date" type="text" name="search_from_from"
                           id="search_from_from"
                           placeholder="  From"
                           onfocus="(this.type='date')"/>
                </div>
                <input class="form-control table-search search_date" name="search_from_to" id="search_from_to"
                       placeholder="  To" type="text" onfocus="(this.type='date')"/>
            </form>
        </td>
        <td>
            <form class="search-form">
                <div>
                    <input class="form-control table-search search_date" type="text"
                           name="search_to_from" id="search_to_from" placeholder="  From"
                           onfocus="(this.type='date')"/>
                </div>
                <input class="form-control form-control table-search search_date"
                       name="search_to_to" id="search_to_to"
                       placeholder="  To" type="text" onfocus="(this.type='date')"/>
            </form>
        </td>
        <td>
            <form class="search-form">
                <div>
                    <input type="number" name="search_rate_from" id="search_rate_from" ,
                           class="form-control table-search search_rate" min="0.01" step="0.01" max="100" ,
                           placeholder="  From"/>
                </div>
                <input type="number" name="search_rate_to" id="search_rate_to" ,
                       class="form-control table-search search_rate" min="0.01" step="0.01" max="100" ,
                       placeholder="  To"/>
            </form>
        </td>
        <td>
            <form class="search-form">
                <div>
                    <input type="number" name="search_value_from" id="search_value_from" ,
                           class="form-control table-search search_value" min="0.001" ,
                           placeholder="  From"/>
                </div>
                <input type="number" name="search_value_to" id="search_value_to" ,
                       class="form-control table-search search_value" min="0.001" ,
                       placeholder="  To"/>
            </form>
        </td>
        <td>
            <div>
                <button type="button" id="btn_search" class="btn btn-default btn-sm my-search-btn"
                        onclick="search()">
                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search
                </button>
            </div>
            <button type="button" id="btn_reset" class="btn btn-default btn-sm my-reset-btn">
                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Reset
            </button>
        </td>
    </tr>
    </thead>

@endsection

@section('body-footer')
    <script>
        var myTableURL = "{{url('/ads/promotions/table')}}";
        var myOrder = [];
        var myDom = "<'row'<'col-sm-12'lB>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-4 col-lg-3'i><'col-sm-8 col-lg-9'p>>";
        var myDeleteURL = '{{route('ads.deleteMulti')}}';
        var myColumns = [
            {
                data: 0,
            },
            {
                "width": "245px",
                render: "[, ]",
                data: 1,
            },
            {
                "width": "135px",
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
        function search() {
            for (var i = 1; i < 8; i++) {
                var col = table.column(i);
                if (i < 4) {
                    var selector = "input#search_" + COLS[i - 1];
                    col.search($(selector).val());
                }
            }
            table.draw();

        }
        $('input.table-search').keypress(function (e) {
            if (e.which == 13 || e.keyCode == 13) {
                search();
            }
        });
    </script>
@endsection