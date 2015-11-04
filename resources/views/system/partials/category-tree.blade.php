{!! Form::open(array('route'=> 'system.settings.category', 
	'class' => 'form-group', 'id' => 'saveCat')) !!}
	
	<div class="panel panel-default category-tree">
		<div class="row">
	    <ul class="bonsai category">
	    @foreach ($tree as $lv1node)
	        @if (count($lv1node['subcat'] > 0))
	            <li class="has-children collapsed first-level">
	            <div class="thumb"></div>
	        @else
	            <li class="first-level">
	        @endif
	        <label>
	        	<input type="checkbox"  name="{{ $lv1node['id'] }}"
	            	data-suitable="{{ $lv1node['is_suitable']  }}" > {{ $lv1node['name'] }}
	        </label>
	        @if (count($lv1node['subcat'] > 0))
	            <ul class="bonsai">
	            @foreach ($lv1node['subcat'] as $lv2node)
	                @if (count($lv2node['subcat'] > 0))
	                    <li class="has-children collapsed">
	                    <div class="thumb"></div>
	                @else
	                    <li>
	                @endif
	                <label>
	                	<input type="checkbox"  name="{{ $lv2node['id'] }}"
	                    	data-suitable="{{ $lv2node['is_suitable']  }}" > {{ $lv2node['name'] }}
	                </label>
	                @if (count($lv2node['subcat'] > 0))
	                    <ul class="bonsai">
	                    @foreach ($lv2node['subcat'] as $lv3node)
	                    <li>
	                    <label>
	                        <input type="checkbox" name="{{ $lv3node['id'] }}"
	                            data-suitable="{{ $lv3node['is_suitable']  }}" > {{ $lv3node['name'] }}
	                    </label>
	                    </li>
	                    @endforeach
	                    </ul>
	                @endif

	                </li>
	            @endforeach
	            </ul>
	        @endif
	        </li>
	    @endforeach
	    </ul>
		</div>
	</div>
	{!! Form::close() !!}
