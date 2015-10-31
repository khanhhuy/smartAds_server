<script>
    function search() {
        for (var i = 1; i < 8; i++) {
            var col = table.column(i);
            var selector = "input#search_" + COLS[i - 1];
            if (i < divider) {
                col.search($(selector).val());
            }
            else {
                var from = $(selector + "_from").val();
                var to = $(selector + "_to").val();
                if (!from) {
                    from = null;
                }
                if (!to) {
                    to = null;
                }
                col.search(from + "," + to);
            }
        }
        table.draw();

    }
    $('input.table-search').keypress(function (e) {
        if (e.which == 13 || e.keyCode == 13) {
            search();
        }
    });

    function resetSearch() {
        $('input.table-search').val('');
        search();
        $('input.table-search[type=date]').attr('type', 'text');
    }
</script>