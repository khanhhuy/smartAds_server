@extends('admin-master')

@section('title','Minor - Category Management')

@section('head-footer')
	<link rel="stylesheet" type="text/css" href="{{asset('js/jQuery-bonsai/bower_components/jquery-bonsai/jquery.bonsai.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{asset('js/jQuery-bonsai/bower_components/jquery-bonsai/assets/svg-icons.css') }}" >
    <link rel="stylesheet" type="text/css" href={!! URL::asset('css/admin-select.css') !!}>
    <link rel="stylesheet" type="text/css" href={!! URL::asset('css/admin.css') !!}>

    <script type="text/javascript" src={!! URL::asset('js/jQuery-bonsai/bower_components/jquery-qubit/jquery.qubit.js') !!} defer></script>
    <script type="text/javascript" src={!! URL::asset('js/jQuery-bonsai/bower_components/jquery-bonsai/jquery.bonsai.js') !!} defer></script>	
@endsection

@section('page-title','Minor - Category Management')
	
@section('content')

	<div class="row">
		<button id="btnOpenAdd" class="btn btn-primary">
			Add Minor - Category
		</button>
        <div class="panel panel-default category-tree" id="category-tree" style="display:none;">
            <div class="panel-body">
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
        </div>
        <div id="manage-minor">
        	<p>
        		Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
        		tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
        		quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
        		consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
        		cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
        		proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        	</p>
        </div>
    </div>
	
	
	<div id="pos-message"></div>
@endsection

@section('breadcrumb')
    <li class="active">Minor - Category Management</li>
@endsection

@section('body-footer')
<script>
    $(document).ready( function(){

    	$('#btnOpenAdd').click(function() {
    		$('#manage-minor').slideToggle('fast');
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
    				@include('partials.fixed-pos-message-script')
    			},
    			error: function (jqXHR, type, errorThrown) {
                    if (errorThrown != null) {
                        alert(errorThrown);
                    }
                }
    		});
    	});

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
    </script>
@endsection