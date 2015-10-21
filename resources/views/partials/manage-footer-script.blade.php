<script type="text/javascript" src="{{asset('/datatables/datatables.min.js')}}"></script>
<script>
    var selectChkboxColum=[{
        sortable: false,
        className: 'select-checkbox',
        defaultContent: "",
        data: null,
        width: "15px"
    }];
    var editBtnColumn = [{
        orderable: false,
        render: function (data, type, row, meta) {
            return '<a class="my-edit-btn" role="button" href="' + row[0] + '/edit">' +
                    '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit</a>';
        }
    }];

    var table = $('#manage-table').DataTable({
        "processing": true,
        "serverSide": true,
        paging: true,
        "ajax": myTableURL,
        "columns": selectChkboxColum.concat(myColumns,editBtnColumn),
        pagingType: "full_numbers",
        select: {
            style: 'os',
            selector: 'td:first-child'
        },
        order: myOrder,
        dom: "<'row'<'col-sm-6'lB><'col-sm-6'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        lengthChange: true,
        buttons: {
            buttons: [
                {
                    extend: 'selected',
                    text: '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete',
                    action: function (e, dt, button, config) {
                        var ids = dt.rows('.selected').data().pluck(0).toArray();
                        var message = "Delete " + ids.length + (ids.length > 1 ? " rows?" : " row?");
                        bootbox.confirm(message, function (result) {
                            if (result && ids.length > 0) {
                                $.ajax({
                                    url: myDeleteURL,
                                    method: 'DELETE',
                                    data: {ids: ids},
                                    success: function (result) {
                                        dt.rows('.selected').remove().draw(false);
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
                }
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
        },
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