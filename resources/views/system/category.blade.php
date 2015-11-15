@extends('system.admin-system')

@section('head-footer-child')
    <link rel="stylesheet" type="text/css"
          href="{{asset('js/jQuery-bonsai/bower_components/jquery-bonsai/jquery.bonsai.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('js/jQuery-bonsai/bower_components/jquery-bonsai/assets/svg-icons.css') }}">
    <link rel="stylesheet" type="text/css" href={!! URL::asset('css/admin-select.css') !!}>

    <script type="text/javascript"
            src={!! URL::asset('js/jQuery-bonsai/bower_components/jquery-qubit/jquery.qubit.js') !!} defer></script>
    <script type="text/javascript"
            src={!! URL::asset('js/jQuery-bonsai/bower_components/jquery-bonsai/jquery.bonsai.js') !!} defer></script>
@endsection

@section('title','Smart Promotion Category Selection')
@section('page-title','Smart Promotion Category Selection')
@section('breadcrumb-child')
    <li><a href="{{url('admin/system/settings')}}">Settings</a></li>
    <li class="active">Category Selection</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <div class="panel panel-default categories-selection">
                <div class="panel-body">
                    <div>
                        <div class="categories-label-container">
                            <label class="control-label">Categories</label>
                        </div>
                        <a href="{{url('admin/system/settings')}}" class="btn btn-default my-cancel-btn"
                           id="cancelBtn">
                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel
                        </a>
                        <button class="btn btn-primary" id="saveBtn">
                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save
                        </button>
                        <div style="clear:both"></div>
                    </div>
                    {!! Form::open(array('route'=> 'system.settings.category',
                    'class' => 'form-group', 'id' => 'saveCat')) !!}

                    @include('system.partials.category-tree')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    @include('partials.save-success')
@endsection

@section('body-footer')
    <script>
        $(document).ready(function () {
            $("input[data-suitable=1]").prop("checked", true);
            var totalList = $(".category .first-level").size();
            var halfList = Math.floor(totalList / 2);
            $(".category").each(function () {
                $(this).children(':gt(' + halfList + ')').detach().wrapAll('<ul class="bonsai category"></ul>').parent().insertAfter(this);
            });
            $(".category").each(function () {
                $(this).wrapAll('<div class="col-md-6"></div>');
            });
            $('.category').bonsai({
                expandAll: false,
                checkboxes: true, // depends on jquery.qubit plugin
                handleDuplicateCheckboxes: true // optional
            });
            $('button#saveBtn').click(function () {
                var form = $('form#saveCat');
                $.ajax({
                    url: form.prop("action"),
                    method: "POST",
                    data: form.serialize(),
                    success: function (data) {
                        $('.alert#my-save-success-message').show().delay(3000).fadeOut('slow');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        if (errorThrown != null) {
                            alert(errorThrown);
                        }
                    }
                })
            });
        });
    </script>
@endsection