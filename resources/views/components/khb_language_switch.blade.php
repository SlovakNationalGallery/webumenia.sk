<div class="row flex-grow-1">
    @foreach($localizedURLs as $localeCode => $localizedURL)
        <div class="col-6 grid-cell-link {!! $localeCode == $currentLocale ? 'active' : '' !!}">
            <a href="{{ $localizedURL }}"></a>
            <span class="text-uppercase">{{ $localeCode }}</span>
        </div>
    @endforeach
</div>