<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Select category</title>

    <link rel="stylesheet" type="text/css" href={!! URL::asset('js/jQuery-bonsai/bower_components/jquery-bonsai/jquery.bonsai.css') !!}>
    <link rel="stylesheet" type="text/css" href={!! URL::asset('js/jQuery-bonsai/bower_components/jquery-bonsai/assets/svg-icons.css') !!}>

    <script type="text/javascript" src={!! URL::asset('js/jQuery-bonsai/bower_components/jquery/dist/jquery.min.js') !!}></script>
    <script type="text/javascript" src={!! URL::asset('js/jQuery-bonsai/bower_components/jquery-qubit/jquery.qubit.js') !!}></script>
    <script type="text/javascript" src={!! URL::asset('js/jQuery-bonsai/bower_components/jquery-bonsai/jquery.bonsai.js') !!}></script>
</head>
<body>
    <div class="container">

        {!! Form::open(array('url' => 'process-trans/category')) !!}

            <ul id="category" class="bonsai">
            @foreach ($tree as $lv1node)
                @if (count($lv1node['subcat'] > 0))
                    <li class="has-children collapsed">
                    <div class="thumb"></div>
                @else
                    <li>
                @endif
                <input type="checkbox"  name="{{ $lv1node['id'] }}"
                    data-suitable="{{ $lv1node['is_suitable']  }}" > {{ $lv1node['name'] }}

                @if (count($lv1node['subcat'] > 0))
                    <ul class="bonsai">
                    @foreach ($lv1node['subcat'] as $lv2node)
                        @if (count($lv2node['subcat'] > 0))
                            <li class="has-children collapsed">
                            <div class="thumb"></div>
                        @else
                            <li>
                        @endif
                        <input type="checkbox"  name="{{ $lv2node['id'] }}"
                            data-suitable="{{ $lv2node['is_suitable']  }}" > {{ $lv2node['name'] }}

                        @if (count($lv2node['subcat'] > 0))
                            <ul class="bonsai">
                            @foreach ($lv2node['subcat'] as $lv3node)
                            <li>
                                <input type="checkbox" name="{{ $lv3node['id'] }}"
                                    data-suitable="{{ $lv3node['is_suitable']  }}" > {{ $lv3node['name'] }}
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

        {!! Form::submit('Save') !!}
        {!! Form::close() !!}

        <script>
            jQuery(function() {
                $('#category').bonsai({
                    expandAll: false,
                    checkboxes: true, // depends on jquery.qubit plugin
                    handleDuplicateCheckboxes: true // optional
                });
            });

            $("input[data-suitable=1]").prop("checked", true);
        </script>

    </div>
</body>
</html>
