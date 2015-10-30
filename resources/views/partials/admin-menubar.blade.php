<nav class="navbar navbar-default menubar">
  <div class="container">
      <ul class="nav navbar-nav">
        <li @if (Request::is('admin/settings/category')) class="active" @endif>
        	<a href="{{url('admin/settings/category')}}"><span class="glyphicon glyphicon-list icon" aria-hidden="true"></span>Category Selection</a>
    	</li>
        <li @if (Request::is('admin/settings/process-config')) class="active" @endif>
        	<a href="{{url('admin/settings/process-config')}}"><i class="fa fa-sliders fa-lg icon"></i>Transaction Process Config</a>
    	</li>
        <li @if (Request::is('admin/settings/area-config')) class="active" @endif>
        	<a href="{{url('admin/settings/area-config')}}">
            <i class="fa fa-cogs fa-lg icon"></i>
              Area Config
          </a>
    	</li>
      </ul>
  </div><!-- /.container-fluid -->
</nav>