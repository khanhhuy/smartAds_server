@extends('manager-master')

@section('title','Edit Promotion')

@section('head-footer')
    <link href="{{asset('/css/select2.min.css')}}" rel="stylesheet"/>
@endsection

@section('content')
    <br/>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-11">
            @include('errors.list')
            {!! Form::model($ads,['route'=> ['promotions.update',$ads->id],'method'=>'PUT','class'=>'form-horizontal promotion-form','enctype'=>'multipart/form-data']) !!}
            @include('ads.partials.promotion-form',['btnSubmitName'=>'Update'])
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('body-footer')
    @include('ads.partials.promotion-footer-script')
@endsection