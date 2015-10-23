@extends('manager-master')

@section('title','Add Targeted Ads')

@section('head-footer')
    <link href="{{asset('/css/select2.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('/css/promotion-form.css')}}" rel="stylesheet"/>
@endsection

@section('content')
    <br/>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-11">
            @include('errors.list')
            {!! Form::open(['route'=> 'targeted.store','class'=>'form-horizontal promotion-form','enctype'=>'multipart/form-data']) !!}
            @include('ads.partials.targeted-form',['btnSubmitName'=>'Add'])
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('body-footer')
    @include('ads.partials.promotion-footer-script')
@endsection