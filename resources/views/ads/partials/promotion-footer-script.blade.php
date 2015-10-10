<script src="{{asset('/js/select2.min.js')}}"></script>
<script>
    templateFunc = function (item) {
        if (typeof item.id === "undefined") {
            return item.text;
        }
        else {
            return item.name + "[" + item.id + "]";
        }
    };
    $('#targetsID').select2();
    $('#target-group').hide();
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
        templateResult: templateFunc,
        templateSelection: templateFunc
    });
    $("#is_whole_system").change(function () {
        if (!this.checked) {
            $('#target-group').show('fast');
            $('#target-group select').attr('required', 'required');
        }
        else {
            $('#target-group').hide('fast');
            $('#target-group select').removeAttr('required', 'required');
        }
    });
    function setRequiredImageGroup(required) {
        if (!required) {
            $('#imageInputGroup input').removeAttr('required');
        }
        else {
            $('#imageInputGroup .tab-pane.active input').attr('required', 'required');
        }
    }
    function setRequiredWebGroup(required) {
        var input = $('#webInputGroup input');
        if (!required) {
            input.removeAttr('required');
        }
        else {
            input.attr('required', 'required');
        }
    }
    $('input[name=image_display]:radio').change(function () {
        if (this.value == 1) {
            $('#webInputGroup').hide('fast');
            $('#imageInputGroup').show('fast');

            setRequiredImageGroup(true);
            setRequiredWebGroup(false);
        }
        else {
            $('#imageInputGroup').hide('fast');
            $('#webInputGroup').show('fast');

            setRequiredWebGroup(true);
            setRequiredImageGroup(false);
        }
    });
    updateRequiredInImageGroup = function () {
        $('#imageInputGroup input').removeAttr('required');
        $('#imageInputGroup .tab-pane.active input').attr('required', 'required');
    }
    var tabImageLink = $('a[data-toggle="tab"][aria-controls="image-link"]');
    tabImageLink.on('shown.bs.tab', function () {
        updateRequiredInImageGroup();
        $('#provide_image_link').val(1);
    });
    tabImageLink.on('hidden.bs.tab', function () {
        updateRequiredInImageGroup();
        $('#provide_image_link').val(0);
    });
</script>