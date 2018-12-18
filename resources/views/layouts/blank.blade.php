<!DOCTYPE html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @section('description')
    <meta name="description" content="{{ trans('master.meta-description') }}">
    @show
    <meta name="author" content="lab.SNG">

    <title>
        @section('title')
        {{ trans('master.meta-title') }}
        @show
    </title>

		<!-- CSS are placed here -->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
		{!! Html::style('css/sb-admin.css') !!}
</head>

<body>
		<!-- Content -->
		@yield('content')



		<!-- Core JavaScript Files -->
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
		{!! Html::script('js/vendor/bootstrap/bootstrap.min.js') !!}


	</body>
</html>
