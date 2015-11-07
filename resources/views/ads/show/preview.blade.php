<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$ads->title}}</title>
    <style>
        .display-label, .title-label {
            font-weight: bold;
        }

        .display-label {
            clear: both;
            width: 52px;
            text-align: right;
        }

        .title-container {
            width: 383px;
        }

        .title {
            width: 326px;
            float: left;
            text-align: center;
        }

        .title-label {
            width: 52px;
            text-align: right;
            float: left;
            margin-right: 5px;
            margin-bottom: 5px;
        }

        body {
            margin: 0;
        }

        #ads-show {
            transform: scale(0.78);
            transform-origin: 0 0;
            display: block;
            margin-left: auto;
            margin-right: auto;
            border: 1px solid #ddd;
        }

        #frame-container {
            width: 270px;
            height: 400px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
<div id="content">
    <div class="title-container">
        <div class="title-label">Title</div>
        <div class="title">{{$ads->title}}</div>
    </div>
    <div class="display-label">Display</div>
    <div id="frame-container">
        <iframe id="ads-show" src="{{route('ads.show',$ads)}}" width="344px" height="510px"></iframe>
    </div>
</div>
</body>
</html>
