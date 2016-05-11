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
		{!! HTML::style('css/styles.css') !!}
		{!! HTML::style('css/sb-admin.css') !!}
        {!! HTML::script('js/modernizr.custom.js') !!}

</head>

<body>
		<!-- Content -->
		@yield('content')



		<!-- Core JavaScript Files -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

		{!! HTML::script('js/bootstrap.min.js') !!}
		{!! HTML::script('js/bootstrap-datepicker.js') !!}

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
