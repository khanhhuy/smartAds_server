@extends('partials.navbar')

@section('home',url('/manager'))

@section('navbar-content')
    <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav my-nav-pills navbar-nav">
            <li @if (Request::is('manager/ads/promotions')||(Request::is('manager/ads/*/edit')&&$ads->is_promotion)) class="active" @endif><a
                        href="{{url('manager/ads/promotions')}}"><span class="glyphicon glyphicon-menu-hamburger"
                                                            aria-hidden="true"></span> Manage Promotions</a></li>
            <li {!!Utils::setActiveClassManager('ads/promotions/create')!!}><a
                        href="{{url('manager/ads/promotions/create')}}"><span class="glyphicon glyphicon-plus"
                                                                              aria-hidden="true"></span> Add Promotion</a>
            </li>
            <li @if (Request::is('manager/ads/targeted')||(Request::is('manager/ads/*/edit')&&!$ads->is_promotion)) class="active" @endif><a
                        href="{{url('manager/ads/targeted')}}"><span class="glyphicon glyphicon-menu-hamburger"
                                                            aria-hidden="true"></span> Manage Targeted Ads</a></li>
            <li {!!Utils::setActiveClassManager('ads/targeted/create')!!}><a
                        href="{{url('manager/ads/targeted/create')}}"><span class="glyphicon glyphicon-plus"
                                                                            aria-hidden="true"></span> Add Targeted Ads</a>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                   aria-expanded="false"><span class="glyphicon glyphicon-user"
                                               aria-hidden="true">
                    </span>&nbsp; @if (Auth::guest()) Manager @else{{ Auth::user()->first_name }}@endif <span
                            class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="{{url('manager/password/edit')}}">Change Password</a></li>
                    <li class="divider"></li>
                    <li><a href="{{ url('/auth/logout') }}">Logout</a></li>
                </ul>
            </li>
        </ul>
    </div><!--/.nav-collapse -->
@endsection