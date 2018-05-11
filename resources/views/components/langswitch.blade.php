<div class="langswitch-wrapper">
  <a class="dropdown-toggle langswitch-toggle uppercase triangle-top-left" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
    {{ $currentLocale }} <span class="caret"></span>
  </a>
  <ul class="dropdown-menu">
    @foreach($localizedURLs as $localeCode => $localizedURL)
      @if ($localeCode != $currentLocale)
      <li>
        <a class="uppercase" href="{{ $localizedURL }}">
          {{ $localeCode }}
        </a>
      </li>
      @endif
    @endforeach
  </ul>
</div>