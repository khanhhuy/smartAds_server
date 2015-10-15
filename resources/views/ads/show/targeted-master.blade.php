<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$ads->title}}</title>
    <style>
        #content {
            text-align: center;
            padding-top: 25px;
        }
        #ads-img{
            max-width:100%;
            height:auto;
        }
    </style>
</head>
<body>
<div id="content">
    @section('content')
        <img id="ads-img" src="{!! $ads->image_url !!}"/>
    @show
</div>
</body>
</html>
