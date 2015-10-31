@extends('manager-master')

@section('head-footer')
    <link href="{{asset('/css/select2.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('/css/promotion-form.css')}}" rel="stylesheet"/>
    <link href="{{asset('/css/targeted-form.css')}}" rel="stylesheet"/>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-offset-1 col-lg-10">
            <div class="panel panel-default">
                <div class="panel-body">
                    @include('errors.list')
                    @yield('form')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('body-footer')
    @include('ads.partials.promotion-footer-script')
@endsection