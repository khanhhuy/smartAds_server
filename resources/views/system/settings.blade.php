@extends('system.admin-system')

@section('head-footer-child')
   
@endsection

@section('title','SmartAds Settings')
@section('page-title','SmartAds Settings')
@section('breadcrumb-child')
    <li class="active">Settings</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-offset-1 col-lg-10">
            <div class="panel panel-default">
                <div class="panel-body">
                    {!! Form::open(['route'=> 'system.settings','class'=>'form-horizontal','enctype'=>'multipart/form-data', 'id' => 'process-config']) !!}
                        <fieldset>
                            <legend>Transaction Process Config</legend>
                    		<div class="form-group">
                    			{!! Form::label('time-range','Processing cutomers\' transactions from the last',['class'=>'col-sm-5 control-label']) !!}
            			        <div class="col-sm-2 col-lg-2">
            			        	{!! Form::input('number','time-rage', null, ['class'=>'form-control', 'id' => 'time-range', 'placeholder'=>'6']) !!}
                    			</div>
                                <span class="unit"> months </span>
                    		</div>
                            <div class="form-group">
                                {!! Form::label('related-item','Use related item suggestion',['class'=>'col-sm-5 control-label']) !!}
                                <div class="col-sm-2 col-lg-2">
                                    <div class="checkbox-inline">
                                        {!! Form::checkbox('related-item', 0, false, ['id' => 'related-item']) !!}
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        </br>
                        <fieldset>
                            <legend>Promotion Threshold</legend>
                            <div class="form-group">
                                {!! Form::label('min-value','Minimum Value Promotion',['class'=>'col-sm-5 control-label']) !!}
                                <div class="col-sm-2 col-lg-2">
                                    {!! Form::input('number','min-value', null, ['class'=>'form-control', 'id' => 'min-value', 'placeholder'=>'10000']) !!}
                                </div>
                                <span class="unit"> VNĐ </span>
                            </div>
                            <div class="form-group">
                                {!! Form::label('min-rate','Minimum Rate Promotion',['class'=>'col-sm-5 control-label']) !!}
                                <div class="col-sm-2 col-lg-2">
                                    {!! Form::input('number','min-rate', null, ['class'=>'form-control', 'id' => 'min-rate', 'placeholder'=>'20']) !!}
                                </div>
                                <span class="unit"> % </span>
                            </div>
                        </fieldset>
                        </br>
                        <fieldset>
                            <legend>Category Selection</legend>
                                <div class="form-group">
                                    <div class="col-sm-5 control-label">
                                        <a href="{{url('/admin/system/settings/category')}}">
                                            <button class="btn btn-primary" id="btnShowCategory" type="button">
                                                Show Category
                                            </button>
                                        </a>
                                    </div>
                                </div>
                        </fieldset>
                    {!! Form::close() !!}
                </div>
            </div>
            <button class="btn btn-primary" id="btnSaveConf">Save config</button>
        </div>
    </div>
@endsection

@section('body-footer')
    <script src="{{asset('/js/bootbox.min.js')}}"></script>
	<script>
    $(document).ready( function(){

        $('#btnShowCategory').on('click', function() {
            
        });

        $('button#btnReprocess').click(function() {
            //Todo AJAX re-mining
        });
        $('button#btnSaveConf').click(function() {
            //Todo AJX save config
        });

    });
    </script>
@endsection
