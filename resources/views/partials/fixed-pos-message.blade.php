@if (Session::has('flash_notification.message'))
    <div class="alert alert-{{ Session::get('flash_notification.level') }} my-fixed-pos-alert">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

        {{ Session::get('flash_notification.message') }}
    </div>
    <script>
        var hideFixedPosMessage = function () {
            $('div.alert.my-fixed-pos-alert').not('.alert-important').not('.alert-danger').delay(3000).fadeOut('slow');
        }
        if (document.readyState !== 'complete') {
            document.addEventListener("DOMContentLoaded", function (event) {
                hideFixedPosMessage();
            });
        }
        else {
            hideFixedPosMessage();
        }
    </script>
@endif
