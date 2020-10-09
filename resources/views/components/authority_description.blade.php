<p class="lead">
    @if ($author->birth_date || $author->birth_place)
        *
    @endif

    @if ($author->birth_date)
        <span itemprop="{{ $author->type === 'corporate body' ? 'foundingDate' : 'birthDate' }}">{{ $author->birth_date }}</span>
    @endif

    @if ($author->birth_place)
        <a href="{{ route('frontend.author.index', ['place' => $author->birth_place]) }}" itemprop="{{ $author->type === 'corporate body' ? 'foundingPlace' : 'birthPlace' }}">{{ $author->birth_place }}</a>
    @endif

    @if (($author->birth_date || $author->birth_place) && ($author->death_date || $author->death_place))
        –
    @endif

    @if ($author->death_date || $author->death_place)
        ✝
    @endif

    @if ($author->death_date)
        <span itemprop="{{ $author->type === 'corporate body' ? 'dissolutionDate' : 'deathDate' }}">{{ $author->death_date }}</span>
    @endif

    @if ($author->death_place)
        <a href="{{ route('frontend.author.index', ['place' => $author->death_place]) }}"@if($author->type !== 'corporate body') itemprop="deathPlace"@endif>{{ $author->death_place }}</a>
    @endif
</p>
@if ($author->roles)
<p class="lead">
    <span class="hidden"> | {{ utrans('authority.roles') }}:</span>
    @foreach ($author->roles as $role)
        <a href="{{ route('frontend.author.index', ['role' => $role]) }}">
            <strong itemprop="{{ $author->type === 'corporate body' ? 'knowsAbout' : 'jobTitle' }}">{{ $role }}</strong>
        </a>{{ !$loop->last ? ', ' : '' }}
    @endforeach
</p>
@endif