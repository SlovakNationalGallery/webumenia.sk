<p class="lead">
    @if ($author->birth_date || $author->birth_place)
        *
    @endif

    @if ($author->birth_date)
        <span
            itemprop="{{ $author->isCorporateBody() ? 'foundingDate' : 'birthDate' }}">{{ $author->birth_date }}</span>
    @endif

    @if ($author->birth_place)
        <a href="{{ route('frontend.author.index', ['place' => $author->birth_place]) }}"
            itemprop="{{ $author->isCorporateBody() ? 'foundingPlace' : 'birthPlace' }}">{{ $author->birth_place }}</a>
    @endif

    @if (($author->birth_date || $author->birth_place) && ($author->death_date || $author->death_place))
        –
    @endif

    @if ($author->death_date || $author->death_place)
        ✝
    @endif

    @if ($author->death_date)
        <span
            itemprop="{{ $author->isCorporateBody() ? 'dissolutionDate' : 'deathDate' }}">{{ $author->death_date }}</span>
    @endif

    @if ($author->death_place)
        <a href="{{ route('frontend.author.index', ['place' => $author->death_place]) }}"
            @if (!$author->isCorporateBody()) itemprop="deathPlace" @endif>{{ $author->death_place }}</a>
    @endif
</p>
@if ($author->roles)
    <p class="lead">
        <span class="hidden"> | {{ utrans('authority.roles') }}:</span>
        @foreach ($author->roles as $role)
            <a href="{{ route('frontend.author.index', ['role' => $role]) }}">
                <strong
                    itemprop="{{ $author->isCorporateBody() ? 'knowsAbout' : 'jobTitle' }}">{{ trans_choice($role, $author->sex) }}</strong>
            </a>{{ !$loop->last ? ', ' : '' }}
        @endforeach
    </p>
@endif
