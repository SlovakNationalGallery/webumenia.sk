<!DOCTYPE html>
<html lang="sk">

<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		@section('description')
		<meta name="description" content="Výstava Slovenskej národnej galérie s názvom DVE KRAJINY (Obraz Slovenska: 19. storočie x súčasnosť) sa venuje rozvoju stvárňovania slovenskej krajiny od počiatku tejto disciplíny v 19. storočí až na prah moderny. Webstránka dvekrajiny.sng.sk sprístupňuje vybrané diela z výstavy online vo vysokom rozlíšení.">
		@show
		<meta name="author" content="lab.SNG">

		<title>
			@section('title')
			Dve krajiny 
			@show
		</title>

		<!--  favicons-->
		<link rel="apple-touch-icon-precomposed" sizes="57x57" href="/apple-touch-icon-57x57.png" />
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/apple-touch-icon-114x114.png" />
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/apple-touch-icon-72x72.png" />
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/apple-touch-icon-144x144.png" />
		<link rel="apple-touch-icon-precomposed" sizes="60x60" href="/apple-touch-icon-60x60.png" />
		<link rel="apple-touch-icon-precomposed" sizes="120x120" href="/apple-touch-icon-120x120.png" />
		<link rel="apple-touch-icon-precomposed" sizes="76x76" href="/apple-touch-icon-76x76.png" />
		<link rel="apple-touch-icon-precomposed" sizes="152x152" href="/apple-touch-icon-152x152.png" />
		<link rel="icon" type="image/png" href="/favicon-196x196.png" sizes="196x196" />
		<link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
		<link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32" />
		<link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16" />
		<link rel="icon" type="image/png" href="/favicon-128.png" sizes="128x128" />
		<meta name="application-name" content="&nbsp;"/>
		<meta name="msapplication-TileColor" content="#FFFFFF" />
		<meta name="msapplication-TileImage" content="/mstile-144x144.png" />
		<meta name="msapplication-square70x70logo" content="/mstile-70x70.png" />
		<meta name="msapplication-square150x150logo" content="/mstile-150x150.png" />
		<meta name="msapplication-wide310x150logo" content="/mstile-310x150.png" />
		<meta name="msapplication-square310x310logo" content="/mstile-310x310.png" />
		<!--  /favicons-->

		<!--  Open Graph protocol -->
		@section('og')
		<meta property="og:title" content="DVE KRAJINY. Obraz Slovenska: 19. storočie × súčasnosť" />
		<meta property="og:description" content="Výstava Slovenskej národnej galérie s názvom DVE KRAJINY (Obraz Slovenska: 19. storočie x súčasnosť) v Esterházyho paláci predstavuje multižánrový výber diel zo zbierok 35 galérií, múzeí, inštitúcií a súkromých majiteľov. Venuje sa rozvoju stvárňovania slovenskej krajiny od počiatku tejto disciplíny v 19. storočí až na prah moderny. Webstránka dvekrajiny.sng.sk sprístupňuje vybrané diela z výstavy online vo vysokom rozlíšení." />
		<meta property="og:type" content="website" />
		<meta property="og:url" content="{{ Request::url() }}" />
		<meta property="og:image" content="{{ URL::to('/images/vizual-og.jpg') }}" />
		<meta property="og:site_name" content="DVE KRAJINY" />
		@show
		<!--  Open Graph protocol -->

		<!-- CSS are placed here -->
		<link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		{{ HTML::style('css/styles.css') }}
		{{ HTML::style('css/magnific-popup.css') }}
        {{ HTML::script('js/modernizr.custom.js') }}

		<script type="text/javascript">
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-19030232-4', 'sng.sk');
			ga('send', 'pageview');	 
		</script>


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

	<nav class="navbar navbar-custom navbar-fixed-top {{ (Request::is('/') || isSet($transparent_menu)) ? '' : 'dark-text' }}" role="navigation">
	    <div class="container">
	        <div class="navbar-header page-scroll">
	            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
	                <i class="fa fa-bars"></i>
	            </button>
	            <a class="navbar-brand" href="{{ URL::to('') }}">
	                <i class="icon-sng"></i>
	            </a>
	            @if (Request::is('dielo/*') && isSet($collection))
	            	 <a href="{{ $collection->getUrl() }}" class="navbar-brand text-small hidden-xs hidden-sm">/&nbsp; {{ $collection->name }}</a>
	            @endif
	        </div>

	        <div class="collapse navbar-collapse navbar-right navbar-main-collapse">


	            <ul class="nav navbar-nav">
						<li class="{{ Request::is( 'sekcie') ? 'active' : '' }}">
								<a href="{{{ URL::to('sekcie') }}}" class="dropdown-toggle" data-toggle="dropdown">Sekcie <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									@foreach (Collection::orderBy('order', 'ASC')->with('items')->get() as $i => $collection)
										<li><a href="{{ URL::to('sekcia/' . $collection->id) }}">{{ $collection->name }}</a></li>
										<li class="separator"></li>
									@endforeach
						        </ul>
						</li>
						<li class="{{ Request::is('katalog') ? 'active' : '' }}">
								<a href="{{{ URL::to('katalog') }}}">Diela</a>
						</li>
						<li class="{{ Request::is( 'informacie') ? 'active' : '' }}">
								<a href="{{{ URL::to('informacie') }}}">Informácie</a>
						</li>
						@if (Session::has('cart') && count(Session::get('cart'))>0)
						<li class="{{ Request::is( 'informacie') ? 'active' : '' }}">
								<a href="{{ URL::to('objednavka')}}" class=""><i class="fa fa-shopping-cart"></i><span class="badge badge-notify">{{ count(Session::get('cart')) }}</span></a>
						</li>
						@endif
						<!-- <li class="{{ Request::is( 'informacie') ? 'active' : '' }}">
								<a href="{{{ URL::to('informacie') }}}"><i class="fa fa-search"></i></a>
						</li> -->
	            </ul>

				{{ Form::open(['url' => 'katalog', 'method' => 'get', 'class' => 'navbar-form navbar-right right-inner-addon']) }}
							<i class="fa fa-search"></i>
							{{ Form::text('search', @$search, array('class' => 'form-control', 'placeholder' => 'Hľadať...', 'id'=>'search')) }}
				{{Form::close() }}
                
	        </div>
	        <!-- /.navbar-collapse -->
	    </div>
	    <!-- /.container -->
	</nav>



	<!-- Content -->
	@yield('content')

	<div class="footer">
      <div class="container">
        <p class="text-muted text-center">Vyrobil a spravuje <a href="http://lab.sng.sk" target="_blank" class="sans">lab.SNG</a></p>
      </div>
    </div>

	<div id="top">
	    <a href="#page-top" title="návrat hore" class="btn btn-default"  data-toggle="tooltip" data-placement="top">
	        <i class="fa fa-angle-up"></i>
	    </a>
	</div>

	<!-- Core JavaScript Files -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
	<!-- Google Maps API Key - You will need to use your own API key to use the map feature -->
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRngKslUGJTlibkQ3FkfTxj3Xss1UlZDA&sensor=false"></script>

	{{ HTML::script('js/gmaps.js') }}
	{{ HTML::script('js/imagesloaded.min.js') }}
	{{ HTML::script('js/jquery.infinitescroll.min.js') }}
	{{ HTML::script('js/jquery.isotope.min.js') }}
	{{ HTML::script('js/jquery.isotope.sloppy-masonry.min.js') }}

	{{ HTML::script('js/bootstrap.min.js') }}
	{{ HTML::script('js/dvekrajiny.js') }}

	<script>
	  function initializeSearchD() {
	    Searchd.monitor("#search", "{{ Config::get('app.searchd_id') }}", {queryPlaceholder: 'Hľadať...'});
	  }
	</script>
	<script async src="http://d3nr6w5i9vqvic.cloudfront.net/assets/collector.js" onload="initializeSearchD();"></script>


	<!-- Content -->
	@yield('javascript')

</body>
</html>
