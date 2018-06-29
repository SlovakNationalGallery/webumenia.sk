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
			Národní galerie v Praze - sbírky
			@show
		</title>

		<!--  favicons-->
		@include('includes.favicons')
		<!--  /favicons-->

		<!--  Open Graph protocol -->
	    @include('includes.og_tags')
	    <!--  /Open Graph protocol -->

	    <!--  hreflangs -->
		@include('includes.hreflangs', [
	      'localizedURLs' => getLocalizedURLArray(),
	    ])
		<!--  /hreflangs -->

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
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-111403294-1"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          gtag('config', 'UA-111403294-1');
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
  js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.11&appId=2085312731692823';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

	@if (App::environment() != 'production')
		<div id="alert-non-production" class="alert alert-warning text-center" role="alert">
		  Pozor! Toto nieje ostrý web. Prostredie: <strong>{!! App::environment() !!}</strong>
		</div>
	@endif

	<nav class="navbar {{-- navbar-fixed-top --}} {{-- navbar-static-top --}} {!! (Request::is('/') || isSet($transparent_menu)) ? '' : 'dark-text' !!}" role="navigation">
	    <div class="container">
	        <div class="navbar-header page-scroll">
                @include('components.langswitch', [
                  'currentLocale' => App::getLocale(),
                  'localizedURLs' => getLocalizedURLArray(),
                ])

                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                    <i class="fa fa-bars fa-2x"></i>
                </button>
	            <a class="navbar-brand no-border hidden-xs" href="http://www.ngprague.cz/">
	                {{-- národní galerie --}}
                    <img src="{{ asset('images/logo.svg') }}" class="brand-image">
	            </a>

	            @include('components.searchbar', [
	              'search' => isSet($search) ? $search : '',
	            ])

	        </div>

	        <div class="collapse navbar-collapse navbar-main-collapse">
	            <ul class="nav navbar-nav">
                        <li class="{!! (Request::is('/')) ? 'active' : '' !!}">
                                <a href="{{{ URL::to('/') }}}">{{ utrans('master.intro') }}</a>
                        </li>
						<li class="{!! (Request::is('katalog') || Request::is('dielo/*')) ? 'active' : '' !!}">
								<a href="{{{ URL::to('katalog') }}}">{{ utrans('master.artworks') }}</a>
						</li>
						<li class="{!! (Request::is( 'kolekcie') || Request::is('kolekcia/*')) ? 'active' : '' !!}">
								<a href="{{{ URL::to('kolekcie') }}}">{{ utrans('master.collections') }}</a>
						</li>
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
      			<a href="https://www.facebook.com/NGvPraze" target="_blank" data-toggle="tooltip" title="facebook"><i class="fa fa-facebook fa-lg"></i></a>
      			<a href="https://twitter.com/narodnigalerie" target="_blank" data-toggle="tooltip" title="twitter"><i class="fa fa-twitter fa-lg"></i></a>
                <a href="https://www.instagram.com/ngprague/" target="_blank" data-toggle="tooltip" title="instagram"><i class="fa fa-instagram fa-lg"></i></a>
      			<a href="https://www.youtube.com/channel/UCeYULpYNcpUJxSeK2FpDpsg" target="_blank" data-toggle="tooltip" title="youtube"><i class="fa fa-youtube fa-lg"></i></a>
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
