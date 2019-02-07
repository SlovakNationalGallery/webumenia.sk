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

		{{-- JS --}}
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/4.1.2/plugins/unveilhooks/ls.unveilhooks.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/4.1.4/plugins/respimg/ls.respimg.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/4.1.2/lazysizes.min.js"></script>

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

	@include('components.nav_bar')

	<!-- Content -->
	@yield('content')

	@include('components.footer')

	<div id="top">
	    <a href="#page-top" title="{{ trans('master.to_top') }}" class="btn btn-default"  data-toggle="tooltip" data-placement="top">
	        <i class="icon-arrow-up"></i>
	    </a>
	</div>

	<!-- Core JavaScript Files -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
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
