<nav class="navbar {!! (Request::is('/') || isSet($transparent_menu)) ? '' : 'dark-text' !!}" role="navigation">
    <div class="container">
        <div class="navbar-header page-scroll">
            @include('components.langswitch', [
                'currentLocale' => App::getLocale(),
                'localesOrdered' => LaravelLocalization::getLocalesOrder(),
                'localizedURLs' => getLocalizedURLArray(),
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
                <li class="visible-xs {{ Request::is('/') ? 'active' : '' }}">
                    <a href="/">Web umenia</a>
                </li>
                <li class="{{ Route::is('frontend.catalog.index', 'dielo') ? 'active' : '' }}">
                        <a href="{{ route('frontend.catalog.index') }}">{{ utrans('master.artworks') }}</a>
                </li>
                <li class="{{ Route::is('frontend.collection.*') ? 'active' : '' }}">
                        <a href="{{ route('frontend.collection.index') }}">{{ utrans('master.collections') }}</a>
                </li>
                <li class="{{ Route::is('frontend.author.*') ? 'active' : '' }}">
                        <a href="{{ route('frontend.author.index') }}">{{ utrans('master.authors') }}</a>
                </li>
                <li class="{{ Route::is('frontend.article.*') ? 'active' : '' }}">
                        <a href="{{ route('frontend.article.index') }}">{{ utrans('master.articles') }}</a>
                </li>
                <li class="{{ Route::is('frontend.educational-article.*') ? 'active' : '' }}">
                        <a href="{{ route('frontend.educational-article.index') }}">{{ utrans('master.education') }}</a>
                </li>
                <li class="{{ Route::is('frontend.reproduction.index') ? 'active' : '' }}">
                        <a href="{{ route('frontend.reproduction.index') }}">{{ utrans('master.reproductions') }}</a>
                </li>
                <li class="{{ Route::is('frontend.info') ? 'active' : '' }}">
                        <a href="{{ route('frontend.info') }}">{{ utrans('master.info') }}</a>
                </li>
                @if (Session::has('cart') && count(Session::get('cart')) > 0)
                <li class="{{ Route::is('frontend.reproduction.detail') ? 'active' : '' }}">
                        <a href="{{ route('frontend.reproduction.detail') }}" class=""><i class="fa fa-shopping-cart"></i><span class="badge badge-sup badge-notify">{!! count(Session::get('cart')) !!}</span></a>
                </li>
                @endif
                <user-collections-nav-link
                    :active="{{ json_encode(Route::is('frontend.user-collection.show')) }}"
                    base-href="{{ route('frontend.user-collection.show') }}"
                    label="{{ utrans('master.favourites') }}"
                ></user-collections-nav-link>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>
