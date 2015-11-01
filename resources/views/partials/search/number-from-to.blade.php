<?php
if (isset($max)) {
    $maxGroup = 'max="' . $max . '"';
} else {
    $maxGroup = '';
}
?>
<td>
    <div>
        <input type="number" name="search_{{$name}}_from" id="search_{{$name}}_from" ,
               class="form-control table-search search_{{$name}}" min="{{$min}}" step="{{$step}}" {{$maxGroup}} ,
               placeholder=" From"/>
    </div>
    <input type="number" name="search_{{$name}}_to" id="search_{{$name}}_to" ,
           class="form-control second-row-in-cell table-search search_{{$name}}" min="{{$min}}" step="{{$step}}"
           {{$maxGroup}} ,
           placeholder=" To"/>
</td>