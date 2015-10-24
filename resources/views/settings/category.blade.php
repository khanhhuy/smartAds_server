@extends('settings.admin-settings')

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
    <li class="active">Category</li>
@endsection

@section('content')
	<!--TODO: update taxanomy-->
	<div class="sub-content">
		{!! Form::open(array('url' => 'admin/category', 'class' => 'form-group')) !!}
		{!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
		<div class="row">
		    <ul class="bonsai category">
		    @foreach ($tree as $lv1node)
		        @if (count($lv1node['subcat'] > 0))
		            <li class="has-children collapsed first-level">
		            <div class="thumb"></div>
		        @else
		            <li class="first-level">
		        @endif
		        <input type="checkbox"  name="{{ $lv1node['id'] }}"
		            data-suitable="{{ $lv1node['is_suitable']  }}" > {{ $lv1node['name'] }}

		        @if (count($lv1node['subcat'] > 0))
		            <ul class="bonsai">
		            @foreach ($lv1node['subcat'] as $lv2node)
		                @if (count($lv2node['subcat'] > 0))
		                    <li class="has-children collapsed">
		                    <div class="thumb"></div>
		                @else
		                    <li>
		                @endif
		                <input type="checkbox"  name="{{ $lv2node['id'] }}"
		                    data-suitable="{{ $lv2node['is_suitable']  }}" > {{ $lv2node['name'] }}

		                @if (count($lv2node['subcat'] > 0))
		                    <ul class="bonsai">
		                    @foreach ($lv2node['subcat'] as $lv3node)
		                    <li>
		                        <input type="checkbox" name="{{ $lv3node['id'] }}"
		                            data-suitable="{{ $lv3node['is_suitable']  }}" > {{ $lv3node['name'] }}
		                    </li>
		                    @endforeach
		                    </ul>
		                @endif

		                </li>
		            @endforeach
		            </ul>
		        @endif
		        </li>
		    @endforeach
		    </ul>
		</div>
		{!! Form::close() !!}
	</div>
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
    });
    </script>
@endsection