<div class="langswitch-wrapper">
  <a class="dropdown-toggle langswitch-toggle uppercase triangle-top-left" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
    {{ config("laravellocalization.supportedLocales.$currentLocale.name") }} <span class="caret"></span>
  </a>
  <ul class="dropdown-menu">
    @foreach($localizedURLs as $localeCode => $localizedURL)
      @if ($localeCode != $currentLocale)
      <li>
        <a class="uppercase" href="{{ $localizedURL }}">
          {{ config("laravellocalization.supportedLocales.$localeCode.name") }}
        </a>
      </li>
      @endif
    @endforeach
  </ul>
</div>