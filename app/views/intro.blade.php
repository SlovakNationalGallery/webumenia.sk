@extends('layouts.master')

@section('title')
@parent
- úvod
@stop

@section('content')

<section class="intro">
    <div class="intro-body">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <p class="intro-text">
                    	<h1>DVE KRAJINY</h1>
                    	<h2>OBRAZ SLOVENSKA</h2>
                    	<h3>19. storočie X súčasnosť</h3>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="collections content-section">
    <div class="collections-body">
        <div class="container">
            <div class="row">
            	<div class="col-xs-12">
            		<h3>Výstavné sekcie: </h3>
            	</div>
            	@foreach ($collections as $collection)
	                <div class="col-md-4 col-sm-6 col-xs-12">
	                	<a href="{{ URL::to('sekcia/' . $collection->id) }}" class="featured-collection">
	                		<img src="{{ URL::to('images/sekcie/' . $collection->id . '.jpeg') }}" class="img-responsive">
	                		<h4 class="title">{{ $collection->name }}</h4>
	                	</a>
	                    
	                </div>	
            	@endforeach
            </div>
        </div>
    </div>
</section>

<section class="map content-section">
    <div class="map-body">
        <div class="container">
            <div class="row">
            	<div class="col-xs-12">
            		<h3>Diela na mape: </h3>
            	</div>
            	<div id="map"></div>
            </div>
        </div>
    </div>
</section>

<p>&nbsp;</p>
<p>&nbsp;</p>

@stop
