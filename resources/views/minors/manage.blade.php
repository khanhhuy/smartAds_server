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
		<button id="btnOpenAdd" class="btn btn-primary">
			Add Minor - Category
		</button>
        <div class="panel panel-default category-tree">
            <div class="panel-body">
            	<div id="category-tree" style="display:none;">
	            	{!! Form::open(array('route'=> 'admin.minors.store', 'class' => 'form-group', 'id' => 'saveMinor')) !!}
				      	<div class="form-group form-inline">
				      		{!! Form::label('minor_id','Minor') !!}
				  			{!! Form::input('number','minor_id', null,['class'=>'form-control', 'id' => 'minor_id','required'=>'required']) !!}
				  			@include('errors.list')
				  			<button type="button" class="btn btn-primary" id="btnAddMinor">Add</button>
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
                                <th>Minor</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                            <tr>
                                <td></td>
                                @include('partials.search.number-from-to',['name'=>'minor','min'=>'1','step'=>'1','max'=>'65535'])
                                {!! Utils::genSearchCell('Category name') !!}
                                @include('partials.search.action-group')
                            </tr>
                            </thead>
                        </table>
                    </div>
            </div>
        </div>
        
    </div>
	@include('partials.delete-success')
	<div id="pos-message"></div>
@endsection

@section('breadcrumb')
    <li class="active">Minor - Category Management</li>
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
                width: "100px"
            },
            {
                data: 1,
                width: "600px"
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
            //loadCreateForm();
        }
        var myDrawCallbackFunc = function () {
            updateEditingRow(editingRow);
        }
	</script>
	@include('partials.manage-footer-script')
	<script>
	    $(document).ready( function(){

	    	$('#btnOpenAdd').click(function() {
		    	$('#minor-table').slideToggle('fast');
	    		$('#category-tree').slideToggle('fast');
	    	});

	    	//add new minor
	    	$('#btnAddMinor').click(function() {
	    		$.ajax({
	    			type: 'POST',
	    			url: $(this).attr('action'),
	    			data: $('#saveMinor').serialize(),
	    			success: function(data) {
	    				$('#pos-message').html(data);
	    				$('#taxonomyModal').modal('hide');
	    				$('.bonsai input[type=checkbox]').prop('checked', false);
	    				$('#category-tree').slideToggle('fast');
	    				if ($('#pos-message .alert-danger').length === 0) {
                     	   	updateEditingRow(-1);
                        	table.draw(false);
                        	@include('partials.fixed-pos-message-script')
	                    }
	    			},
	    			error: function (jqXHR, type, errorThrown) {
	                    if (errorThrown != null) {
	                        alert(errorThrown);
	                    }
	                }
	    		});
	    	});

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

			var editingRow = -1;

	    	function updateEditingRow(newMinor) {
	            if (editingRow >= 0) {
	                $('tr#' + editingRow).removeClass('editing-row');
	            }
	            editingRow = newMinor;
	            if (editingRow >= 0) {
	                $('tr#' + editingRow).addClass('editing-row');
	            }
	        }

	        function loadEditForm(btn) {
	            var row = table.row($(btn).closest('tr'));
	            var minor = row.data()[0];
	            var url = "{{url('/minors')}}/" + minor + "/edit";
	            loadForm(url, function () {
	                $('#minor').focus();
	            });
	            updateEditingRow(minor);
	        }



	    }
	</script>
	<script type="text/javascript">
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
                checkboxes: false, // depends on jquery.qubit plugin
                handleDuplicateCheckboxes: true // optional
            });
	</script>
@include('partials.search.footer-script')
@endsection