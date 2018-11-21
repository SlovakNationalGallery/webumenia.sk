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
        ARTBASE
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

    <!-- CSS -->
    
    <link rel="stylesheet" type="text/css" href="{!! asset_timed('css/style.css') !!}" />

    {{-- JS --}}

    {{-- Google Analytics --}}
    @if (App::environment() == 'production')
    <script>
        {{-- GA tracking code --}}
    </script>
    @endif
</head>

<body>
    <nav role="navigation">
    </nav>

    <!-- Content -->
    @yield('content')

    @include('components.footer')

    <!-- Javascript -->
    {!! Html::script('js/bootstrap.min.js') !!}
    @yield('javascript')
</body>
</html>
