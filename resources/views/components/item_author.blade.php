{{-- comments kept for code indentation without whitespace --}}
@empty ($author->authority)
    <a class="underline" href="{{ route('frontend.catalog.index', ['author' => $author->name]) }}">{{ formatName($author->name) }}</a>{{--
--}}@elseif ($author->authority->pivot->role === 'autor/author')
    <span itemprop="creator" itemscope itemtype="http://schema.org/Person">
        <a class="underline" href="{{ $author->authority->getUrl() }}" itemprop="sameAs">
            <span itemprop="name">{{ formatName($author->authority->name) }}</span>{{--
        --}}</a>{{--
    --}}</span>{{--
--}}@else
    <a class="underline" href="{{ $author->authority->getUrl() }}">{{ formatName($author->authority->name) }}</a>
    &ndash; {{ $author->authority::formatMultiAttribute($author->authority->pivot->role) }}{{--
--}}@endif