@extends('manager-master')

@section('title','Change Password')

@section('head-footer')
    <link href="{{asset('css/change-info.css')}}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    @include('auth.password.partials.password-edit-form',['url'=>'manager/password','home'=>'manager'])
@endsection