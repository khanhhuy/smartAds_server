<script src="{{asset('/js/select2.min.js')}}"></script>
<script>
    templateFunc = function (item) {
        if (typeof item.name === "undefined") {
            return item.text;
        }
        else {
            return item.name + " [" + item.id + "]";
        }
    };

    $('#itemsID').select2({
        ajax: {
            delay: 100,
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
        minimumInputLength: 3,
        templateResult: templateFunc,
        templateSelection: templateFunc,
    });
    $('#targetsID').select2();

    var wholeSystemCheckbox = $("#is_whole_system")[0];
    if (wholeSystemCheckbox.checked) {
        $('#target-group').hide();
    }
    else {
        $('#target-group select').attr('required', 'required');
    }
    $("#is_whole_system").change(function () {
        if (!this.checked) {
            $('#target-group').show('fast');
            $('#target-group select').attr('required', 'required');
        }
        else {
            $('#target-group').hide('fast');
            $('#target-group select').removeAttr('required');
        }
    });
    var auto_thumbnail = $("#auto_thumbnail")[0];
    if (auto_thumbnail.checked) {
        $('#thumbnailInputGroup').hide();
    }
    $("#auto_thumbnail").change(function () {
        if (!this.checked) {
            $('#thumbnailInputGroup').show('fast');
        }
        else {
            $('#thumbnailInputGroup').hide('fast');
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
            $('#auto_thumbnail').prop('checked', true);
            $('#auto_thumbnail').trigger('change');
            $('#auto_thumbnail').prop('disabled', false);
        }
        else {
            $('#imageInputGroup').hide('fast');
            $('#webInputGroup').show('fast');

            setRequiredWebGroup(true);
            setRequiredImageGroup(false);
            $('#auto_thumbnail').prop('checked', false);
            $('#auto_thumbnail').trigger('change');
            $('#auto_thumbnail').prop('disabled', true);
        }
    });
    if ($('input[name=image_display]:radio:checked').val() == 0) {
        $('#imageInputGroup').hide();
        $('#webInputGroup').show();
        setRequiredWebGroup(true);
        setRequiredImageGroup(false);
    }
    else {
        setRequiredImageGroup(true);
    }

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

    var tabThumbnailLink = $('a[data-toggle="tab"][aria-controls="thumbnail-link"]');
    tabThumbnailLink.on('shown.bs.tab', function () {
        $('#provide_thumbnail_link').val(1);
    });
    tabThumbnailLink.on('hidden.bs.tab', function () {
        $('#provide_thumbnail_link').val(0);
    });


    //validate
    $('#end_date').popover({
        trigger: 'manual',
        html: true,
        content: function () {
            return $('#date-error').html();
        }
    });
    $('.promotion-form').submit(function (e) {
        e.preventDefault();
        var start_date = $('#start_date').val(), end_date = $('#end_date').val();
        var ts = Date.parse(start_date), te = Date.parse(end_date);
        var cancel = false;
        if (!isNaN(ts) && !isNaN(te)) {
            if (new Date(start_date) > new Date(end_date)) {
                $('#end_date').popover('show');
                $('#end_date').focus();
                setTimeout(function () {
                    $('#end_date').popover('hide');
                }, 5000);
                cancel = true;
            }
        }
        if (!cancel) {
            this.submit();
        }
    });
</script>