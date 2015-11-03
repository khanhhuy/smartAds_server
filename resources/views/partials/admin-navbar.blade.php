@extends('partials.navbar')

@section('home',url('/admin'))

@section('navbar-content')
    <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav my-nav-pills navbar-nav">
            <li @if (Request::is('admin/minors')) class="active" @endif>
                <a href="{{url('admin/minors')}}"><span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>
                    Minors - Categories</a></li>
            <li @if (Request::is('admin/majors')) class="active" @endif>
                <a href="{{url('admin/majors')}}"><span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span> Majors -
                    Stores</a></li>
        </ul>
        <ul class="nav my-nav-pills navbar-nav navbar-right">
            <li @if (Request::is('admin/system/*')) class="active" @endif>
                <a href="{{url('admin/system')}}"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    System </a>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                   aria-expanded="false"><span class="glyphicon glyphicon-user" aria-hidden="true">
                    </span>&nbsp; @if (Auth::guest()) Admin @else{{ Auth::user()->first_name }}@endif <span
                            class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#password">Change Account Info</a></li>
                    <li class="divider"></li>
                    <li><a href="{{ url('/auth/logout') }}">Logout</a></li>
                </ul>
            </li>
        </ul>
    </div><!--/.nav-collapse -->
@endsection