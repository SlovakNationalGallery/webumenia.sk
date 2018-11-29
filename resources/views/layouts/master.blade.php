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
    
    <link rel="stylesheet" type="text/css" href="css/vendor/bootstrap/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>

    {{-- JS --}}

    {{-- Google Analytics --}}
    @if (App::environment() == 'production')
    <script>
        {{-- GA tracking code --}}
    </script>
    @endif
</head>
<body class="">
    <div class="container-fluid">
        <div class="row">
            <div class="col-2">
                @include('components.khb_header')
            </div>
            <div class="col-10 py-0">
                @include('components.khb_nav_bar')
                <div class="row">
                    <div class="col">
                        @yield('content')
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        @include('components.footer')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Javascript -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    {!! Html::script('js/vendor/bootstrap/bootstrap.min.js') !!}
    @yield('javascript')
</body>
</html>
