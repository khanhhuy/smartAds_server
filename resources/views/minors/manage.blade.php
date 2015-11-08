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
            	<div id="category-tree" style="display:none;">
	            	{!! Form::open(array('route'=> 'admin.minors.store', 'class' => 'form-group', 'id' => 'saveMinor')) !!}
				      	<div class="form-group form-inline">
                            <div id="pos-message"></div>
				      		{!! Form::label('minor_id','Minor') !!}
				  			{!! Form::input('number','minor_id', null,['class'=>'form-control', 'id' => 'minor_id','required'=>'required']) !!}
				  			<button type="button" class="btn btn-primary" id="btnAddMinor">Add</button>
                            <button type="button" class="btn btn-primary" id="btnEditMinor" style="display:none;">Save</button>
                            <button type="button" class="btn" id="btnCancel">Cancel</button>
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
                                <th>Category</th>
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
            function togglePanel() {
                $('#minor-table').slideToggle('fast');
                $('#category-tree').slideToggle('fast');
            }

	    	$('#btnOpenAdd').click(function() {
		    	togglePanel();
                $('.bonsai input[type=checkbox]').prop('checked', false);
                $('#minor_id').val('');
	    	});

	    	//add new minor
	    	$('#btnAddMinor').click(function(e) {
                e.preventDefault();
                var $form = $('#saveMinor');
                if (!$form[0].checkValidity()) {
                    $form.find(':submit').click();
                    return;
                }
                var minor = $('#minor_id').val();
	    		$.ajax({
	    			type: 'POST',
	    			url: $(this).attr('action'),
	    			data: $('#saveMinor').serialize(),
	    			success: function(data) {
	    				$('#pos-message').html(data);
                        if ($('#pos-message .alert-danger').length === 0) {
                            table.draw(false);
                            @include('partials.fixed-pos-message-script')
    	    				$('.bonsai input[type=checkbox]').prop('checked', false);
    	    				togglePanel();
                        }
	    			},
	    			error: function (jqXHR, type, errorThrown) {
	                    if (errorThrown != null) {
	                        alert(errorThrown);
	                    }
	                }
	    		});
	    	});

            //edit minor
	    	function loadEditForm(btn) {
                var row = table.row($(btn).closest('tr'));
                var minor = row.data()[1];
                $.ajax({
                    type: 'GET',
                    url: "{{url('admin/minors')}}/" + minor,
                    success: function(data) {
                        //reset tree - too-slow!
                        $('ul.category').each( function() {
                            var bonsai = $(this).data('bonsai');
                            bonsai.collapseAll();
                        });
                        $('.bonsai input[type=checkbox]').prop('checked', false);
                        //set up tree
                        console.log(data.id);
                        $('#minor_id').val(data.id);
                        var bonsai = $('ul.category').data('bonsai');
                        $.each(data.categories, function(k, v) {
                            $('input#node_' + v).prop('checked', true);
                            var listItem = $('input#node_' + v).closest('li');
                            bonsai.expand(listItem);
                        });
                        $('#btnEditMinor').show();
                        $('#btnAddMinor').hide();
                        $('#minor_id').prop('disabled', true);
                        togglePanel();
                    },
                    error: function (jqXHR, type, errorThrown) {
                        if (errorThrown != null) {
                            alert(errorThrown);
                        }
                    }
                });
            }

            $('#btnEditMinor').click(function(e) {
                e.preventDefault();
                var minor = $('#minor_id').val();
                $.ajax({
                    type: 'GET',
                    url: "{{url('admin/minors')}}/" + minor +"/edit",
                    data: $('#saveMinor').serialize(),
                    success: function(data) {
                        $('#pos-message').html(data);
                        if ($('#pos-message .alert-danger').length === 0) {
                            table.draw(false);
                            @include('partials.fixed-pos-message-script')
                            $('.bonsai input[type=checkbox]').prop('checked', false);
                            togglePanel();
                        }
                    },
                    error: function (jqXHR, type, errorThrown) {
                        if (errorThrown != null) {
                            alert(errorThrown);
                        }
                    }
                });
            });

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
                    checkboxes: false, // depends on jquery.qubit plugin
                    handleDuplicateCheckboxes: true // optional
            });
        });

        //for search
        var COLS = ["category", "minor"];
        var divider = 2;
	</script>
@include('partials.search.footer-script')
@endsection