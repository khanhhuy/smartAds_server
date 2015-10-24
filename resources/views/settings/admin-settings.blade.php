@extends('admin-master')

@section('head-footer')
	<link rel="stylesheet" type="text/css" href="{{asset('/css/admin-menubar.css')}}"/>
	@yield('head-footer-child')
@endsection

@section('menubar')
	@include('partials.admin-menubar')
@endsection

@section('breadcrumb')
    <li class="active"><a href="settings">Settings</a></li>
    @yield('breadcrumb-child')
@endsection
