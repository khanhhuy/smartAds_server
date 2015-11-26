@extends('admin-master')

@section('title','Manage Minors - Categories')

@section('head-footer')
    <link rel="stylesheet" type="text/css"
          href="{{asset('js/jQuery-bonsai/bower_components/jquery-bonsai/jquery.bonsai.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('js/jQuery-bonsai/bower_components/jquery-bonsai/assets/svg-icons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('/datatables/datatables.min.css')}}"
          xmlns="http://www.w3.org/1999/html"/>
    <link href="{{asset('/css/select2.min.css')}}" rel="stylesheet"/>

    <link rel="stylesheet" type="text/css" href="{{asset('/css/major-manage.css')}}"/>
    <link rel="stylesheet" type="text/css" href={!! URL::asset('css/admin-select.css') !!}>
    <link rel="stylesheet" type="text/css" href={!! URL::asset('css/admin.css') !!}>

    <script type="text/javascript"
            src={!! URL::asset('js/jQuery-bonsai/bower_components/jquery-qubit/jquery.qubit.js') !!} defer></script>
    <script type="text/javascript"
            src={!! URL::asset('js/jQuery-bonsai/bower_components/jquery-bonsai/jquery.bonsai.js') !!} defer></script>
    <style>
        #minor_id {
            margin-right: 15px;
        }

        #saveMinor label.control-label {
            width: 90px;
        }

        #btnCancel, #btnAddMinor, #btnSaveEdit {
            float: right;
        }

        #categories_label {
            margin-bottom: 0;
        }

        #btn_add_container {
            text-align: right;
        }

        .minor-manage #manage-table_info {
            text-align: left;
        }
    </style>
@endsection

@section('page-title','Manage Minors - Categories')

@section('content')
    <div class="row manage-page major-manage minor-manage">
        <div class="col-sm-offset-1 col-sm-10">
            <div class="panel panel-default no-heading-panel">
                <div class="panel-body">
                    <div id="category-tree" style="display: none;">
                        <div id="errors-container-div"></div>
                        {!! Form::open(array('route'=> 'admin.minors.store', 'id' => 'saveMinor')) !!}
                        <div class="form-group form-inline">
                            {!! Form::label('minor_id','Minor',['class'=>'control-label']) !!}
                            {!! Form::input('number','minor_id', null,['class'=>'form-control', 'id' => 'minor_id','required'=>'required', 'min'=>'1','step'=>'1','max'=>'65535']) !!}
                            <button type="button" class="btn btn-default my-cancel-btn" id="btnCancel">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel
                            </button>
                            <button type="button" class="btn btn-primary" id="btnAddMinor">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                Add
                            </button>
                            <button type="button" class="btn btn-primary" id="btnSaveEdit" style="display:none;">
                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save
                            </button>

                            <input type="submit" value="Submit" hidden>
                        </div>
                        <div style="clear:both"></div>
                        <div><label id="categories_label" class="control-label">Categories</label></div>
                        <div id="partial-tree"></div>
                        {!! Form::close() !!}
                    </div>
                    <div class="table-responsive" id="minor-table">
                        <table id="manage-table" class="table table-striped table-bordered table-hover" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th id="select-all-chkbox"></th>
                                <th>Categories</th>
                                <th>Minor</th>
                                <th>Action</th>
                            </tr>
                            <tr>
                                <td></td>
                                {!! Utils::genSearchCell('category') !!}
                                @include('partials.search.number-from-to',['name'=>'minor','min'=>'1','step'=>'1','max'=>'65535'])
                                @include('partials.search.action-group')
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partials.delete-success')
    @include('partials.hidden-success-message',['successMessage'=>"",
    'messageID'=>'my-success-message-container'])
    @include('partials.flash-overlay')
@endsection

