@extends('layouts.master')

@section('title')
@parent
- úvod
@stop

@section('content')

<section class="collection content-section top-section">
    <div class="collection-body">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center">
                        <img src="/images/x.svg" alt="x" class="xko">
                    	<h1>{{ $collection->name }}</h1>
                        <p>{{ nl2br($collection->text) }}</p>
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
            		<h3>Diela: </h3>
            	</div>
            	@foreach ($collection->items as $item)
	                <div class="col-md-4 col-sm-6 col-xs-12">
	                	<a href="{{ URL::to('sekcia/' . $collection->id) }}">
	                		<img src="{{ $item->getImagePath(); }}" class="img-responsive">	                		
	                	</a>
                        <div class="item-title">
                            {{ $item->author }} <br />
                            <strong>{{ $item->title }}</strong><br>
                            <em>{{ $item->dating }}</em>
                        </div>
                        
	                    
	                </div>	
            	@endforeach
            </div>
        </div>
    </div>
</section>

<div id="map"></div>

@stop
