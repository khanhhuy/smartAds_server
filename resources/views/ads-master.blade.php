<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$ads->title}}</title>

    {{--<link href="{{ asset('/css/mobile.css') }}" rel="stylesheet">--}}
</head>
<body>
<div class="container">
    @section('content')
        <h5>{{$ads->title}}</h5>
        <img src="{{asset('img/'.$ads->id.'.png')}}" alt="{{$ads->title}}" />
    @show
</div>

</body>
</html>
