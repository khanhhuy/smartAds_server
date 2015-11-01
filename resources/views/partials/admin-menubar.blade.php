<nav class="navbar navbar-default menubar">
  <div class="container">
      <ul class="nav navbar-nav">
        <li @if (Request::is('admin/system/settings')) class="active" @endif>
        	<a href="{{url('admin/system/settings')}}"><i class="fa fa-sliders fa-lg icon"></i>Settings</a>
    	  </li>
        <li @if (Request::is('admin/system/tools')) class="active" @endif>
        	<a href="{{url('admin/system/tools')}}">
            <i class="fa fa-cogs fa-lg icon"></i>
              Tools
          </a>
    	 </li>
      </ul>
  </div><!-- /.container-fluid -->
</nav>