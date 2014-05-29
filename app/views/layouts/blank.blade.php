<!DOCTYPE html>
<html lang="sk">

<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="SNG, Igor Rjabinin">

		<title>
			@section('title')
			WEBUMENIA ADMIN
			@show
		</title>


		<!-- CSS are placed here -->
		<link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		{{ HTML::style('css/styles.css') }}
		{{ HTML::style('css/sb-admin.css') }}
        {{ HTML::script('js/modernizr.custom.js') }}

</head>

<body>
	@if (Auth::check())
		<nav class="navbar navbar-custom navbar-fixed-top top-nav-collapse" role="navigation">
				<div class="container">
						<div class="navbar-header page-scroll">
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
										<i class="fa fa-bars"></i>
								</button>
								<a class="logo" href="{{{ URL::to('') }}}"><img src="/img/bavlna_logo.svg" alt="BAVLNA" /></a>
						</div>

						<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="collapse navbar-collapse navbar-main-collapse">
								<ul class="nav navbar-nav">
										<li class="{{ Request::is( 'posts') ? 'active' : '' }}">
												<a href="{{{ URL::to('posts') }}}">Novinky</a>
										</li>
										<li class="">
												<a href="{{{ URL::to('/') }}}"></a>
										</li>
										<li class="">
												<a href="{{{ URL::to('logout') }}}">Odhlásiť sa</a>
										</li>
								</ul>
						</div>
						<!-- /.navbar-collapse -->
				</div>
				<!-- /.container -->
		</nav>
	@endif


		<!-- Content -->
		@yield('content')



		<!-- Core JavaScript Files -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

		{{ HTML::script('js/bootstrap.min.js') }}
		{{ HTML::script('js/bootstrap-datepicker.js') }}

		<script>
		$(document).ready(function() {
	        $('.datepicker').datepicker({
			    format: "yyyy-mm-dd",
			    language: "sk"
			});
		});
		</script>


	</body>
</html>
