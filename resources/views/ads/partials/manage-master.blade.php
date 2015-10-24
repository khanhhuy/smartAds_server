@extends('manager-master')

@section('head-footer')
    <link rel="stylesheet" type="text/css" href="{{asset('/datatables/datatables.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('/css/manage.css')}}"/>
@endsection
@section('content')
    <div class="panel panel-default my-padding-panel">
        @include('partials.fixed-pos-message')
        <div id="manage-ads">
            <table id="manage-table" class="table table-striped table-bordered table-hover" cellspacing="0"
                   width="100%">
                <thead>
                    @yield('table-header')
                </thead>
            </table>
        </div>
    </div>
    @include('partials.delete-success')
@endsection