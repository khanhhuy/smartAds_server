@extends('partials.navbar')

@section('home',url('/manager'))

@section('navbar-content')
    <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav my-nav-pills navbar-nav">
            <li {!!Utils::setActiveClassManager('ads')!!}><a href="{{url('manager/ads')}}"><span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span> Manage Ads</a></li>
            <li {!!Utils::setActiveClassManager('ads/promotions/create')!!}><a href="{{url('manager/ads/promotions/create')}}"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Promotion</a></li>
            <li {!!Utils::setActiveClassManager('ads/targeted/create')!!}><a href="{{url('manager/ads/targeted/create')}}"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Targeted Ads</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                   aria-expanded="false"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp; {{--{{ Auth::user()->name }}--}}Manager <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#password">Change Account Info</a></li>
                    <li class="divider"></li>
                    <li><a href="{{ url('/auth/logout') }}">Logout</a></li>
                </ul>
            </li>
        </ul>
    </div><!--/.nav-collapse -->
@endsection