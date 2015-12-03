@extends('layouts.blank')

@section('title')
skicare | 
@parent
@stop

@section('content')
<link rel="stylesheet" type="text/css" href="/css/style.css">
<style type="text/css">
  body {margin: 0 !important; padding: 0 !important;}
  #skicare {
    display: flex;
      align-items: center;
      /*justify-content: center;*/
      flex-direction: row;
      flex-wrap: nowrap;
      justify-content: flex-start;
      height:100%;
             position:absolute;
             top: -30px;
             width:100%;
             padding: 20px;
  }
  #skicare .skicar {
	  /*max-width: 50%;*/
	  display: block;
	  /*border: 1px solid red;*/
	  margin: auto 10px;
	  /*flex:1;*/
	  flex:none;
  }
  #skicare .skicar img {
  	/*max-height: 300px;*/
  }
  .big {
  	font-size: 36px;
  }
  .big, .title { margin-left: 2px;}
</style>

<div id="skicare">
@foreach ($sketchbooks as $i=>$sketchbook)
	<a class="skicar" href="{{ $sketchbook->file }}" style="height: {{ ($sketchbook->height / $max_height)*90 }}%; width: auto;">
	<span class="big">#{{ ($i+1) }}</span><br>
	<img src="{{ $sketchbook->item->getImagePath() }}" class="img-responsive" style="height: {{ ($sketchbook->height / $max_height)*100 }}%; width: auto;" >
	{{-- <br> --}}
	<span class="title">{{ $sketchbook->title }}</span>
	</a>

@endforeach
</div>

@stop
