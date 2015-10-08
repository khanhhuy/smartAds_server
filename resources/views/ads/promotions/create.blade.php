@extends('manager-master')

@section('title','Add Promotion')
@section('head-footer')
    <link href="{{asset('/css/select2.min.css')}}" rel="stylesheet"/>
@endsection
@section('content')
    {{--<br/>--}}
    {{--<div class="row">--}}
        {{--<div class="col-md-1"></div>--}}
        {{--<div class="col-md-11">--}}
            @include('errors.list')
            {!! Form::open(['url'=> 'ads/promotions','class'=>'form-horizontal promotion-form']) !!}
            @include('ads.partials.promotion-form',['btnSubmitName'=>'Add'])
            {!! Form::close() !!}
        {{--</div>--}}
    {{--</div>--}}
@endsection

@section('body-footer')
    <script src="{{asset('/js/select2.min.js')}}"></script>
    <script>
        $('#targetsID').select2();
        $('#itemsID').select2({
            ajax: {
                delay: 250,
                dataType: 'jsonp',
                url: "{{Connector::getItemSearchURL()}}",
                data: function (params) {
                    var queryParameters = {
                        query: params.term,
                        page: params.page
                    }

                    return queryParameters;
                },
                processResults: function (data) {
                    return {
                        results: data.items,
                    };
                },
                cache: true
            },
            minimumInputLength: 1,
            templateResult: function (item) {
                return item.name + "[" + item.id + "]";
            },
            templateSelection: function (item) {
                return item.name + "[" + item.id + "]";
            }
        });
    </script>
@endsection