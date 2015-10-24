@extends('ads.promotions.partials.create-edit-master')

@section('title','Add Promotion')

@section('page-title','Create Promotion')
@section('form')
    {!! Form::open(['route'=> 'promotions.store','class'=>'form-horizontal promotion-form','enctype'=>'multipart/form-data']) !!}
    @include('ads.partials.promotion-form',['btnSubmitName'=>'Add'])
    {!! Form::close() !!}
@endsection

@section('breadcrumb')
    <li class="active">Create Promotion</li>
@endsection