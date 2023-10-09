@extends('layouts.master')

@section('title')
Pattern Library | @parent
@stop

@section('link')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/default.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
@stop

@section('content')

<script type="application/javascript">
    function toggle_source(event) {
        event.preventDefault();
        $(event.target).parent().find('pre.js-source').toggleClass('hidden');
        var txt = $(event.target).parent().find('pre.js-source').hasClass('hidden') ? '<i class="fa fa-code"></i> Show source' : '<i class="fa fa-code"></i> Hide source';
        $(event.target).html(txt);
    }
</script>

<section>
    <div class="container">
        <h1>Pattern Library</h1>

        @foreach ($components as $component)

            <section class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="panel-title">{{$component['name']}}</h2>
                        </div>
                        <div class="panel-body">
                            <div class="clearfix">
                                <div class="tw-relative {{ isset($component['wrapper_classes']) ? $component['wrapper_classes'] : '' }}">
                                    @include($component['include_path'], $component['data'])
                                    @if (isset($component['include_path_js']))
                                        @section('javascript')
                                            @if (isset($component['data_js']))
                                                @include($component['include_path_js'], array_merge($component['data'], $component['data_js']))
                                            @else
                                                @include($component['include_path_js'], $component['data'])
                                            @endif

                                        @append
                                    @endif
                                    @if (isset($component['js_asset']))
                                        @section('javascript')
                                            <script type="text/javascript" src="{!! asset('js/components/' . $component['js_asset']) !!}"></script>
                                        @append
                                    @endif
                                </div>
                            </div>

                            @if (isset($component['usage_notes']))
                            <h4>Usage notes</h4>
                            <p>{{$component['usage_notes']}}</p>
                            @endif

                            <a href="#" class="btn btn-default btn-outline sans" onclick="toggle_source(event);"><i class="fa fa-code"></i> Show source</a>
                            <pre class="js-source pre-scrollable hidden" v-pre><code class="html">{{$component['source_code']}}</code></pre>
                        </div>
                    </div>
                </div>
            </section>
        @endforeach
    </div>
</section>

@stop
