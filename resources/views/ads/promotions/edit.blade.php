@extends('ads.promotions.partials.create-edit-master')

@section('title',"Edit Promotion \"$ads->id\"")

@section('form')
    {!! Form::model($ads,['route'=> ['promotions.update',$ads->id],'method'=>'PUT','class'=>'form-horizontal promotion-form','enctype'=>'multipart/form-data']) !!}
    @include('ads.partials.edit-promotion-form',['btnSubmitName'=>'Update'])
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-primary">
                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save
            </button>
            <a href="{{route('promotions.manager-manage')}}" class="btn btn-default my-cancel-edit-btn">
                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel
            </a>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@section('breadcrumb')
    <li><a href="{{route('promotions.manager-manage')}}">Manage Promotions</a></li>
    <li class="active">Edit Promotion</li>
@endsection