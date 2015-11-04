@extends('admin-master')

@section('content')
    @include('partials.flash-overlay')
@endsection

@section('body-footer')
    <script>
        $('#flash-overlay-modal').modal();
    </script>
@endsection