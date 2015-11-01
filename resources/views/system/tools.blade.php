@extends('system.admin-system')

@section('head-footer-child')
@endsection

@section('title','Tools')
@section('page-title','Tools')
@section('breadcrumb-child')
    <li class="active">Tools</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-offset-1 col-lg-10">
        
        </div>
    </div>
@endsection
    
@section('body-footer')
<script>
    $(document).ready( function(){
        $('button#btnUpdate').click(function() {
            //Todo AJAX update area
        });
        $('button#btnSaveConf').click(function() {
            //Todo AJX save config
        });

    });
    </script>
@endsection
