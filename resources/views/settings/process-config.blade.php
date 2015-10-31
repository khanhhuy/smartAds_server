@extends('settings.admin-settings')

@section('head-footer-child')
@endsection

@section('title','Transaction Process Config')
@section('page-title','Transaction Process Config')
@section('breadcrumb-child')
    <li class="active">Transaction Process Config</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-offset-1 col-lg-10">
            <div class="panel panel-default">
                <div class="panel-body">
                	{!! Form::open(['route'=> 'settings.process-config','class'=>'form-horizontal','enctype'=>'multipart/form-data', 'id' => 'process-config']) !!}
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
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="date">Last processed date: TODO</div>
            <button class="btn btn-primary" id="btnReprocess">Re-process</button>
            <button class="btn btn-primary" id="btnSaveConf">Save config</button>
        </div>
    </div>
@endsection

@section('body-footer')
	<script>
    $(document).ready( function(){
        $('button#btnReprocess').click(function() {
            //Todo AJAX re-mining
        });
        $('button#btnSaveConf').click(function() {
            //Todo AJX save config
        });

    });
    </script>
@endsection
