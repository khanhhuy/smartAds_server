@extends('system.admin-system')

@section('head-footer-child')
	<link rel="stylesheet" type="text/css" href="{{asset('js/jQuery-bonsai/bower_components/jquery-bonsai/jquery.bonsai.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{asset('js/jQuery-bonsai/bower_components/jquery-bonsai/assets/svg-icons.css') }}" >
    <link rel="stylesheet" type="text/css" href={!! URL::asset('css/admin-select.css') !!}>

    <script type="text/javascript" src={!! URL::asset('js/jQuery-bonsai/bower_components/jquery/dist/jquery.min.js') !!} defer></script>
    <script type="text/javascript" src={!! URL::asset('js/jQuery-bonsai/bower_components/jquery-qubit/jquery.qubit.js') !!} defer></script>
    <script type="text/javascript" src={!! URL::asset('js/jQuery-bonsai/bower_components/jquery-bonsai/jquery.bonsai.js') !!} defer></script>
@endsection

@section('title','Set Up Category')
@section('page-title','Set Up Category')
@section('breadcrumb-child')
	<li><a href="{{url('admin/system/settings')}}">Settings</a></li>
    <li class="active">Category</li>
@endsection

@section('content')
	<div class="date">Last updated taxonomy: TODO</div>
	<button class="btn btn-primary" id="saveBtn">Save</button>
	<button class="btn btn-warning" id="updateBtn">Update Taxonomy</button>
    {!! Form::open(array('route'=> 'system.settings.category', 
    'class' => 'form-group', 'id' => 'saveCat')) !!}
		@include('system.partials.category-tree')
    {!! Form::close() !!}
@endsection

@section('body-footer')
	<script>
    $(document).ready( function(){
        $("input[data-suitable=1]").prop("checked", true);
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
    });
    </script>
@endsection