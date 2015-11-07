<script type="text/javascript" src="{{asset('/datatables/datatables.min.js')}}"></script>
<script>
    var selectChkboxColum = [{
        orderable: false,
        searchable: false,
        className: 'select-checkbox',
        defaultContent: "",
        data: null,
        width: "15px"
    }];
    if (typeof myIDIndex === 'undefined') {
        var myIDIndex = 0;
    }
    if (typeof mySearching === 'undefined') {
        var mySearching = true;
    }

    if (typeof myDom === 'undefined') {
        var myDom = "<'row'<'col-sm-7'lB><'col-sm-5'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-4 col-lg-3'i><'col-sm-8 col-lg-9'p>>";
    }


    var table = $('#manage-table').DataTable({
        "processing": true,
        "serverSide": true,
        searching: mySearching,
        "language": {
            "processing": "Loading..."
        },
        paging: true,
        orderCellsTop: true,
        deferRender: true,
        "ajax": myTableURL,
        "columns": selectChkboxColum.concat(myColumns),
        pagingType: "full_numbers",
        select: {
            style: 'os',
            selector: 'td:first-child',
            info: false,
        },
        order: myOrder,
        dom: myDom,
        lengthChange: true,
        rowId: myIDIndex,
        buttons: {
            buttons: [
                {
                    extend: 'selected',
                    text: '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete',
                    action: function (e, dt, button, config) {
                        var datas = dt.rows('.selected').data();
                        var ids;
                        ids = datas.pluck(myIDIndex).toArray();
                        var message = "Delete " + ids.length + (ids.length > 1 ? " rows?" : " row?");
                        bootbox.confirm(message, function (result) {
                            if (result && ids.length > 0) {
                                $.ajax({
                                    url: myDeleteURL,
                                    method: 'DELETE',
                                    data: {ids: ids},
                                    success: function (result) {
                                        dt.rows('.selected').remove().draw(false);
                                        setTimeout(function () {
                                            $('.alert#my-delete-success-message').show().delay(3000).fadeOut('slow');
                                        }, 600);
                                        if (typeof myDelSuccessFunc !== 'undefined') {
                                            myDelSuccessFunc();
                                        }
                                    },
                                    error: function (jqXHR, type, errorThrown) {
                                        if (errorThrown != null) {
                                            alert(errorThrown);
                                        }
                                    }
                                })
                            }
                        });
                    },
                },
            ],
            dom: {
                container: {
                    className: 'dt-buttons btn-group del-container'
                },
                button: {
                    className: 'btn btn-default'
                }
            }
        },
        drawCallback: function (settings) {
            if (table.rows(':not(.selected)').count() > 0) {
                uncheckSelectAll();
            }

            if (typeof myDrawCallbackFunc !== 'undefined') {
                myDrawCallbackFunc();
            }
        },
        preDrawCallback: myPreDrawCallBack,
    });
    $('#select-all-chkbox').click(function () {
        if (!$(this).hasClass("checked")) {
            table.rows().select();
            $(this).addClass("checked");
        }
        else {
            $(this).removeClass('checked');
            table.rows().deselect();
        }
    });
    var selectAllChkbox = $('#select-all-chkbox');
    uncheckSelectAll = function () {
        if (selectAllChkbox.hasClass('checked')) {
            selectAllChkbox.removeClass('checked');
        }
    }
    table.on('select', function (e, dt, type, indexes) {
        uncheckSelectAll();
    });
    table.on('deselect', function (e, dt, type, indexes) {
        uncheckSelectAll();
    });
</script>
<script src="{{asset('/js/bootbox.min.js')}}"></script>