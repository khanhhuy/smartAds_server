@extends('settings.admin-settings')

@section('head-footer-child')
@endsection

@section('title','Area Config')
@section('page-title','Area Config')
@section('breadcrumb-child')
    <li class="active">Area Config</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-offset-1 col-lg-10">
            <div class="panel panel-default">
                <div class="panel-body">
                    <legend>Whole system</legend>
                	{!! Form::open(['route'=> 'settings.area-config','class'=>'form-horizontal','enctype'=>'multipart/form-data', 'id' => 'config']) !!}
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
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="date">Last updated date: TODO</div>
            <button class="btn btn-primary" id="btnUpdate">Update Area/Stores</button>
            <button class="btn btn-primary" id="btnSaveConf">Save Config</button>
        </div>
    </div>
@endsection
    
@section('body-footer')
<script>
    $(document).ready( function(){
        $('button#btnUpdate').click(function() {
            //Todo AJAX update area
        });
        $('button#btnSaveConf').click(function() {
            //Todo AJX save config
        });

    });
    </script>
@endsection
