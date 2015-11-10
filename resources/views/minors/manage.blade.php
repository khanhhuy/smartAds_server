@extends('admin-master')

@section('title','Minor - Category Management')

@section('head-footer')
	<link rel="stylesheet" type="text/css" href="{{asset('js/jQuery-bonsai/bower_components/jquery-bonsai/jquery.bonsai.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{asset('js/jQuery-bonsai/bower_components/jquery-bonsai/assets/svg-icons.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{asset('/datatables/datatables.min.css')}}"
          xmlns="http://www.w3.org/1999/html"/>
    <link href="{{asset('/css/select2.min.css')}}" rel="stylesheet"/>

	<link rel="stylesheet" type="text/css" href="{{asset('/css/major-manage.css')}}"/>
    <link rel="stylesheet" type="text/css" href={!! URL::asset('css/admin-select.css') !!}>
    <link rel="stylesheet" type="text/css" href={!! URL::asset('css/admin.css') !!}>

    <script type="text/javascript" src={!! URL::asset('js/jQuery-bonsai/bower_components/jquery-qubit/jquery.qubit.js') !!} defer></script>
    <script type="text/javascript" src={!! URL::asset('js/jQuery-bonsai/bower_components/jquery-bonsai/jquery.bonsai.js') !!} defer></script>	
@endsection

@section('page-title','Minor - Category Management')
	
@section('content')

	<div class="row manage-page major-manage">
        <div class="col-sm-offset-1 col-sm-10">
    		<button id="btnOpenAdd" class="btn btn-primary">
    			Add Minor - Category
    		</button>
            <div class="panel panel-default category-tree">
            <div class="panel-body">
            	<div id="category-tree" style="display: none;">
	            	{!! Form::open(array('route'=> 'admin.minors.store', 'class' => 'form-group', 'id' => 'saveMinor')) !!}
				      	<div class="form-group form-inline">
                            <div id="pos-message"></div>
				      		{!! Form::label('minor_id','Minor') !!}
				  			{!! Form::input('number','minor_id', null,['class'=>'form-control', 'id' => 'minor_id','required'=>'required', 'min'=>'1','step'=>'1','max'=>'65535']) !!}
				  			<button type="button" class="btn btn-primary" id="btnAddMinor">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                Add
                            </button>
                            <button type="button" class="btn btn-primary" id="btnSaveEdit" style="display:none;">Save</button>
                            <button type="button" class="btn btn-default my-cancel-btn" id="btnCancel">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel
                            </button>
                            <input type="submit" value="Submit" hidden>
				      	</div>
				      	<h4 class="modal-title">List Categories</h4>
				    	@include('system.partials.category-tree')
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
            <div class="col-sm-12 col-md-8">
        </div>
        
    </div>
	@include('partials.delete-success')
	
@endsection

@section('breadcrumb')
    <li class="active" id="breadcrumbManage">Minor - Category Management</li>
    <li class="active" id="breadcrumbAdd" style="display:none;">Add</li>
    <li class="active" id="breadcrumbEdit" style="display:none;">Edit</li>
@endsection

@section('body-footer')
	<script type="text/javascript">
		var myTableURL = "{{url('/minors/table')}}";
        var myOrder = [];
        var myDom = "<'row'<'col-sm-6'lB><'col-sm-6'i>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12'p>>";
        var myDeleteURL = "{{route('minors.deleteMulti')}}";
        var myColumns = [
            {
                data: 0,
                width: "600px",
                orderable: false
            },
            {
                data: 1,
                width: "100px"
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
        function toManagePanel() {
            $('#minor-table').slideToggle();
            $('#category-tree').slideToggle();
            $('#breadcrumbAdd').hide();
            $('#breadcrumbEdit').hide();
            $('#breadcrumbManage').text('Minor - Category Management');
            $('#btnOpenAdd').show();
        }

        function toAddEditPanel() {
            $('#minor-table').slideToggle('fast');
            $('#category-tree').slideToggle('fast');
            $('.bonsai input[type=checkbox]').prop('checked', false);
            $('.bonsai input[type=checkbox]').prop('indeterminate', false);
            $('#minor_id').val('');
            $('ul.category').each( function() {
                var bonsai = $(this).data('bonsai');
                bonsai.collapseAll();
            });
            $('#btnOpenAdd').hide();
            $('#btnSaveEdit').hide();
            $('#btnAddMinor').show();
            $('#breadcrumbManage').html('<a href="{{url('/admin/minors')}}">Minor - Category Management</a>');
        }

    	$('#btnOpenAdd').click(function() {
            toAddEditPanel();
            $('#breadcrumbAdd').toggle();
    	});

        $('#btnCancel').click(function() {
            toManagePanel();
        });


    	//add new minor
    	$('#btnAddMinor').click(function(e) {
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
    			success: function(data) {
    				$('#pos-message').html(data);
                    if ($('#pos-message .alert-danger').length === 0) {
                        table.draw(false);
                        toManagePanel();
                    }
    			},
    			error: function (jqXHR, type, errorThrown) {
                    if (errorThrown != null) {
                        alert(errorThrown);
                    }
                }
    		});
    	});

        var oldMinor = -1;
        //edit minor
    	function loadEditForm(btn) {
            $('#btnAddMinor').hide();
            $('#btnSaveEdit').show();
            var row = table.row($(btn).closest('tr'));
            var minor = row.data()[1];
            oldMinor = minor;
            $.ajax({
                type: 'GET',
                url: "{{url('admin/minors')}}/" + minor,
                success: function(data) {
                    toAddEditPanel();
                    //set up tree
                    $('#minor_id').val(data.id);
                    var bonsai = $('ul.category').data('bonsai');
                    $.each(data.categories, function(k, v) {
                        parentList = $('input#node_' + v).parents('li');
                        $(parentList[0]).find('input[type=checkbox]').prop('checked', true);
                        $('input#node_' + v).prop('checked', true);
                    });
                    $('.category').each(function() {
                        $(this).qubit();
                    });
                    $('#btnSaveEdit').show();
                    $('#btnAddMinor').hide();
                    $('#breadcrumbEdit').show();
                },
                error: function (jqXHR, type, errorThrown) {
                    if (errorThrown != null) {
                        alert(errorThrown);
                    }
                }
            });
        }

        $('#btnSaveEdit').click(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'PUT',
                url: "{{url('admin/minors')}}/" + oldMinor,
                data: selectParent(),
                success: function(data) {
                    $('#pos-message').html(data);
                    if ($('#pos-message .alert-danger').length === 0) {
                        table.draw(false);
                        toManagePanel();
                    }
                },
                error: function (jqXHR, type, errorThrown) {
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
                    var p = $('#node_' + node.name).parents('ul').each(function() { //traverse up the tree
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

        $(document).ready( function(){
            //split the list to 2 columns
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
        });

        //for search
        var COLS = ["category", "minor"];
        var divider = 2;
	</script>
@include('partials.search.footer-script')
@endsection