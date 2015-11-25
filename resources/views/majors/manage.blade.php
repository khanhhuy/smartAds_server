@extends('admin-master')

@section('title','Manage Majors - Stores')

@section('head-footer')
    <link rel="stylesheet" type="text/css" href="{{asset('/datatables/datatables.min.css')}}"
          xmlns="http://www.w3.org/1999/html"/>
    <link href="{{asset('/css/select2.min.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{asset('/css/major-manage.css')}}"/>
@endsection
@section('page-title','Manage Majors - Stores')
@section('content')
    <div class="row manage-page major-manage">
        <div class="col-sm-12 col-md-8">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="manage-table" class="table table-striped table-bordered table-hover" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th id="select-all-chkbox"></th>
                                <th>Store</th>
                                <th>Area</th>
                                <th>Major</th>
                                <th>Action</th>
                            </tr>
                            <tr>
                                <td></td>
                                {!! Utils::genSearchCell('store') !!}
                                {!! Utils::genSearchCell('area') !!}
                                @include('partials.search.number-from-to',['name'=>'major','min'=>'1','step'=>'1','max'=>'65535'])
                                @include('partials.search.action-group')
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-8 col-md-4">
            <div id="affix-form" data-spy="affix" data-offset-top="160">
                <div class="panel panel-default" id="form-container">
                    @include('majors.partials.create')
                </div>
            </div>
        </div>
    </div>
    @include('partials.delete-success')
@endsection

@section('breadcrumb')
    <li class="active">Manage Majors - Store</li>
@endsection

@section('body-footer')
    <script>
        var myTableURL = "{{url('/majors/table')}}";
        var myOrder = [];
        var myDom = "<'row'<'col-sm-6 col-lg-5'lB><'col-sm-6 col-lg-7'i>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12'p>>";
        var myDeleteURL = '{{route('majors.deleteMulti')}}';
        var myColumns = [
            {
                data: 0,
            },
            {
                data: 1,
            },
            {
                data: 2,
                width: "50px",
            },
            {
                orderable: false,
                width: "70px",
                searchable: false,
                render: function (data, type, row, meta) {
                    return '<button class="my-manage-edit-btn" role="button" onclick="loadEditForm(this)">' +
                            '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit</button>';
                }
            }
        ];

        var myIDIndex = 2;
        var myDelSuccessFunc = function () {
            loadCreateForm();
        }
        var myDrawCallbackFunc = function () {
            updateEditingRow(editingRow);
        }
    </script>
    @include('partials.manage-footer-script')
    <script src="{{asset('/js/select2.min.js')}}"></script>
    <script>
        function initForm() {
            $('#store_id').select2({
                escapeMarkup: function (markup) {
                    return markup;
                }
            });
            $('#form-container form').first().submit(function (e) {
                e.preventDefault();
                $('#my-submit-btn').prop('disabled', true);
                var form = $(this);
                var url = form.attr("action");
                var posting = $.post(url, form.serialize(), function (data) {
                    $('#form-container').html(data);
                    $('#my-submit-btn').prop('disabled', false);
                    if ($('#form-container .alert-danger').length === 0) {
                        updateEditingRow(-1);
                        table.draw(false);
                    }

                    initForm();
                })
                        .fail(function (jqXHR, type, errorThrown) {
                            if (errorThrown != null) {
                                alert(errorThrown);
                            }
                        });
                posting.always(function () {
                    $('#my-submit-btn').prop('disabled', false);
                })
            });

        }
        initForm();

        var editingRow = -1;
        function loadForm(url, alwaysFunc) {
            $('#my-submit-btn').prop('disabled', true);
            $('#form-container').load(url, function (response, status, xhr) {
                if (status == "error") {
                    var msg = "Sorry but there was an error: ";
                    alert(msg + xhr.status + " " + xhr.statusText);
                    $('#my-submit-btn').prop('disabled', false);
//                    $('#form-container').html(response);
                }

                initForm();
                if (alwaysFunc) {
                    alwaysFunc();
                }
            });
        }

        function loadEditForm(btn) {
            var row = table.row($(btn).closest('tr'));
            var major = row.data()[2];
            var url = "{{url('/majors')}}/" + major + "/edit";
            loadForm(url, function () {
                $('#major').focus();
            });
            updateEditingRow(major);
        }

        function loadCreateForm() {
            var url = "{{route('majors.create')}}";
            loadForm(url);
            updateEditingRow(-1);
        }
        $(document).ready(function () {
            var style = $('<style>.affix { width: ' + $('#affix-form').width() + 'px; }</style>');
            $('html > head').append(style);
        });

        function updateEditingRow(newMajor) {
            if (editingRow >= 0) {
                $('tr#' + editingRow).removeClass('editing-row');
            }
            editingRow = newMajor;
            if (editingRow >= 0) {
                $('tr#' + editingRow).addClass('editing-row');
            }
        }

        //for search
        var COLS = ["store", "area", "major"];
        var divider = 3;
    </script>

    @include('partials.search.footer-script')
@endsection