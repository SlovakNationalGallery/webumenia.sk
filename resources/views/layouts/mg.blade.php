<!DOCTYPE html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @section('description')
    <meta name="description" content="Kompletní on-line zveřejnění sbírek Moravské galerie v Brně. Webová stránka umožňuje uživateli vyhledávat sbírkové předměty dle autora, techniky, výtvarného žánru, materiálu, námětu a také místa vzniku">
    @show
    <meta name="author" content="lab.SNG">

    <title>
      @section('title')
      Moravská galerie v Brně | sbirky
      @show
    </title>

    <!--  favicons-->
    <link rel="apple-touch-icon" sizes="180x180" href="{!! URL::to('/images/mg/apple-touch-icon.png') !!}">
    <link rel="icon" type="image/png" sizes="32x32" href="{!! URL::to('/images/mg/favicon-32x32.png') !!}">
    <link rel="icon" type="image/png" sizes="16x16" href="{!! URL::to('/images/mg/favicon-16x16.png') !!}">
    <link rel="manifest" href="{!! URL::to('/images/mg/manifest.json') !!}">
    <link rel="mask-icon" href="{!! URL::to('/images/mg/safari-pinned-tab.svg" color="#5bbad5') !!}">
    <link rel="shortcut icon" href="{!! URL::to('/images/mg/favicon.ico') !!}">
    <meta name="msapplication-config" content="{!! URL::to('/images/mg/browserconfig.xml') !!}">
    <meta name="theme-color" content="#ffffff">
    <!--  /favicons-->

    <!--  Open Graph protocol -->
    @section('og')
    <meta property="og:title" content="Moravská galerie v Brně" />
    <meta property="og:description" content="Kompletní on-line zveřejnění sbírek Moravské galerie v Brně. Webová stránka umožňuje uživateli vyhledávat sbírkové předměty dle autora, techniky, výtvarného žánru, materiálu, námětu a také místa vzniku" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{!! Request::url() !!}" />
    <meta property="og:image" content="{!! URL::to('/images/mg/og-image.png') !!}" />
    <meta property="og:site_name" content="Moravská galerie | sbírky on-line" />
    @show
    <!--  Open Graph protocol -->

    @yield('link')

    <!-- CSS are placed here -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="{!! asset_timed('css/mg.css') !!}" />
    {!! Html::style('css/slick-theme.css') !!}
    {!! Html::style('css/magnific-popup.css') !!}

    <script>
        document.createElement( "picture" );
    </script>
    {!! Html::script('js/picturefill.min.js') !!}

        {!! Html::script('js/modernizr.custom.js') !!}

    @if (App::environment() == 'production')
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-100423580-1', 'auto');
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

  <nav class="navbar {{-- navbar-fixed-top --}} {{-- navbar-static-top --}} {!! (Request::is('/') || isSet($transparent_menu)) ? '' : 'dark-text' !!}" role="navigation">
      <div class="container">
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                  <i class="fa fa-bars fa-2x"></i>
              </button>
              <a class="navbar-brand no-border hidden-xs first-part" href="{!! URL::to('') !!}">
                  <img src="/images/mg/logo.svg" alt="MORAVSKÁ GALERIE - SBÍRKA ON–LINE">
              </a>
              {!! Form::open(['url' => 'katalog', 'method' => 'get', 'class' => 'navbar-form right-inner-addon', 'data-searchd-engine' => Config::get('app.searchd_id_autocomplete')]) !!}
                    {!! Form::text('search', @$search, array('class' => 'form-control', 'placeholder' => 'hledej', 'id'=>'search', 'autocomplete'=>'off')) !!}
                    {!!  Form::submit('submit'); !!}
              {!!Form::close() !!}
              {{--
              @if (Request::is('dielo/*') && isSet($collection))
                 <a href="{!! $collection->getUrl() !!}" class="navbar-brand text-small hidden-xs hidden-sm">/&nbsp; {!! $collection->name !!}</a>
              @endif
               --}}
          </div>

          <div class="collapse navbar-collapse navbar-main-collapse">
              <ul class="nav navbar-nav">
                <li class="{!! (Request::is('katalog') || Request::is('dielo/*')) ? 'active' : '' !!}">
                    <a href="{{{ URL::to('katalog') }}}">{{ utrans('master.artworks') }}</a>
                </li>
                <li class="{!! Request::is( 'informacie') ? 'active' : '' !!}">
                    <a href="{{{ URL::to('informacie') }}}">{{ utrans('master.info') }}</a>
                </li>
              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container -->
  </nav>



  <!-- Content -->
  @yield('content')

  <div class="footer">
      <div class="container">
        <div class="row">
          <div class="col-xs-6"></div>
          <div class="col-xs-6">
            <p class="text-muted text-right">{{ utrans('master.made_by') }} <a href="http://lab.sng.sk" target="_blank" class="sans">lab.SNG</a></p>
          </div>
        </div>

      </div>
    </div>

  <div id="top">
      <a href="#page-top" title="{{ trans('master.to_top') }}" class="btn btn-default"  data-toggle="tooltip" data-placement="top">
          <i class="icon-arrow-up"></i>
      </a>
  </div>

  <!-- Core JavaScript Files -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
  <script src="https://unpkg.com/flickity@1.1/dist/flickity.pkgd.min.js"></script>
  {!! Html::script('js/imagesloaded.min.js') !!}
  {!! Html::script('js/jquery.infinitescroll.min.js') !!}
  {!! Html::script('js/jquery.isotope.min.js') !!}
  {!! Html::script('js/jquery.isotope.sloppy-masonry.min.js') !!}
  {!! Html::script('js/bootstrap.min.js') !!}
  {!! Html::script('js/typeahead.bundle.min.js') !!}
  {!! Html::script('js/mg.js') !!}

  <!-- Content -->
  @yield('javascript')

</body>
</html>
