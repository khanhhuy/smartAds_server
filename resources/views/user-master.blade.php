<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <link href="{{ asset('/css/all.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href="{{asset('/fonts/googlefonts.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('fonts/font-awesome-4.4.0/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    @yield('head-footer')
</head>
<body>
@yield('navbar')
@yield('menubar')
<div class="page-heading">
    <div class="container">
        <div class="page-title">
            <h1>@section('page-title')@yield('title')@show</h1>
        </div>
        <div class="my-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="@yield('home')">Home</a></li>
                @section('breadcrumb')
                    <li class="active">@yield('title')</li>
                @show
            </ol>
        </div>
    </div>
</div>
@section('container')
    <div class="container">
        @yield('content')
    </div>
@show
<script src="{{asset('/js/all.js')}}"></script>
@yield('body-footer')
</body>
</html>
