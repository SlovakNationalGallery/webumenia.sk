@php
    $size = 165;

    if (isSet($slick_variant)) {
        switch ($slick_variant) {
            case 'small':
                $size = 100;
                break;

            case 'large':
                $size = 200;
                break;

            case 'artwork-detail-thumbnail':
                $size = 800;
                break;
        }
    }
@endphp

<div class="artwork-carousel-container {{$class_names or ''}}">
  <div class="artwork-carousel {{$slick_target}} {{$slick_variant or ''}}">
    @foreach ($img_urls as $img_url)

    @php
        list($width, $height) = getimagesize('https://webumenia.sk/' . $img_url);
        $width = $width * ($size / $height);
        $height = $size;
    @endphp

    <a
      href="{!! $item->getUrl() !!}"
      >
      <img
        src="{{ $img_url }}"
        class="mw-100 lazyload relative"
        style=""
        alt="{!! $item->getTitleWithAuthors() !!} "
        >
    </a>
    @endforeach
  </div>
</div>