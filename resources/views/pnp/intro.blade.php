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
            <p class="lead tagline text-center">
                Wenceslaus Hollar Bohemus<br>
                — řeč jehly a rydla —<br>
            </p>

            <p class="lead text-center">
                virtuální kolekce děl jednoho <br>
                z nejvýznamnějších barokních rytců
            </p>
        </div>
    </div>
</section>

<section class="articles content-section">
    <div class="articles-body">
        <div class="container">
            <div class="row">
            	@foreach ($collections as $i=>$collection)
	                <div class="col-sm-4 col-xs-12 bottom-space">
	                	<a href="{!! $collection->getUrl() !!}" class="featured-collection">
	                		<img src="{!! $collection->getHeaderImage() !!}" class="img-responsive" alt="{!! $collection->title !!}">
	                	</a>
                        <a href="{!! $collection->getUrl() !!}">
                        <h4 class="title">
                            {!! $collection->name !!}
                        </h4>
                        </a>
                        <p class="attributes">
                            {!! $collection->getShortTextAttribute($collection->text, 350) !!}
                        (<a href="{!! $collection->getUrl() !!}">{{ trans('general.more') }}</a>)
                        </p>
	                </div>
                    @if ($i%3 == 2)
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
