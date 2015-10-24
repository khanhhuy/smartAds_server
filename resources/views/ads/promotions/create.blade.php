@extends('ads.promotions.partials.create-edit-master')

@section('title','Add Promotion')

@section('page-title','Add Promotion')
@section('form')
    {!! Form::open(['route'=> 'promotions.store','class'=>'form-horizontal promotion-form','enctype'=>'multipart/form-data']) !!}
    @include('ads.partials.promotion-form')
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-primary">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add
            </button>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@section('breadcrumb')
    <li class="active">Add Promotion</li>
@endsection