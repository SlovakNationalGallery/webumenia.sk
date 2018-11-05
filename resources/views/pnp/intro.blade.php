@extends('layouts.pnp')

@section('title')
@parent

@stop

@section('link')
@stop

@section('content')

<section class="intro content-section">
    <div class="intro-body">
        <div class="container">
            <img src="/images/pnp/logo.svg" class="65 unikátů" style="max-width: 300px; position: relative; top: 450px; left: 150px;">
        </div>
    </div>
</section>

<section class="articles content-section black">
    <div class="articles-body">
        <div class="container">
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        </div>
    </div>
</section>

<div class="container text-center top-space">
    <div class="fb-like" data-href="{!! Config::get('app.url') !!}" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
    &nbsp;
    <a href="https://twitter.com/share" class="twitter-share-button" data-count="true">Tweet</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
</div>

@stop

@section('javascript')

@stop