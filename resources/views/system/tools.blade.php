@extends('system.admin-system')
    <link rel="stylesheet" type="text/css" href="{{asset('/css/admin-tool.css')}}">
@section('head-footer-child')
@endsection

@section('title','Tools')
@section('page-title','Tools')
@section('breadcrumb-child')
    <li class="active">Tools</li>
@endsection

@section('content')
    <div class="row">
        <?php $loader = asset('/img/icon/rolling.svg'); ?>
        <div class="btn-rich" id="btnProcess">
            <a class="btn-name" href="#">Re-Process </a>
            <img src="{{$loader}}" class="icon-loader">
            <span class="glyphicon glyphicon-ok icon-success" aria-hidden="true"></span>
            <div class="btn-update">Last updated: 11-1-2015</div>
        </div>
        <div class="btn-rich" id="btnTaxonomy">
            <a class="btn-name" href="#">Update Taxonomy</a>
            <img src="{{$loader}}" class="icon-loader">
            <span class="glyphicon glyphicon-ok icon-success" aria-hidden="true"></span>
            <div class="btn-update">Last updated: 11-1-2015</div>
        </div>
        <div class="btn-rich" id="btnArea">
            <a class="btn-name" href="#">Update Area</a>
            <img src="{{$loader}}" class="icon-loader">
            <span class="glyphicon glyphicon-ok icon-success" aria-hidden="true"></span>
            <div class="btn-update">Last updated: 11-1-2015</div>
        </div>
    </div>
@endsection
    
@section('body-footer')
<script>
    $(document).ready( function(){
        $('#btnProcess').click(function() {
            $(this).find('.icon-loader').show("slow");
        });
        $('#btnTaxonomy').click(function() {
            $(this).find('.icon-loader').show("slow");
            $(this).find('.icon-success').hide();
            $.ajax({
                url: "{{route('system.tools.update-taxonomy')}}", //TODO: implement backend
                success: function(data) {
                    console.log(data);
                    $('#btnTaxonomy .btn-update').text('Last updated: ' + data);
                    $('#btnTaxonomy').find('.icon-loader').hide("slow");
                    $('#btnTaxonomy').find('.icon-success').show("slow");
                },
                error: function(error) {
                    console.log(error);
                },
                type: 'POST'
            });
        });
    });
    </script>
@endsection
