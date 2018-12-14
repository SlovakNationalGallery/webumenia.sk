<nav>
    <div class="row">
        <div class="col d-none d-sm-flex">
            <!-- Search not yet supported -->
        </div>
        <div class="col col-sm-2dot4">
            <a href="{{{ URL::to('o-projekte') }}}" class="{!! Request::is('o-projekte') ? 'active' : '' !!}">
                {{ utrans('master.about') }}
            </a>
        </div>
        <div class="col col-sm-2dot4 py-0 border-0 d-flex">
            @include('components.khb_language_switch')
        </div>
    </div>
    <div class="row">
        <div class="col">
            <a href="{{{ URL::to('autori') }}}" class="{!! (Request::is('autori') || Request::is('autor/*')) ? 'active' : '' !!}">
                {{ utrans('master.authors') }}
            </a>
        </div>
        <div class="col">
            <a href="{{{ URL::to('skupiny') }}}" class="{!! Request::is('skupiny') ? 'active' : '' !!}">
                {{ utrans('master.groups') }}
            </a>
        </div>
        <div class="col">
            <a href="{{{ URL::to('teoretici') }}}" class="{!! Request::is('teoretici') ? 'active' : '' !!}">
                {{ utrans('master.theoreticians') }}
            </a>
        </div>
        <div class="col">
            <a href="{{{ URL::to('vystavne-priestory') }}}" class="{!! Request::is('vystavne-priestory') ? 'active' : '' !!}">
                {{ utrans('master.spaces') }}
            </a>
        </div>
        <div class="col">
            <a href="{{{ URL::to('klucove-slova') }}}" class="{!! Request::is('klucove-slova') ? 'active' : '' !!}">
                {{ utrans('master.keywords') }}
            </a>
        </div>
    </div>
</nav>