@extends('manager-master')

@section('title','Add Promotion')
@section('head-footer')
    <link href="{{asset('/css/select2.min.css')}}" rel="stylesheet"/>
    <script src="{{asset('/js/select2.min.js')}}"></script>
@endsection
@section('content')
    <h2>New Promotion</h2>
    <hr/>
    @include('errors.list')
    {!! Form::open(['url'=> 'ads/promotions','class'=>'form-horizontal promotion-form']) !!}
    @include('ads.partials.promotion-form',['btnSubmitName'=>'Add'])
    {!! Form::close() !!}
@endsection