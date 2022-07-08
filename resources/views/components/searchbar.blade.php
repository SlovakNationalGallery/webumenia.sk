{!! Form::open([
    'url' => 'katalog',
    'method' => 'get',
    'class' => 'navbar-form right-inner-addon ukraine',
]) !!}
<i class="fa fa-search"></i>
{!! Form::text('search', request()->get('search'), [
    'class' => 'form-control',
    'placeholder' => utrans('master.search_placeholder'),
    'id' => 'search',
    'autocomplete' => 'off',
    'data-artists-title' => utrans('authority.authors'),
    'data-artworks-title' => utrans('katalog.title'),
    'data-articles-title' => utrans('articles.title'),
    'data-collections-title' => utrans('kolekcie.title'),
]) !!}
{!! Form::submit('submit') !!}
{!! Form::close() !!}
