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

		@include('includes.favicons')
		@include('includes.og_tags')

		@foreach(LaravelLocalization::getSupportedLanguagesKeys() as $locale)
		<link rel="alternate" hreflang="{{ $locale }}" href="{{ LaravelLocalization::getLocalizedURL($locale, url()->current(), [], true) }}">
		@endforeach
		{{-- "default" url with locale hidden --}}
		<link rel="alternate" hreflang="{{ LaravelLocalization::getDefaultLocale() }}" href="{{ LaravelLocalization::getNonLocalizedURL(url()->current()) }}">


		@yield('link')

		<!-- CSS are placed here -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
		<link rel="stylesheet" type="text/css" href="{{ mix('/css/style.css') }}" />
		{!! Html::style('css/magnific-popup.css') !!}
		@livewireStyles

		{{-- JS --}}
		@include('googletagmanager::head')

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

		@yield('head-javascript')
</head>

<body id="page-top">

@include('googletagmanager::body')

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

@if (session('scena_ai_key') ?? config('services.scena_ai.key'))
	<scena-ai-popup key-id="{{ session('scena_ai_key') ?? config('services.scena_ai.key') }}" id="scena-ai"></scena-ai-popup>
	<script src="https://widget.scena.ai/app.js"></script>
@endif

	<!-- Content -->
	<div id="app">
		@sectionMissing('main-navigation')
			@include('components.nav_bar')
		@endif

		@yield('content')
	</div>

	@include('components.footer')

	<div id="top">
	    <a href="#page-top" title="{{ trans('master.to_top') }}" class="btn btn-default"  data-toggle="tooltip" data-placement="top">
	        <i class="icon-arrow-up"></i>
	    </a>
	</div>

	@livewireScripts
	<script type="text/javascript" src="{{ mix('/js/manifest.js') }}"></script>
	<script type="text/javascript" src="{{ mix('/js/vendor.js') }}"></script>
	<script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>

	@yield('javascript')
</body>
</html>
