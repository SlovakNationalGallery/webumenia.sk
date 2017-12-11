<div class="langswitch-wrapper">
  <a class="dropdown-toggle langswitch-toggle uppercase triangle-top-left" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
    {{ $currentLocale }} <span class="caret"></span>
  </a>
  <ul class="dropdown-menu">
    @foreach($localesOrdered as $localeCode => $properties)
      @if ($localeCode != $currentLocale)
      <li>
        <a class="uppercase" rel="alternate" hreflang="{{$localeCode}}" href="{{ $localizedURLs[$localeCode] }}">
          {{ $localeCode }}
        </a>
      </li>
      @endif
    @endforeach
  </ul>
</div>