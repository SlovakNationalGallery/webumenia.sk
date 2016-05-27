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
		{!! Html::style('css/styles.css') !!}
		{!! Html::style('css/sb-admin.css') !!}
        {!! Html::script('js/modernizr.custom.js') !!}

</head>

<body>
		<!-- Content -->
		@yield('content')



		<!-- Core JavaScript Files -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

		{!! Html::script('js/bootstrap.min.js') !!}
		{!! Html::script('js/bootstrap-datepicker.js') !!}

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
