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
        'data-artists-title'=> utrans('autori.title'),
        'data-artworks-title'=> utrans('katalog.title'),
        'data-articles-title'=> utrans('clanky.title'),
        'data-collections-title'=> utrans('kolekcie.title'),
    ]) !!}
    {!!  Form::submit('submit'); !!}
{!! Form::close() !!}