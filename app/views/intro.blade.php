@extends('layouts.master')

@section('title')
@parent

@stop

@section('content')

    <div id="webumeniaCarousel" class="carousel slide carousel-fade" data-ride="carousel">
      {{-- <div class="container"> --}}
      <!-- Indicators -->
        <ol class="carousel-indicators">
          <li data-target="#webumeniaCarousel" data-slide-to="0" class="active"></li>
          <li data-target="#webumeniaCarousel" data-slide-to="1"></li>
          <li data-target="#webumeniaCarousel" data-slide-to="2"></li>
        </ol>

      <div class="carousel-inner" role="listbox">

        <div class="item active ">
            <a href="/clanok/spustili-sme-novy-web-umenia" class="header-image text-center" style="background-image: url(/images/clanky/psicek-a-macicka-sa-tesia.jpg); text-shadow:0px 1px 0px #777; color: #fff">
                <div class="header-body">
                    <h1>Spustili sme nový Web umenia!</h1>
                </div>
            </a>
        </div>

        <div class="item">
            <a href="/clanok/pribehy-umenia-slavin" class="header-image text-center" style="background-image: url(/images/clanky/pribehy_slavin.jpg); text-shadow:0px 1px 0px #777; color: #fff">
                <div class="header-body">
                    <h2>príbehy umenia</h2>
                    <h1>Slavín</h1>
                </div>
            </a>
        </div>

        <div class="item">
            <a href="/katalog?work_type=kresba&amp;technique=koláž&amp;has_iip=1" class="header-image text-center" style="background-image: url(/images/kolekcie/kolaz.jpg); text-shadow:0px 1px 0px #777; color: #fff">
                <div class="header-body">
                    <h2>výtvarný druh: kresba<br>
                    +<br>
                    technika: koláž<br>
                    + <br>
                    len diela so zoom
                    </h2>
                </div>
            </a>
        </div>

      </div>
      {{-- <div class="pull-center">
        <a class="carousel-control left" href="#webumeniaCarousel" data-slide="prev">‹</a>
        <a class="carousel-control right" href="#webumeniaCarousel" data-slide="next">›</a>
      </div> --}}
    </div>

<section class="articles light-grey content-section">
    <div class="articles-body">
        <div class="container">
            <div class="row">
            	@foreach ($articles as $article)
	                <div class="col-sm-6 col-xs-12 bottom-space">
	                	<a href="{{ $article->getUrl() }}" class="featured-article">
	                		<img src="{{ $article->getThumbnailImage() }}" class="img-responsive" alt="{{ $article->title }}">
	                	</a>
                        <a href="{{ $article->getUrl() }}"><h4 class="title">
                            @if ($article->category)
                                {{ $article->category->name }}:
                            @endif
                            {{ $article->title }}
                        </h4></a>
                        <p class="attributes">{{ $article->getShortTextAttribute($article->summary, 250) }}
                        (<a href="{{ $article->getUrl() }}">viac</a>)
                        </p>
                        <p class="meta">{{$article->published_date}} / {{$article->author}}</p>
	                    
	                </div>	
            	@endforeach
            </div>
        </div>
    </div>
</section>


@stop

@section('javascript')

<script type="text/javascript">

</script>
@stop
