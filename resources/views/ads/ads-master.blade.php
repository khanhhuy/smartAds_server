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

        #ads-img {
            margin-top: 10px;
            max-width: 95%;
            margin-right: 5%;
            height: auto;
        }

        #range {
            font-size: 15px;
            margin-right: 20px;
        }

        #name-container {
            text-align: center;
        }

        #name {
            display:inline-block;
            font-size: 17px;
            margin:0;
            padding: 15px;
            background-color: #d9edf7;
        }
    </style>
</head>
<body>
<div id="content">
    @section('content')
        <div id="range">{{$start_date}} - {{$end_date}}</div>
        <img id="ads-img" src="{!! $ads->image_url !!}"/>
        <div id="name-container"><p id="name">{{$itemName}}</p></div>
    @show
</div>
<script>

</script>
</body>
</html>
