<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>

    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        body {
            padding-top: 40px;
            padding-bottom: 40px;
        }

        #login_btn {
            min-width: 80px;
            margin-right: 25px;
        }

        @media (min-width: 768px) {
            #my-login-panel {
                width: 470px;
                margin-left: auto;
                margin-right: auto;
            }

            #my-login-panel .panel-heading {
                text-align: center;
            }

            #my-login-panel .panel-body {
                padding-left: 20px;
                padding-right: 20px;
            }
        }

        #remember_chk {
            display: inline-block;
        }
    </style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default" id="my-login-panel">
                <div class="panel-heading">Portal Login</div>
                <div class="panel-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-sm-4 control-label">E-Mail Address</label>

                            <div class="col-sm-7">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                       required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Password</label>

                            <div class="col-sm-7">
                                <input type="password" class="form-control" name="password" required="required">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-4">
                                <button type="submit" id="login_btn" class="btn btn-primary">Login</button>

                                <div class="checkbox" id="remember_chk">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('/js/all.js')}}"></script>
</body>
</html>
