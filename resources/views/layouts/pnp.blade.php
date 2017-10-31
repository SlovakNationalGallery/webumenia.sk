<!DOCTYPE html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @section('description')
    <meta name="description" content="Virtuální výstava děl jednoho z nejvýznamnějších barokního rytců a kreslířů 17. století">
    @show
    <meta name="author" content="lab.SNG">

    <title>
      @section('title')
      Wenceslaus Hollar Bohemus – Řeč jehly a rydla
      @show
    </title>

    <!--  favicons-->
    <link rel="apple-touch-icon" sizes="180x180" href="/images/pnp/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/pnp/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/pnp/favicon-16x16.png">
    <link rel="manifest" href="/images/pnp/manifest.json">
    <link rel="mask-icon" href="/images/pnp/safari-pinned-tab.svg" color="#fef200">
    <link rel="shortcut icon" href="/images/pnp/favicon.ico">
    <meta name="msapplication-config" content="/images/pnp/browserconfig.xml">
    <meta name="theme-color" content="#fef200">
    <!--  /favicons-->

    <!--  Open Graph protocol -->
    @section('og')
    <meta property="og:title" content="Wenceslaus Hollar Bohemus – Řeč jehly a rydla" />
    <meta property="og:description" content="Virtuální výstava děl jednoho z nejvýznamnějších barokního rytců a kreslířů 17. století" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{!! Request::url() !!}" />
    <meta property="og:image" content="{!! URL::to('/images/pnp/og-image.jpg') !!}" />
    <meta property="og:site_name" content="Wenceslaus Hollar Bohemus – Řeč jehly a rydla" />
    @show
    <!--  Open Graph protocol -->

    @yield('link')

    <!-- CSS are placed here -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro&amp;subset=latin-ext" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{!! asset_timed('css/pnp.css') !!}" />
    {!! Html::style('css/slick-theme.css') !!}
    {!! Html::style('css/magnific-popup.css') !!}

    <script>
        document.createElement( "picture" );
    </script>
    {!! Html::script('js/picturefill.min.js') !!}

        {!! Html::script('js/modernizr.custom.js') !!}

    @if (App::environment() == 'production')
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-19030232-9"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-19030232-9');
    </script>
    @endif
    {!! Html::script('js/scroll-frame-head.js') !!}
</head>

<body id="page-top" class="{{ (isSet($show_bg) && $show_bg == true) ? 'show_bg' : '' }}">
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
              <div class="collapse navbar-collapse navbar-main-collapse">
                  <ul class="nav navbar-nav">
                    <li class="{!! (Request::is('/') || Request::is('uvod')) ? 'active' : '' !!}">
                        <a href="{{{ URL::to('uvod') }}}">{{ utrans('master.intro') }}</a>
                    </li>
                    <li class="{!! (Request::is('autor')) ? 'active' : '' !!}">
                        <a href="{{{ URL::to('autor') }}}">{{ utrans('master.author') }}</a>
                    </li>
                    <li class="{!! (Request::is('katalog') || Request::is('dielo/*')) ? 'active' : '' !!}">
                        <a href="{{{ URL::to('katalog') }}}">{{ utrans('master.artworks') }}</a>
                    </li>
                    <li class="{!! (Request::is( 'kolekcie') || Request::is('kolekcia/*')) ? 'active' : '' !!}">
                        <a href="{{{ URL::to('sections') }}}">{{ utrans('master.sections') }}</a>
                    </li>
                    <li class="{!! Request::is( 'vystava') ? 'active' : '' !!}">
                        <a href="{{{ URL::to('vystava') }}}">{{ utrans('master.exhibition') }}</a>
                    </li>
                  </ul>
                  {!! Form::open(['url' => 'katalog', 'method' => 'get', 'class' => 'navbar-form right-inner-addon', 'data-searchd-engine' => Config::get('app.searchd_id_autocomplete')]) !!}
                        {!! Form::text('search', @$search, array('class' => 'form-control', 'placeholder' => 'hledej', 'id'=>'search', 'autocomplete'=>'off')) !!}
                        {!!  Form::submit('submit'); !!}
                  {!!Form::close() !!}
              </div>
              <!-- /.navbar-collapse -->
              {{--
              @if (Request::is('dielo/*') && isSet($collection))
                 <a href="{!! $collection->getUrl() !!}" class="navbar-brand text-small hidden-xs hidden-sm">/&nbsp; {!! $collection->name !!}</a>
              @endif
               --}}
          </div>
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
            <p class="text-muted text-right">&copy; <a href="http://www.pamatniknarodnihopisemnictvi.cz/" target="_blank" class="sans">Památník národního písemnictví</a>,  <a href="http://lab.sng.sk" target="_blank" class="sans">lab.SNG</a></p>
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

  @if (App::environment('production'))
    <script>
      function initializeSearchD() {
              Searchd.monitorSearch("#search", "{!! Config::get('app.searchd_id') !!}", {queryPlaceholder: 'Hľadať diela, autorov...'});
              Searchd.monitorAutocomplete("#search", "{!! Config::get('app.searchd_id_autocomplete') !!}");
          }
    </script>
    <script async src="https://cdn.searchd.co/assets/collector.js" onload="initializeSearchD();"></script>
  @endif

  <!-- Content -->
  @yield('javascript')

</body>
</html>
