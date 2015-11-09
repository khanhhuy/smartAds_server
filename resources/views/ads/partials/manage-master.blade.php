@extends('manager-master')

@section('head-footer')
    <link rel="stylesheet" type="text/css" href="{{asset('/datatables/datatables.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('/css/manage.css')}}"/>
@endsection
@section('content')
    <div class="panel panel-default">
        @include('partials.fixed-pos-message')
        <div class="panel-body">
            <div id="manage-ads">
                <div class="table-responsive">
                    <table id="manage-table" class="table table-striped table-bordered table-hover" cellspacing="0"
                           width="100%">
                        @yield('table-header')
                    </table>

                </div>
            </div>
        </div>
    </div>
    @include('partials.delete-success')
    @yield('content-footer')
@endsection

@section('body-footer')
    <script>
        var myTableURL = "@yield('table-url')";
        var myOrder = [];
        var myDom = "<'row'<'col-sm-7'lB><'col-sm-5'i>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12'p>>";
        var myDeleteURL = '{{route('ads.deleteMulti')}}';
                @yield('my-columns')
                var myPreDrawCallBack = function () {
            if (typeof lastClickIDCol !== 'undefined' && lastClickIDCol != null) {
                lastClickIDCol.popover('destroy');
            }
        }
    </script>

    @include('partials.manage-footer-script')

    <script>
        //view details
        var lastClickIDCol = null;
        var BASE_ADS_URL = "{{url('ads')}}";
        $('#manage-table').find('tbody').on('click', 'tr', function (e) {
            if (e.target.tagName.toLowerCase() !== 'td' || e.target.cellIndex === 0 || e.target.cellIndex === 8) {
                return ;
            }
            var idCol = $(this).find('td:nth-child(2)');
            var sameRow = false;
            if (lastClickIDCol != null) {
                if (lastClickIDCol[0] != idCol[0]) {
                    lastClickIDCol.popover('destroy');
                }
                else {
                    sameRow = true;
                }
            }
            if (!sameRow) {
                var previewURL = BASE_ADS_URL + '/' + idCol.text() + "/preview";
                idCol.popover({
                    animation: false,
                    trigger: 'manual',
                    html: true,
                    container: '#manage-ads',
                    content: '<iframe width="400px" height="455px" style="border:none;" src="' + previewURL + '"></iframe>',
                });
            }
            idCol.popover('toggle');
            lastClickIDCol = idCol;
            e.stopPropagation();
        });
        $('body').click(function (e) {
            if (typeof lastClickIDCol !== 'undefined' && lastClickIDCol != null) {
                lastClickIDCol.popover('hide');
            }
        });

        //search
        @yield('table-search-init')

        $('#flash-overlay-modal').modal();
    </script>
    @include('partials.search.footer-script')
@endsection