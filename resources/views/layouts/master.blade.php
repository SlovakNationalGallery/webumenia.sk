<!DOCTYPE html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="lab.SNG">
    @section('description')
    <meta name="description" content="{{ trans('master.meta-description') }}">
    @show

    <title>
        @section('title')
        {{ trans('master.meta-title') }}
        @show
    </title>

    @include('includes.favicons')

    <!--  Open Graph protocol -->
    @include('includes.khb.og_tags')

    @include('includes.hreflangs', [
      'localizedURLs' => getLocalizedURLArray(),
    ])

    @yield('link')

    <!-- CSS -->
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" />

    <!-- JS -->
    @include('includes.analytics')
    
</head>
<body class="p-0 bg-white text-black">
    @if(Session::has('status'))
        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('status') }}</p>
    @endif

    <div class="container-fluid mw-1920">
        <div class="row">
            <div class="col-sm-2">
                @include('components.khb_header')
            </div>
            <div class="col-sm-10 py-0 border-0">
                @include('components.khb_nav_bar')
                @yield('content')
                @include('components.khb_footer')
            </div>
        </div>
    </div>

    <!-- Javascript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/4.1.1/lazysizes.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/4.1.1/plugins/unveilhooks/ls.unveilhooks.min.js"></scri --}}
    <script type="text/javascript" src="{{ mix('js/manifest.js') }}"></script>
    <script type="text/javascript" src="{{ mix('/js/vendor.js') }}"></script>
    <script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>
    @yield('javascript')
</body>
</html>
