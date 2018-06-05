{!! Form::open([
    'url' => 'katalog',
    'method' => 'get',
    'class' => 'navbar-form right-inner-addon',
    'data-searchd-engine' => Config::get('app.searchd_id_autocomplete', ''),
]) !!}
    <i class="fa fa-search"></i>
    {!! Form::text('search', @$search, [
        'class' => 'form-control',
        'placeholder' => utrans('master.search_placeholder'),
        'id'=>'search',
        'autocomplete'=>'off',
        'data-artists'=> utrans('autori.title'),
        'data-artworks'=> utrans('katalog.title'),
        'data-articles'=> utrans('clanky.title'),
        'data-collections'=> utrans('kolekcie.title'),
    ]) !!}
    {!!  Form::submit('submit'); !!}
{!! Form::close() !!}