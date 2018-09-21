<nav class="navbar {!! (Request::is('/') || isSet($transparent_menu)) ? '' : 'dark-text' !!}" role="navigation">
    <div class="container">
        <div class="navbar-header page-scroll">
            @include('components.langswitch', [
                'currentLocale' => App::getLocale(),
                'localesOrdered' => LaravelLocalization::getLocalesOrder(),
                'localizedURLs' => getLocalizedURLArray($removeQueryString = true),
            ])

            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                <i class="fa fa-bars fa-2x"></i>
            </button>
            <a class="navbar-brand no-border hidden-xs first-part" href="{!! URL::to('') !!}">
                web
            </a>

            @include('components.searchbar', [
                'search' => isSet($search) ? $search : '',
            ])

            <a class="navbar-brand no-border hidden-xs second-part" href="{!! URL::to('') !!}">
                umenia
            </a>
        </div>

        <div class="collapse navbar-collapse navbar-main-collapse">
            <ul class="nav navbar-nav">
                <li class="{!! (Request::is('katalog') || Request::is('dielo/*')) ? 'active' : '' !!}">
                        <a href="{{{ URL::to('katalog') }}}">{{ utrans('master.artworks') }}</a>
                </li>
                <li class="{!! (Request::is( 'kolekcie') || Request::is('kolekcia/*')) ? 'active' : '' !!}">
                        <a href="{{{ URL::to('kolekcie') }}}">{{ utrans('master.collections') }}</a>
                </li>
                <li class="{!! (Request::is('autori') || Request::is('autor/*')) ? 'active' : '' !!}">
                        <a href="{{{ URL::to('autori') }}}">{{ utrans('master.authors') }}</a>
                </li>
                <li class="{!! (Request::is('clanky') || Request::is('clanok/*')) ? 'active' : '' !!}">
                        <a href="{{{ URL::to('clanky') }}}">{{ utrans('master.articles') }}</a>
                </li>
                <li class="{!! Request::is( 'informacie') ? 'active' : '' !!}">
                        <a href="{{{ URL::to('informacie') }}}">{{ utrans('master.info') }}</a>
                </li>
                @if (Session::has('cart') && count(Session::get('cart'))>0)
                <li class="{!! Request::is( 'objednavka') ? 'active' : '' !!}">
                        <a href="{!! URL::to('objednavka')!!}" class=""><i class="fa fa-shopping-cart"></i><span class="badge badge-notify">{!! count(Session::get('cart')) !!}</span></a>
                </li>
                @endif
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>