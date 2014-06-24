<!DOCTYPE html>
<html lang="sk">

<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Online výstava Dve krajiny - obraz Slovenska - 19. storočie x súčastnosť">
		<meta name="author" content="SNG, Igor Rjabinin">

		<title>
			@section('title')
			Dve krajiny - obraz Slovenska  - 19. storočie X súčastnosť
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

	<nav class="navbar navbar-custom navbar-fixed-top {{ Request::is('/') ? '' : 'dark-text' }}" role="navigation">
	    <div class="container">
	        <div class="navbar-header page-scroll">
	            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
	                <i class="fa fa-bars"></i>
	            </button>
	            <a class="navbar-brand" href="{{ URL::to('') }}">
	                <img src="/images/logo_sng.svg" alt="SNG">
	            </a>
	        </div>

	        <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
	            <ul class="nav navbar-nav">
						<li class="{{ Request::is('') ? 'active' : '' }}">
								<a href="{{{ URL::to('') }}}">Úvod</a>
						</li>
						<li class="{{ Request::is( 'catalog') ? 'active' : '' }}">
								<a href="{{{ URL::to('catalog') }}}">Vystavené diela</a>
						</li>
						<li class="{{ Request::is( 'info') ? 'active' : '' }}">
								<a href="{{{ URL::to('info') }}}">Informácie</a>
						</li>
	            </ul>
	        </div>
	        <!-- /.navbar-collapse -->
	    </div>
	    <!-- /.container -->
	</nav>



	<!-- Content -->
	@yield('content')


	<!-- Core JavaScript Files -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
	<!-- Google Maps API Key - You will need to use your own API key to use the map feature -->
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRngKslUGJTlibkQ3FkfTxj3Xss1UlZDA&sensor=false"></script>

	{{ HTML::script('js/jquery-ui-1.10.3.custom.min.js') }}
	{{ HTML::script('js/jquery.mousewheel.min.js') }}
	{{ HTML::script('js/jquery.kinetic.min.js') }}
	{{ HTML::script('js/jquery.smoothdivscroll-1.3-min.js') }}
	{{ HTML::script('js/jquery.magnific-popup.min.js') }}

	{{ HTML::script('js/bootstrap.min.js') }}
	{{ HTML::script('js/dvekrajiny.js') }}

</body>
</html>