@section('breadcrumb')
    <li class="active" id="breadcrumbManage">Manage Minors - Categories</li>
    <li class="active" id="breadcrumbAdd" style="display:none;">Add</li>
    <li class="active" id="breadcrumbEdit" style="display:none;">Edit</li>
@endsection

@section('body-footer')
    <script type="text/javascript">
        var myTableURL = "{{url('/minors/table')}}";
        var myOrder = [];
        var myDom = "<'row'<'col-sm-6'lB><'col-sm-6'<'#btn_add_container'>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-6'i><'col-sm-6'p>>";
        var myDeleteURL = "{{route('minors.deleteMulti')}}";
        var myColumns = [
            {
                data: 0,
                render: "[, ]",
            },
            {
                data: 1,
                width: "70px"
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

        var myIDIndex = 1;
    </script>
    @include('partials.manage-footer-script')
    <script>
        $('#btn_add_container').html('<button id="btnOpenAdd" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add</button>');
        var nextMinor = "{{$nextMinor}}";
        function toManagePanel() {
            $('#minor-table').slideToggle();
            $('#category-tree').slideToggle();
            $('#breadcrumbAdd').hide();
            $('#breadcrumbEdit').hide();
            $('#breadcrumbManage').text('Manage Minors - Categories');
            $('.page-title h1').text('Manage Minors - Categories');
            $('#btnOpenAdd').show();
        }

        function toAddEditPanel() {
            $('#errors-container-div').html('');
            $('.page-title h1').text('Add Minors - Categories');
            $('#minor-table').slideToggle('fast');
            $('#category-tree').slideToggle('fast');
            $('.bonsai input[type=checkbox]').prop('checked', false);
            $('.bonsai input[type=checkbox]').prop('indeterminate', false);
            $('#minor_id').val(nextMinor);
            $('ul.category').each(function () {
                var bonsai = $(this).data('bonsai');
                bonsai.collapseAll();
            });
            $('#btnOpenAdd').hide();
            $('#btnSaveEdit').hide();
            $('#btnAddMinor').show();
            $('#breadcrumbManage').html('<a href="{{url('/admin/minors')}}">Manage Minors - Categories</a>');
        }

        $('#btnOpenAdd').click(function () {
            toAddEditPanel();
            $('#breadcrumbAdd').toggle();
        });

        $('#btnCancel').click(function () {
            toManagePanel();
        });
        var addSuccessMessage = "{{Lang::get('flash.add_success')}}";
        var editSuccessMessage = "{{Lang::get('flash.edit_success')}}";
        function showSuccessMessage(message) {
            var container = $('.alert#my-success-message-container');
            container.find('#my_hidden_success_message_text').text(message);
            container.show().delay(3000).fadeOut('slow');
        }
        //add new minor
        $('#btnAddMinor').click(function (e) {
            $("body").css("cursor", "progress");
            e.preventDefault();
            $('#btnAddMinor').show();
            var $form = $('#saveMinor');
            if (!$form[0].checkValidity()) {
                $form.find(':submit').click();
                return;
            }
            var minor = $('#minor_id').val();
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: selectParent(),
                success: function (data) {
                    $("body").css("cursor", "default");
                    if (!$(data).is('.alert-danger')) {
                        table.draw(false);
                        $('#errors-container-div').html('');
                        toManagePanel();
                        showSuccessMessage(addSuccessMessage);
                        nextMinor = data;
                    }
                    else {
                        $('#errors-container-div').html(data);
                    }
                },
                error: function (jqXHR, type, errorThrown) {
                    $("body").css("cursor", "default");
                    if (errorThrown != null) {
                        alert(errorThrown);
                    }
                }
            });
        });

        var oldMinor = -1;
        //edit minor
        function loadEditForm(btn) {
            $("body").css("cursor", "progress");
            $('#btnAddMinor').hide();
            $('#btnSaveEdit').show();
            $('.page-title h1').text('Edit Minors - Categories');
            var row = table.row($(btn).closest('tr'));
            var minor = row.data()[1];
            oldMinor = minor;
            $.ajax({
                type: 'GET',
                url: "{{url('admin/minors')}}/" + minor,
                success: function (data) {
                    toAddEditPanel();
                    $('.page-title h1').text('Edit Minors - Categories');
                    //set up tree
                    $('#minor_id').val(data.id);
                    var bonsai = $('ul.category').data('bonsai');
                    $.each(data.categories, function (k, v) {
                        //go to the first <li> and find children   
                        $('input#node_' + v).parents('li').first().
                                find('input[type=checkbox]').prop('checked', true);
                        //go to all upper <li> and set indeterminate
                        $('input#node_' + v).parents('li').each(function () {
                            $(this).find('input[type=checkbox]:first').prop('indeterminate', true);
                        });
                        $('input#node_' + v).prop('indeterminate', false);
                        $('input#node_' + v).prop('checked', true);
                    });
                    $('#btnSaveEdit').show();
                    $('#btnAddMinor').hide();
                    $('#breadcrumbEdit').show();
                    $("body").css("cursor", "default");
                },
                error: function (jqXHR, type, errorThrown) {
                    $("body").css("cursor", "default");
                    if (errorThrown != null) {
                        alert(errorThrown);
                    }
                }
            });
        }

        $('#btnSaveEdit').click(function(e) {
            $("body").css("cursor", "progress");
            e.preventDefault();
            $.ajax({
                type: 'PUT',
                url: "{{url('admin/minors')}}/" + oldMinor,
                data: selectParent(),
                success: function (data) {
                    $("body").css("cursor", "default");
                    if (!$(data).is('.alert-danger')) {
                        table.draw(false);
                        $('#errors-container-div').html('');
                        toManagePanel();
                        showSuccessMessage(editSuccessMessage);
                        nextMinor = data;
                    }
                    else {
                        $('#errors-container-div').html(data);
                    }
                },
                error: function (jqXHR, type, errorThrown) {
                    $("body").css("cursor", "default");
                    if (errorThrown != null) {
                        alert(errorThrown);
                    }
                }
            });
        });


        function selectParent() {
            var tree = $('#saveMinor').serializeArray();
            var i = 0;
            while (i < tree.length) {
                var node = tree[i];
                if (node.name != '_token' && node.name != 'minor_id') {
                    var p = $('#node_' + node.name).parents('ul').each(function () { //traverse up the tree
                        var input = $(this).prev('label').children('input')[0]; //find the sibling input
                        if ($(input).prop('checked') == true) {
                            tree.splice(i, 1);
                            i--;
                            return false;
                        }
                    });
                }
                i++;
            }
            console.log(jQuery.param(tree));
            return jQuery.param(tree);
        }

        $(document).ready(function () {
            //split the list to 2 columns
            $.ajax({
                type: 'GET',
                url: "{{url('minors/partial-tree')}}/",
                success: function(data) {
                    $('#partial-tree').html(data);
                    var totalList = $(".category .first-level").size();
                    var halfList = Math.floor(totalList/2);
                    $(".category").each(function() {
                        $(this).children(':gt('+halfList+')').detach().wrapAll('<ul class="bonsai category"></ul>').parent().insertAfter(this);
                    });
                    $(".category").each(function() {
                        $(this).wrapAll('<div class="col-md-6"></div>');
                    });
                    //apply bonsai tree
                    $('.category').bonsai({
                            expandAll: false,
                            checkboxes: true, // depends on jquery.qubit plugin
                            handleDuplicateCheckboxes: true // optional
                    });
                },
                error: function (jqXHR, type, errorThrown) {
                    if (errorThrown != null) {
                        alert(errorThrown);
                    }
                }
            });
        });

        //for search
        var COLS = ["category", "minor"];
        var divider = 2;

        $('#flash-overlay-modal').modal();
    </script>
    @include('partials.search.footer-script')
@endsection