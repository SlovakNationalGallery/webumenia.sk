<nav class="khb-nav-bar">
    <div class="row">
        <div class="col d-none d-sm-flex">
            <!-- Search not yet supported -->
        </div>
        <div class="col col-sm-2dot4 grid-cell-link {!! Request::is('o-projekte') ? 'active' : '' !!}">
            <a href="{{{ URL::to('o-projekte') }}}" class="">
            </a>
            <span>{{ utrans('master.about') }}</span>
        </div>
        <div class="col col-sm-2dot4 py-0 border-0 d-flex">
            @include('components.khb_language_switch', [
                'currentLocale' => App::getLocale(),
                'localesOrdered' => LaravelLocalization::getLocalesOrder(),
                'localizedURLs' => getLocalizedURLArray($removeQueryString = true),
            ])
        </div>
    </div>
    <div class="row">
        <div class="col grid-cell-link {!! (Request::is('umelci') || Request::is('umelec/*')) ? 'active' : '' !!}">
            <a href="{{{ URL::to('umelci') }}}" class="">
            </a>
            <span>{{ utrans('master.authors') }}</span>
        </div>
        <div class="col grid-cell-link {!! Request::is('skupiny') ? 'active' : '' !!}">
            <a href="{{{ URL::to('skupiny') }}}" class="">
            </a>
            <span>{{ utrans('master.groups') }}</span>
        </div>
        <div class="col grid-cell-link {!! (Request::is('teoretici') || Request::is('teoretik/*')) ? 'active' : '' !!}">
            <a href="{{{ URL::to('teoretici') }}}" class="">
            </a>
            <span>{{ utrans('master.theoreticians') }}</span>
        </div>
        <div class="col grid-cell-link {!! Request::is('vystavne-priestory') ? 'active' : '' !!}">
            <a href="{{{ URL::to('vystavne-priestory') }}}" class="">
            </a>
            <span>{{ utrans('master.spaces') }}</span>
        </div>
        <div class="col grid-cell-link {!! Request::is('klucove-slova') ? 'active' : '' !!}">
            <a href="{{{ URL::to('klucove-slova') }}}" class="">
            </a>
            <span>{{ utrans('master.keywords') }}</span>
        </div>
    </div>
</nav>