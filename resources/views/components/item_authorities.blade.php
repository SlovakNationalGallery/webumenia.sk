@foreach($item->authorities as $authority)
    @if ($authority->pivot->role === 'autor/author')
        <span itemprop="creator" itemscope itemtype="http://schema.org/Person">
            <a class="underline" href="{{ $authority->getUrl() }}" itemprop="sameAs">
                <span itemprop="name">{{ $authority->formated_name }}</span><!--
            --></a><!--
        --></span>,
    @else
        <a class="underline" href="{{ $authority->getUrl() }}">{{ $authority->formated_name }}</a>
        &ndash; {{ $authority::formatMultiAttribute($authority->pivot->role) }},
    @endif
@endforeach

@foreach($authors as $author)
    <a class="underline" href="{{ url_to('katalog', ['author' => $author]) }}">{{ $author }}</a>,
@endforeach