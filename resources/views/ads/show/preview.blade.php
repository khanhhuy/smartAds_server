<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$ads->title}}</title>
    <style>
        #display-label, #title-label {
            font-weight: bold;
            margin-right: 5px;
            text-align: left;
            width: 52px;
            display: inline-block;
        }

        #content {
            width: 383px;
        }

        #title, #second-col {
            width: 320px;
            display: inline-block;
            text-align: center;
        }

        #title {
            vertical-align: top;
        }

        #title-container {
            margin-bottom: 5px;
        }

        #second-col {
            vertical-align: middle;
        }

        #title-label {
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
            vertical-align: middle;
            height: 400px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
<div id="content">
    <div id="title-container">
        <div id="title-label">Title</div>
        <div id="title">{{$ads->title}}</div>
    </div>
    <div id="display-label">Display</div>
    <div id="second-col">
        <div id="frame-container">
            <iframe id="ads-show" src="{{route('ads.show',$ads)}}" width="344px" height="510px"></iframe>
        </div>
    </div>
</div>
</body>
</html>
