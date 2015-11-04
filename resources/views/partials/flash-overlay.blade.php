@if (Session::has('flash_notification.message')&&Session::has('flash_notification.overlay'))
    @include('flash::modal', ['modalClass' => 'flash-modal', 'title' => Session::get('flash_notification.title'), 'body' => Session::get('flash_notification.message')])
@endif