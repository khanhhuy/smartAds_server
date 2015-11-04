@extends('admin-master')

@section('title','Minor - Category Management')

@section('head-footer')
	<link rel="stylesheet" type="text/css" href="{{asset('js/jQuery-bonsai/bower_components/jquery-bonsai/jquery.bonsai.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{asset('js/jQuery-bonsai/bower_components/jquery-bonsai/assets/svg-icons.css') }}" >
    <link rel="stylesheet" type="text/css" href={!! URL::asset('css/admin-select.css') !!}>
    <link rel="stylesheet" type="text/css" href={!! URL::asset('css/admin.css') !!}>

    <script type="text/javascript" src={!! URL::asset('js/jQuery-bonsai/bower_components/jquery/dist/jquery.min.js') !!} defer></script>
    <script type="text/javascript" src={!! URL::asset('js/jQuery-bonsai/bower_components/jquery-qubit/jquery.qubit.js') !!} defer></script>
    <script type="text/javascript" src={!! URL::asset('js/jQuery-bonsai/bower_components/jquery-bonsai/jquery.bonsai.js') !!} defer></script>	
@endsection

@section('page-title','Minor - Category Management')
	
@section('content')

	<div class="modal fade" id="taxonomyModal">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Add Category - Minor</h4>
	        <div class="date">Last updated:</div>
	      </div>
	      <div class="modal-body">
	      	{!! Form::label('minor','Minor') !!}
	      	{!! Form::input('number','minor', null,['class'=>'form-control','required'=>'required']) !!}
	        	@include('system.partials.category-tree')
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary">
	        	Add
	        </button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<button id="btnAddMinor" class="btn btn-primary" data-toggle="modal" data-target="#taxonomyModal">
		Add Minor - Category
	</button>
	
	@include('partials.flash-overlay')

@endsection

@section('breadcrumb')
    <li class="active">Minor - Category Management</li>
@endsection

@section('body-footer')
<script>
    $(document).ready( function(){
        $("input[data-suitable=1]").prop("checked", true);
        $("input[data-suitable=0]").hide();
        var totalList = $(".category .first-level").size();
        var halfList = Math.floor(totalList/2);
        $(".category").each(function() {
            $(this).children(':gt('+halfList+')').detach().wrapAll('<ul class="bonsai category"></ul>').parent().insertAfter(this);
        });
        $(".category").each(function() {
            $(this).wrapAll('<div class="col-md-6"></div>');
        });
        $('.category').bonsai({
                expandAll: false,
                checkboxes: true, // depends on jquery.qubit plugin
                handleDuplicateCheckboxes: true // optional
            });
        $('button#saveBtn').click(function() {
        	$('form#saveCat').submit();
        });
        $('button#updateBtn').click(function() {
        	$('form#updateTax').submit();
        });	

        $('#flash-overlay-modal').modal();
     
    });
    </script>
@endsection