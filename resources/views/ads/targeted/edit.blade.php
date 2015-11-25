@extends('ads.targeted.partials.creat-edit-master')

@section('title',"Edit Targeted Ads \"$ads->id\"")

@section('form')
    <?php
    $labelClass = "col-sm-3 control-label";
    $fromValueGroup = "col-sm-4 col-md-3";
    $toRateGroup = "col-sm-5 col-md-6";
    $urlGroupClass = "col-sm-8 col-md-7";
    ?>
    {!! Form::model($ads,['route'=> ['targeted.update',$ads->id],'method'=>'PUT','class'=>'form-horizontal promotion-form','enctype'=>'multipart/form-data']) !!}
    @include('ads.partials.edit-targeted-form',['btnSubmitName'=>'Add'])
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-primary">
                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save
            </button>
            <a href="{{route('targeted.manager-manage')}}" class="btn btn-default my-cancel-edit-btn">
                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel
            </a>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@section('breadcrumb')
    <li><a href="{{route('targeted.manager-manage')}}">Manage Targeted Ads</a></li>
    <li class="active">Edit Targeted Ads</li>
@endsection