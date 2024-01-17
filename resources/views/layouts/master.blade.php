<!DOCTYPE html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}">

<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		@section('description')
		<meta name="description" content="{{ trans('master.meta_description') }}">
		@show
		<meta name="author" content="lab.SNG">

		<title>
			@section('title')
			Web umenia
			@show
		</title>

		<!--  favicons-->
		@include('includes.favicons')
		<!--  /favicons-->
		<!--  Open Graph protocol -->
		@include('includes.og_tags')
		<!--  Open Graph protocol -->

		@yield('link')

		<!-- CSS are placed here -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="{!! asset_timed('css/style.css') !!}" />
		{!! Html::style('css/slick-theme.css') !!}
		{!! Html::style('css/magnific-popup.css') !!}

		<script>
		    document.createElement( "picture" );
		</script>
		{!! Html::script('js/picturefill.min.js') !!}

        {!! Html::script('js/modernizr.custom.js') !!}

		@if (App::environment() == 'production')
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-19030232-8', 'auto');
		  ga('send', 'pageview');

		</script>
		@endif
		{!! Html::script('js/scroll-frame-head.js') !!}
</head>

<body id="page-top">
<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=1429726730641216&version=v2.0";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>

	@if (App::environment() != 'production')
		<div id="alert-non-production" class="alert alert-warning text-center" role="alert">
		  Pozor! Toto nieje ostrý web. Prostredie: <strong>{!! App::environment() !!}</strong>
		</div>
	@endif

    @if (true)
        <div class="alert alert-danger text-center" role="alert">
          Omlouváme se, ale z technických příčin <strong>není dočasně dostupná funkce zoom</strong>. Na opravě pracujeme, děkujeme za trpělivost!
        </div>
    @endif

	<nav class="navbar {{-- navbar-fixed-top --}} {{-- navbar-static-top --}} {!! (Request::is('/') || isSet($transparent_menu)) ? '' : 'dark-text' !!}" role="navigation">
	    <div class="container">
	        <div class="navbar-header page-scroll">

              @include('components.langswitch', [
                'currentLocale' => App::getLocale(),
                'localesOrdered' => LaravelLocalization::getLocalesOrder(),
                'localizedURLs' => getLocalizedURLArray(),
              ])

              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                  <i class="fa fa-bars fa-2x"></i>
              </button>
	            <a class="navbar-brand no-border hidden-xs first-part" href="{!! URL::to('') !!}">
	                web
	            </a>

	            @include('components.searchbar', [
	              'search' => isSet($search) ? $search : '',
	            ])

	            <a class="navbar-brand no-border hidden-xs second-part" href="{!! URL::to('') !!}">
	                umenia
	            </a>
	            {{--
	            @if (Request::is('dilo/*') && isSet($collection))
	            	 <a href="{!! $collection->getUrl() !!}" class="navbar-brand text-small hidden-xs hidden-sm">/&nbsp; {!! $collection->name !!}</a>
	            @endif
	             --}}
	        </div>

	        <div class="collapse navbar-collapse navbar-main-collapse">
	            <ul class="nav navbar-nav">
						<li class="{!! (Request::is('katalog') || Request::is('dilo/*')) ? 'active' : '' !!}">
								<a href="{{{ URL::to('katalog') }}}">{{ utrans('master.artworks') }}</a>
						</li>
						<li class="{!! (Request::is( 'kolekcie') || Request::is('kolekcia/*')) ? 'active' : '' !!}">
								<a href="{{{ URL::to('kolekcie') }}}">{{ utrans('master.collections') }}</a>
						</li>
						<li class="{!! (Request::is('autori') || Request::is('autor/*')) ? 'active' : '' !!}">
								<a href="{{{ URL::to('autori') }}}">{{ utrans('master.authors') }}</a>
						</li>
						<li class="{!! (Request::is('clanky') || Request::is('clanok/*')) ? 'active' : '' !!}">
								<a href="{{{ URL::to('clanky') }}}">{{ utrans('master.articles') }}</a>
						</li>
						{{-- <li class="{!! Request::is('galerie') ? 'active' : '' !!}">
								<a href="{{{ URL::to('galerie') }}}">{{ utrans('master.galleries') }}</a>
						</li> --}}
						<li class="{!! Request::is( 'informacie') ? 'active' : '' !!}">
								<a href="{{{ URL::to('informacie') }}}">{{ utrans('master.info') }}</a>
						</li>
						@if (Session::has('cart') && count(Session::get('cart'))>0)
						<li class="{!! Request::is( 'objednavka') ? 'active' : '' !!}">
								<a href="{!! URL::to('objednavka')!!}" class=""><i class="fa fa-shopping-cart"></i><span class="badge badge-notify">{!! count(Session::get('cart')) !!}</span></a>
						</li>
						@endif
	            </ul>
	        </div>
	        <!-- /.navbar-collapse -->
	    </div>
	    <!-- /.container -->
	</nav>



	<!-- Content -->
	@yield('content')

	<div class="footer">
      <div class="container">
      	<div class="row">
      		<div class="col-xs-6">
      			<a href="https://www.facebook.com/webumenia.sk" target="_blank" data-toggle="tooltip" title="facebook"><i class="fa fa-facebook fa-lg"></i></a>
      			<a href="http://webumenia.tumblr.com/" target="_blank" data-toggle="tooltip" title="tumblr"><i class="fa fa-tumblr fa-lg"></i></a>
      			<a href="https://vimeo.com/webumeniask" target="_blank" data-toggle="tooltip" title="vimeo"><i class="fa fa-vimeo-square fa-lg"></i></a>
      			<a href="https://sk.pinterest.com/webumeniask/" target="_blank" data-toggle="tooltip" title="pinterest"><i class="fa fa-pinterest fa-lg"></i></a>
      			<a href="https://twitter.com/webumeniask" target="_blank" data-toggle="tooltip" title="twitter"><i class="fa fa-twitter fa-lg"></i></a>
      			<a href="https://instagram.com/web_umenia/" target="_blank" data-toggle="tooltip" title="instagram"><i class="fa fa-instagram fa-lg"></i></a>
      			<a href="https://github.com/SlovakNationalGallery" target="_blank" data-toggle="tooltip" title="github"><i class="fa fa-github fa-lg"></i></a>
      		</div>
      		<div class="col-xs-6">
      			<p class="text-muted text-right">{{ utrans('master.made_by') }} <a href="http://lab.sng.sk" target="_blank" class="sans">lab.SNG</a></p>
      		</div>
      	</div>

      </div>
    </div>

	<div id="top">
	    <a href="#page-top" title="{{ trans('master.to_top') }}" class="btn btn-default"  data-toggle="tooltip" data-placement="top">
	        <i class="icon-arrow-up"></i>
	    </a>
	</div>

	<!-- Core JavaScript Files -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/4.0.1/lazysizes.min.js" async=""></script>
    <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>
	<script src="https://unpkg.com/flickity@1.1/dist/flickity.pkgd.min.js"></script>
	{!! Html::script('js/jquery.infinitescroll.min.js') !!}
    {!! Html::script('js/bootstrap.min.js') !!}
    @include('components.searchbar_js')
    <script src="{!! asset_timed('js/webumenia.js') !!}"></script>


	<!-- Content -->
	@yield('javascript')

</body>
</html>
