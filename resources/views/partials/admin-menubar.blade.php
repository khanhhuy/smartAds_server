<nav class="navbar navbar-default menubar">
    <div class="container">
        <ul class="nav navbar-nav">
            <li @if (Request::is('admin/system/settings*')) class="active" @endif>
                <a href="{{url('admin/system/settings')}}"><span class="glyphicon glyphicon-cog"
                                                                 aria-hidden="true"></span> Settings</a>
            </li>
            <li @if (Request::is('admin/system/tools')) class="active" @endif>
                <a href="{{url('admin/system/tools')}}">
                    <span class="glyphicon glyphicon-wrench"></span> Admin Tools
                </a>
            </li>
        </ul>
    </div>
    <!-- /.container-fluid -->
</nav>