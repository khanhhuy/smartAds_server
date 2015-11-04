<?php
$labelClass = "col-sm-5 control-label";
$inputClass = "col-sm-7";
?>
<div class="panel panel-default" id="my-password-panel">
    <div class="panel-body">
        @include('errors.list')
        {!! Form::open(['url'=>"$url",'method'=>'PUT','class'=>'form-horizontal']) !!}
        <div class="form-group">
            {!! Form::label('current_password','Current Password',['class'=>"$labelClass"]) !!}
            <div class="{{$inputClass}}">
                {!! Form::input('password','current_password',null,['class'=>'form-control','required'=>'required']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('new_password','New Password',['class'=>"$labelClass"]) !!}
            <div class="{{$inputClass}}">
                {!! Form::input('password','new_password',null,['class'=>'form-control','required'=>'required']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('new_password_confirmation','Confirm New Password',['class'=>"$labelClass"]) !!}
            <div class="{{$inputClass}}">
                {!! Form::input('password','new_password_confirmation',null,['class'=>'form-control','required'=>'required']) !!}
            </div>
        </div>
        <div class="form-group" id="action-group">
            <div class="col-sm-offset-5 col-sm-7">
                <button type="submit" class="btn btn-primary">
                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save
                </button>
                <a class="btn btn-default" id="my-cancel-btn" href="{{url($home)}}">
                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel
                </a>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>