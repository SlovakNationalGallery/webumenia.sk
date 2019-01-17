<!DOCTYPE html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}" class="h-100">

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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/solid.css" integrity="sha384-aj0h5DVQ8jfwc8DA7JiM+Dysv7z+qYrFYZR+Qd/TwnmpDI6UaB3GJRRTdY8jYGS4" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/fontawesome.css" integrity="sha384-WK8BzK0mpgOdhCxq86nInFqSWLzR5UAsNg0MGX9aDaIIrFWQ38dGdhwnNCAoXFxL" crossorigin="anonymous">
    <!-- JS -->
    @include('includes.analytics')

</head>
<body class="h-100 p-0 bg-white text-black">
    <div class="container-fluid h-100 mw-1920">
        <div class="row h-100">
            <div class="col-sm-2">
                @include('components.khb_header')
            </div>
            <div class="col-sm-10 d-flex flex-column py-0 border-0">
                @include('components.khb_nav_bar')

                @if (Session::has('status'))
                    <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show my-2" role="alert">
                        {!! Session::get('status') !!}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

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
