@extends('ads.promotions.partials.create-edit-master')

@section('title','Add Promotion')

@section('page-title','Edit Promotion')
@section('form')
    {!! Form::model($ads,['route'=> ['promotions.update',$ads->id],'method'=>'PUT','class'=>'form-horizontal promotion-form','enctype'=>'multipart/form-data']) !!}
    @include('ads.partials.promotion-form',['btnSubmitName'=>'Update'])
    {!! Form::close() !!}
@endsection

@section('breadcrumb')
    <li><a href="{{route('promotions.manager-manage')}}">Manage Promotions</a></li>
    <li class="active">Edit Promotion</li>
@endsection