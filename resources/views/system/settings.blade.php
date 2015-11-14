@extends('system.admin-system')

@section('head-footer-child')
    <link href="{{asset('css/admin-setting.css')}}" rel="stylesheet" type="text/css"/>
@endsection

@section('title','SmartAds Settings')
@section('page-title','SmartAds Settings')
@section('breadcrumb-child')
    <li class="active">Settings</li>
@endsection
<?php
$labelClass = "control-label";
$fromValueGroup = "";
$toRateGroup = "";
?>
@section('content')
    <div class="row">
        <div class="my-setting-container">
            <div class="panel panel-default">
                <div class="panel-body my-list-setting-panel">
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default threshold-panel">
                            <a class=" panel-heading panel-title" data-toggle="collapse" role="tab"
                               data-parent="#accordion"
                               href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                Default Notification Thresholds
                            </a>

                            <div id="collapseTwo" class="collapse in">
                                <div class="panel-body">
                                    {!! Form::open(['method'=>'PUT','route'=> 'system.settings.update-threshold','class'=>'form-horizontal', 'id' => 'threshold-config']) !!}
                                    <div id="threshold_errors"></div>
                                    <fieldset>
                                        <legend>Entrance Notification</legend>
                                        <div class="form-group">
                                            {!! Form::label('entrance_value','Min Discount Amount',['class'=>"$labelClass"]) !!}
                                            <div class="input-group my-inline-input-group">
                                                {!! Form::input('number','entrance_value',Config::get('promotion-threshold.entrance_value'),['class'=>'form-control my-inline-control discount-value','required'=>'required',
                                                 'min'=>'0','step'=>'0.001','placeholder'=>'e.g. 10500']) !!}
                                                <div class="input-group-addon">VND</div>
                                            </div>
                                            {!! Form::label('entrance_rate','Rate',['class'=>'control-label my-between-label']) !!}
                                            <div class="input-group my-inline-input-group">
                                                {!! Form::input('number','entrance_rate',Config::get('promotion-threshold.entrance_rate'),['class'=>'form-control my-inline-control  discount-rate', 'min'=>'0','step'=>'0.01','max'=>'100',
                                                'required'=>'required','placeholder'=>'e.g. 20']) !!}
                                                <div class="input-group-addon">%</div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <br/>
                                    <fieldset>
                                        <legend>Aisle Notification</legend>
                                        <div class="form-group">
                                            {!! Form::label('aisle_value','Min Discount Amount',['class'=>"$labelClass"]) !!}
                                            <div class="input-group my-inline-input-group">
                                                {!! Form::input('number','aisle_value',Config::get('promotion-threshold.aisle_value'),['class'=>'form-control my-inline-control discount-value','required'=>'required',
                                                 'min'=>'0','step'=>'0.001','placeholder'=>'e.g. 10500']) !!}
                                                <div class="input-group-addon">VND</div>
                                            </div>
                                            {!! Form::label('aisle_rate','Rate',['class'=>'control-label my-between-label']) !!}
                                            <div class="input-group my-inline-input-group">
                                                {!! Form::input('number','aisle_rate',Config::get('promotion-threshold.aisle_rate'),['class'=>'form-control my-inline-control  discount-rate', 'min'=>'0','step'=>'0.01','max'=>'100',
                                                'required'=>'required','placeholder'=>'e.g. 20']) !!}
                                                <div class="input-group-addon">%</div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <hr class="submit-top-hr"/>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save
                                        </button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default trans-process-panel">
                            <a class="panel-heading panel-title collapsed" data-toggle="collapse" role="tab"
                               href="#collapseOne" data-parent="#accordion"
                               aria-expanded="false" aria-controls="collapseOne">
                                Transaction Process Config
                            </a>

                            <div id="collapseOne" class="collapse">
                                <div class="panel-body">
                                    <div id="process_errors"></div>
                                    {!! Form::open(['method' => 'PUT', 'route'=> 'system.settings.update-process-config','class'=>'form-horizontal','enctype'=>'multipart/form-data', 'id' => 'process-config']) !!}
                                    <div class="form-group">
                                        {!! Form::label('time-range','Process customers\' transactions from the last',['class'=>"control-label"]) !!}
                                        {!! Form::input('number','time-range', $timeRange, ['class'=>'form-control', 'id' => 'time-range', 'required' => 'required']) !!}
                                        <span class="unit"> months </span>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('related-item','Use related item suggestion',['class'=>'control-label']) !!}
                                        <div class="my-inline-control">
                                            {!! Form::checkbox('related-item', 1, $relatedItem, ['id' => 'related-item']) !!}
                                        </div>
                                    </div>
                                    <hr class="submit-top-hr"/>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save
                                        </button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <a class="collapsed panel-heading panel-title" data-parent="#accordion"
                               href="{{url('/admin/system/settings/category')}}">
                                Category Selection
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="my-fixed-pos-message-container"></div>
@endsection

@section('body-footer')
    <script src="{{asset('/js/bootbox.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#threshold-config').submit(function (e) {
                e.preventDefault();
                var url = $(this).prop('action');
                $.ajax({
                    'url': url,
                    'method': 'POST',
                    data: $(this).serialize(),
                    success: function (result) {
                        console.log(result);
                        if ($(result).is('.alert-danger')) {
                            $('#threshold_errors').html(result);
                        }
                        else {
                            $('#my-fixed-pos-message-container').html(result);
                            $('#threshold_errors').html('');
                        }
                    }
                });
            })

            $('#process-config').submit(function (e) {
                e.preventDefault();
                var url = $(this).prop('action');
                $('#process_errors').html('');
                $.ajax({
                    'url': url,
                    'method': 'PUT',
                    data: $(this).serialize(),
                    success: function (result) {
                        console.log(result);
                        if ($(result).is('.alert-danger')) {
                            $('#process_errors').html(result);
                        }
                        else {
                            $('#my-fixed-pos-message-container').html(result);
                            $('#process_errors').html('');
                            bootbox.confirm("Saved successfully, do you want to re-process all of the transactions", 
                                    function(result) {
                                        if (result == true)
                                            window.location.replace("{{url('admin/system/tools')}}");
                            }); 
                        }
                    }
                });
            })
        });
    </script>
@endsection
