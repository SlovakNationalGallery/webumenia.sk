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
            <h1 class="text-center black">
                Wenceslaus Hollar Bohemus<br>
                — řeč jehly a rydla —
            </h1>
            <h2 class="text-center black"><em>
                virtuální kolekce děl jednoho <br>
                z nejvýznamnějších barokních rytců</em>
            </h2>
        </div>
    </div>
</section>

<section class="articles content-section black">
    <div class="articles-body">
        <div class="container">
            <div class="row">
            	@foreach ($collections as $i=>$collection)
	                <div class="col-sm-6 col-xs-12 bottom-space">
	                	<a href="{!! $collection->getUrl() !!}" class="featured-article bottom-space">
	                		<img src="{!! $collection->getHeaderImage() !!}" class="img-responsive " alt="{!! $collection->title !!}">
	                	</a>
                        <a href="{!! $collection->getUrl() !!}">
                        <h4 class="title">
                            {!! $collection->name !!}
                        </h4>
                        </a>
	                </div>
                    @if ($i%2 == 1)
                        <div class="clearfix"></div>
                    @endif
            	@endforeach
            </div>
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
