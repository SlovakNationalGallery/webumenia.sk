{{-- comments kept for code indentation without whitespace --}}
@if (is_string($author))
    <a class="underline" href="{{ route('frontend.catalog.index', ['author' => $author]) }}">{{ formatName($author) }}</a>{{--
--}}@elseif ($author->pivot->role === 'autor/author')
    <span itemprop="creator" itemscope itemtype="http://schema.org/Person">
        <a class="underline" href="{{ $author->getUrl() }}" itemprop="sameAs">
            <span itemprop="name">{{ formatName($author->name) }}</span>{{--
        --}}</a>{{--
    --}}</span>{{--
--}}@else
    <a class="underline" href="{{ $author->getUrl() }}">{{ formatName($author->name) }}</a>
    &ndash; {{ $author::formatMultiAttribute($author->pivot->role) }}{{--
--}}@endif