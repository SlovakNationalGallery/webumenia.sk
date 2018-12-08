<nav>
    <div class="row">
        <div class="col">
            Search
        </div>
        <div class="col-2dot4">
            O Projekte
        </div>
        <div class="col-2dot4 py-0">
            @include('components.khb_language_switch')
        </div>
    </div>
    <div class="row">
        <div class="col">
            <a href="{{{ URL::to('autori') }}}" class="{!! (Request::is('autori') || Request::is('autor/*')) ? 'active' : '' !!}">{{ utrans('master.authors') }}</a>
        </div>
        <div class="col">Skupiny</div>
        <div class="col">Teoretici</div>
        <div class="col">Vystavne priestory</div>
        <div class="col">Klucove slova</div>
    </div>
</nav>