@extends('ads.targeted.partials.creat-edit-master')

@section('title','Add Targeted Ads')

@section('form')
        <?php
            $labelClass = "col-sm-3 control-label";
            $fromValueGroup = "col-sm-4 col-md-3";
            $toRateGroup = "col-sm-5 col-md-6";
            $urlGroupClass = "col-sm-8 col-md-7";
        ?>
        {!! Form::open(['route'=> 'targeted.store','class'=>'form-horizontal promotion-form','enctype'=>'multipart/form-data']) !!}
        @include('ads.partials.targeted-form',['btnSubmitName'=>'Add'])
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <button type="submit" class="btn btn-primary">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add
                </button>
            </div>
        </div>
        {!! Form::close() !!}
@endsection
