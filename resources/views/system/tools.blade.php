@extends('system.admin-system')

<?php
$loader = asset('/img/icon/rolling.svg');
?>

@section('head-footer-child')
    <link rel="stylesheet" type="text/css" href="{{asset('/css/admin-tool.css')}}">
@endsection

@section('title','Admin Tools')
@section('page-title','Admin Tools')
@section('breadcrumb-child')
    <li class="active">Admin Tools</li>
@endsection

@section('content')
    <div class="btn-rich" id="btnProcess">
        <div class="btn-name">Re-Process</div>
        <img src="{{$loader}}" class="icon-loader">
        <span class="glyphicon glyphicon-ok icon-success" aria-hidden="true"></span>

        <div class="btn-update">Last updated: 11-1-2015</div>
    </div>
    <div class="btn-rich" id="btnTaxonomy">
        <div class="btn-name">Update Taxonomy</div>
        <img src="{{$loader}}" class="icon-loader">
        <span class="glyphicon glyphicon-ok icon-success" aria-hidden="true"></span>

        <div class="btn-update">Last updated: {{$lastUpdated['taxonomy']}}</div>
    </div>
    <div class="btn-rich" id="btnStoreArea">
        <div class="btn-name">Update Store List</div>
        <img src="{{$loader}}" class="icon-loader">
        <span class="glyphicon glyphicon-ok icon-success" aria-hidden="true"></span>

        <div class="btn-update">Last updated: {{$lastUpdated['stores_areas']}}</div>
    </div>
@endsection

@section('body-footer')
    <script>
        var OBJS = {
            'taxonomy': {
                id: "#btnTaxonomy",
                requestURL: "{{route('taxonomy.update-requests.process')}}",
                statusURL: "{{route('taxonomy.update-status')}}",
                updating: false,
            },
            'stores_areas': {
                id: "#btnStoreArea",
                requestURL: "{{route('stores.update-requests.process')}}",
                statusURL: "{{route('stores.update-status')}}",
                updating: false,
            }
        };
        function showUpdatingIndicator(obj) {
            obj.updating = true;
            $(obj.id + ' .icon-success').hide();
            $(obj.id + ' .icon-loader').show("slow");
            $(obj.id + ' .btn-update').text('Last updated: Updating ...');
        }
        function handleClick(name) {
            var obj = OBJS[name];
            if (!obj.updating) {
                showUpdatingIndicator(obj);
                $.ajax({
                    method: 'POST',
                    url: obj.requestURL,
                    success: function (data) {
                        console.log(data);
                        pollingUpdateStatus(obj);
                    },
                    error: function (jqXHR, type, errorThrown) {
                        $(obj.id + ' .icon-loader').hide("slow");
                        if (errorThrown != null) {
                            alert(errorThrown);
                        }
                    }
                });
            }
        }

        $(document).ready(function () {
            $('#btnProcess').click(function () {
                $(this).find('.icon-loader').show("slow");
            });
            var obj;
            @foreach($names as $name)
                obj = OBJS['{{$name}}'];
            $(obj.id).click(function () {
                handleClick('{{$name}}');
            });
            @endforeach




        });
        function pollingUpdateStatus(obj) {
            $.ajax({
                url: obj.statusURL,
                success: function (data) {
                    console.log(obj.id + ": " + data);
                    if (data === "Updating") {
                        setTimeout(function () {
                            pollingUpdateStatus(obj);
                        }, 1000);
                    }
                    else {
                        $(obj.id + ' .btn-update').text('Last updated: ' + data);
                        $(obj.id + ' .icon-loader').hide();
                        $(obj.id + ' .icon-success').show("slow");
                        obj.updating = false;
                    }
                },
                error: function (jqXHR, type, errorThrown) {
                    $(obj.id + ' .icon-loader').hide("slow");
                    if (errorThrown != null) {
                        alert(errorThrown);
                    }
                }
            });
        }
        @foreach($names as $name)
            @if ($updating[$name])
                obj = OBJS['{{$name}}'];
        showUpdatingIndicator(obj);
        pollingUpdateStatus(obj);
        @endif
        @endforeach
    </script>
@endsection
