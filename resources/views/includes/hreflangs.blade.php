@foreach($localizedURLs as $localeCode => $URL)
<link rel="alternate" hreflang="{{$localeCode}}" href="{{ $URL }}">
@endforeach