@extends('layouts.master')

@section('title')
Styleguide |
@parent
@stop

@section('link')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/default.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
@stop

@section('content')

            <script>
                function toggle_source(event) {
            event.preventDefault();
                    $(event.target).parent().find('pre.js-source').toggleClass('hidden');
                    var txt = $(event.target).parent().find('pre.js-source').hasClass('hidden') ? '<i class="fa fa-code"></i> Show source' : '<i class="fa fa-code"></i> Hide source';
                    $(event.target).html(txt);
                }
            </script>
<section>
    <div class="container">
        <h1>Styleguide</h1>
        
        @foreach ($components as $component)
            <section>
                <h2>{{$component['name']}}</h2>

                <div class="pull-left">
                    @include($component['include_path'], $component['data'])
                </div>

                <br>
                <br>
                <br>
                <br>

                
                <h4>usage notes</h4> 
                <p>{{$component['usage_notes']}}</p>

                <a href="#" class="btn btn-default btn-outline sans" onclick="toggle_source(event);"><i class="fa fa-code"></i> Show source</a>
                <pre class="js-source pre-scrollable hidden"><code class="html">{{$component['source_code']}}</code></pre>
            </section>

        @endforeach
    </div>
</section>

@stop