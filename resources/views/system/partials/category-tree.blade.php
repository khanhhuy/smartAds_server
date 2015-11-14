<div class="panel panel-default category-tree no-heading-panel">
    <div class="panel-body">
	<div class="row">
        <ul class="bonsai category">
        @foreach ($tree as $lv1node)
            @if (count($lv1node['subcat'] > 0))
                <li class="has-children collapsed first-level"  id="li_{{ $lv1node['id'] }}">
                <div class="thumb"></div>
            @else
                <li class="first-level" id="{{ $lv1node['id'] }}">
            @endif
            <label>
            	<input type="checkbox"  name="{{ $lv1node['id'] }}" id="node_{{$lv1node['id'] }}"
                       data-suitable="{{ $lv1node['is_suitable']  }}">
                	<span>{{ $lv1node['name'] }}</span>
            </label>
            @if (count($lv1node['subcat'] > 0))
                <ul class="bonsai">
                @foreach ($lv1node['subcat'] as $lv2node)
                    @if (count($lv2node['subcat'] > 0))
                        <li class="has-children collapsed" id="li_{{ $lv2node['id'] }}">
                        <div class="thumb"></div>
                    @else
                        <li id="{{ $lv2node['id'] }}">
                    @endif
                    <label>
                    	<input type="checkbox"  name="{{ $lv2node['id'] }}" id="node_{{ $lv2node['id'] }}"
                               data-suitable="{{ $lv2node['is_suitable']  }}">
                        	<span>{{ $lv2node['name'] }}</span>
                    </label>
                    @if (count($lv2node['subcat'] > 0))
                        <ul class="bonsai">
                        @foreach ($lv2node['subcat'] as $lv3node)
                        <li id="li_{{ $lv3node['id'] }}">
                        <label>
                            <input type="checkbox" name="{{ $lv3node['id'] }}" id="node_{{ $lv3node['id'] }}"
                                   data-suitable="{{ $lv3node['is_suitable']  }}">
                                <span>{{ $lv3node['name'] }}</span>
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
</div>