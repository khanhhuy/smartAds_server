@extends('manager-master')

@section('head-footer')
    <link rel="stylesheet" type="text/css" href="{{asset('/datatables/datatables.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('/css/manage.css')}}"/>
@endsection
@section('content')
    <div class="panel panel-default">
        @include('partials.fixed-pos-message')
        <div class="panel-body">
            <div id="manage-ads">
                <div class="table-responsive">
                    <table id="manage-table" class="table table-striped table-bordered table-hover" cellspacing="0"
                           width="100%">
                        @yield('table-header')
                    </table>

                </div>
            </div>
        </div>
    </div>
    @include('partials.delete-success')
    @yield('content-footer')
@endsection