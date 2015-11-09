@extends('ads.partials.targeted-form')

@section('id-group')
    <div class="form-group">
        <div class="col-sm-3 text-right"><b>ID</b></div>
        <div class="col-sm-9">{{$ads->id}}</div>
    </div>
@endsection