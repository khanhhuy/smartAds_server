@extends('system.admin-system')

<?php
$taxonomyLastUpdated = Setting::get('taxonomy.updated_at');
$taxnomyUpdating = ($taxonomyLastUpdated === 'Updating');
if ($taxnomyUpdating) {
    $taxonomyLastUpdated .= ' ...';
}
$loader = asset('/img/icon/rolling.svg');
?>

@section('head-footer-child')
    <link rel="stylesheet" type="text/css" href="{{asset('/css/admin-tool.css')}}">
@endsection

@section('title','Tools')
@section('page-title','Tools')
@section('breadcrumb-child')
    <li class="active">Tools</li>
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

        <div class="btn-update">Last updated: {{$taxonomyLastUpdated}}</div>
    </div>
    <div class="btn-rich" id="btnStoreArea">
        <div class="btn-name">Update Area</div>
        <img src="{{$loader}}" class="icon-loader">
        <span class="glyphicon glyphicon-ok icon-success" aria-hidden="true"></span>

        <div class="btn-update">Last updated: 11-1-2015</div>
    </div>
@endsection

@section('body-footer')
    <script>
        function handleClick(id, urlRequest, urlStatus) {
            $('#' + id + ' .icon-loader').show("slow");
            $('#' + id + ' .btn-update').text('Last updated: Updating ...');
            $.ajax({
                method: 'POST',
                url: urlRequest,
                success: function (data) {
                    console.log(data);
                    pollingUpdateStatus(id, urlStatus);
                },
                error: function (jqXHR, type, errorThrown) {
                    $('#' + id + ' .icon-loader').hide("slow");
                    if (errorThrown != null) {
                        alert(errorThrown);
                    }
                }
            });
        }
        var TAXONOMY_URL = ["{{route('taxonomy.update-requests.process')}}", "{{route('taxonomy.update-status')}}"];
        var STORE_AREA_URL = ['{{route('stores.update-requests.process')}}', '{{route('stores.update-status')}}'];
        $(document).ready(function () {
            $('#btnProcess').click(function () {
                $(this).find('.icon-loader').show("slow");
            });
            $('#btnTaxonomy').click(function () {
                handleClick('btnTaxonomy', TAXONOMY_URL[0], TAXONOMY_URL[1]);
            });
            $('#btnStoreArea').click(function () {
                handleClick('btnStoreArea', TAXONOMY_URL[1], STORE_AREA_URL[1]);
            });
        });
        function pollingUpdateStatus(id, urlStatus) {
            $.ajax({
                url: urlStatus,
                success: function (data) {
                    console.log(id + ": " + data);
                    if (data === "Updating") {
                        setTimeout(pollingUpdateStatus(id, urlStatus), 1000);
                    }
                    else {
                        $('#' + id + ' .btn-update').text('Last updated: ' + data);
                        $('#' + id + ' .icon-loader').hide("slow");
                        $('#' + id + ' .icon-success').show("slow");
                    }
                },
                error: function (jqXHR, type, errorThrown) {
                    $('#' + id + ' .icon-loader').hide("slow");
                    if (errorThrown != null) {
                        alert(errorThrown);
                    }
                }
            });
        }
        @if($taxnomyUpdating)
            $('#btnTaxonomy .icon-loader').show();
        pollingUpdateStatus('btnTaxonomy', TAXONOMY_URL[1]);
        @endif
    </script>
@endsection
