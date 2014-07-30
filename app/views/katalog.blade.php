@extends('layouts.master')

@section('title')
@parent
| katalóg diel
@stop

@section('content')

<section class="collection content-section top-section">
    <div class="collection-body">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center">
                        <img src="/images/x.svg" alt="x" class="xko">
                    	<h2 class="uppercase bottom-space">katalóg diel</h2>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="collections content-section">
    <div class="collections-body">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <h3>Filter: </h3>
                </div>
            	<div class="col-sm-9 container-item">
            		<h3>Diela: </h3>
                    @if ($items->count() == 0)
                        <p class="text-center">Momentálne žiadne diela</p>
                    @endif

                    <div id="iso">
                	@foreach ($items as $i=>$item)
    	                <div class="col-md-6 col-sm-6 col-xs-12 item">
    	                	<a href="{{ $item->getDetailUrl() }}">
    	                		<img src="{{ $item->getImagePath() }}" class="img-responsive">	                		
    	                	</a>
                            <div class="item-title">
                                @if (!empty($item->iipimg_url))
                                    <div class="pull-right"><a href="{{ URL::to('dielo/' . $item->id . '/zoom') }}" data-toggle="tooltip" data-placement="left" title="Zoom obrázku"><i class="fa fa-search-plus"></i></a></div>
                                @endif    
                                <a href="{{ $item->getDetailUrl() }}">
                                    <em>{{ implode(', ', $item->authors) }}</em><br>
                                <strong>{{ $item->title }}</strong>, <em>{{ $item->getDatingFormated() }}</em><br>
                                
                                <span class="">{{ $item->gallery }}</span>
                                </a>
                            </div>
    	                </div>	
                	@endforeach

                    </div>
                    {{ $items->links() }}
                </div>

            </div>
        </div>
    </div>
</section>


@stop

@section('javascript')

<script type="text/javascript">

$(document).ready(function(){

    var $container = $('#iso');
       
    // az ked su obrazky nacitane aplikuj isotope
    $container.imagesLoaded(function () {
        $container.isotope({
            itemSelector : '.item',
            masonry: {
                isFitWidth: true,
                gutter: 20
            }
        });
    });
 

$container.infinitescroll({
    navSelector     : ".pagination",
    nextSelector    : ".pagination a:last",
    itemSelector    : ".item",
    debug           : true,
    dataType        : 'html',
    path: function(index) {
        return "?page=" + index;
    }
}, function(newElements, data, url){
    var $newElems = jQuery( newElements ).hide(); 
    $newElems.imagesLoaded(function(){
        $newElems.fadeIn();
        $container.isotope( 'appended', $newElems );
    });
});


});

</script>
@stop
