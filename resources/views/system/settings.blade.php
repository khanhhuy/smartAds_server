@extends('system.admin-system')

@section('head-footer-child')
    <style>
        .my-list-setting-panel a.panel-heading {
            display: block;
            text-decoration: none;
            text-align: center;
        }
    </style>
@endsection

@section('title','SmartAds Settings')
@section('page-title','SmartAds Settings')
@section('breadcrumb-child')
    <li class="active">Settings</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-offset-1 col-lg-10">
            <div class="panel panel-default">
                <div class="panel-body my-list-setting-panel">
                    <div class="panel panel-default">
                        <a class=" panel-heading panel-title" data-toggle="collapse"
                           href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                            Promotion Threshold
                        </a>

                        <div id="collapseTwo" class="collapse in">
                            <div class="panel-body">
                                {!! Form::open(['route'=> 'system.settings','class'=>'form-horizontal','enctype'=>'multipart/form-data', 'id' => 'process-config']) !!}
                                <div class="form-group">
                                    {!! Form::label('entrance-min-value','Entrance Minimum Value',['class'=>'col-sm-5 control-label']) !!}
                                    <div class="col-sm-2 col-lg-2">
                                        {!! Form::input('number','entrance-min-value', null, ['class'=>'form-control', 'id' => 'entrance-min-value', 'placeholder'=>'10000']) !!}
                                    </div>
                                    <span class="unit"> VNĐ </span>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('entrance-min-rate','Entrance Minimum Rate',['class'=>'col-sm-5 control-label']) !!}
                                    <div class="col-sm-2 col-lg-2">
                                        {!! Form::input('number','entrance-min-rate', null, ['class'=>'form-control', 'id' => 'entrance-min-rate', 'placeholder'=>'20']) !!}
                                    </div>
                                    <span class="unit"> % </span>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('aisle-min-value','Aisle Minimum Value',['class'=>'col-sm-5 control-label']) !!}
                                    <div class="col-sm-2 col-lg-2">
                                        {!! Form::input('number','aisle-min-value', null, ['class'=>'form-control', 'id' => 'aisle-min-value', 'placeholder'=>'10000']) !!}
                                    </div>
                                    <span class="unit"> VNĐ </span>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('aisle-min-rate','Aisle Minimum Rate',['class'=>'col-sm-5 control-label']) !!}
                                    <div class="col-sm-2 col-lg-2">
                                        {!! Form::input('number','aisle-min-rate', null, ['class'=>'form-control', 'id' => 'aisle-min-rate', 'placeholder'=>'20']) !!}
                                    </div>
                                    <span class="unit"> % </span>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-5 col-sm-7">
                                        <button type="submit" class="btn btn-primary">
                                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save
                                        </button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <a class="panel-heading panel-title collapsed" data-toggle="collapse"
                           href="#collapseOne"
                           aria-expanded="false" aria-controls="collapseOne">
                            Transaction Process Config
                        </a>

                        <div id="collapseOne" class="collapse">
                            <div class="panel-body">
                                {!! Form::open(['route'=> 'system.settings','class'=>'form-horizontal','enctype'=>'multipart/form-data', 'id' => 'process-config']) !!}
                                <div class="form-group">
                                    {!! Form::label('time-range','Processing cutomers\' transactions from the last',['class'=>'col-sm-5 control-label']) !!}
                                    <div class="col-sm-2 col-lg-2">
                                        {!! Form::input('number','time-rage', null, ['class'=>'form-control', 'id' => 'time-range', 'placeholder'=>'6']) !!}
                                    </div>
                                    <span class="unit"> months </span>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('related-item','Use related item suggestion',['class'=>'col-sm-5 control-label']) !!}
                                    <div class="col-sm-2 col-lg-2">
                                        <div class="checkbox-inline">
                                            {!! Form::checkbox('related-item', 0, false, ['id' => 'related-item']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-5 col-sm-7">
                                        <button type="submit" class="btn btn-primary">
                                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save
                                        </button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>

                    </div>
                    <div class="panel panel-default">
                        <a class="collapsed panel-heading panel-title"
                           href="{{url('/admin/system/settings/category')}}">
                            Category Selection
                        </a>
                    </div>
                </div>
            </div>
        </div>
        {{--<button class="btn btn-primary" id="btnSaveConf">Save config</button>--}}
    </div>
@endsection

@section('body-footer')
    <script src="{{asset('/js/bootbox.min.js')}}"></script>
    <script>
        $(document).ready(function () {

            $('button#btnReprocess').click(function () {
                //Todo AJAX re-mining
            });
            $('button#btnSaveConf').click(function () {
                //Todo AJX save config
            });

        });
    </script>
@endsection
