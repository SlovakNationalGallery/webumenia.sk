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
    <a
      href="{{ route('item.zoom', ['id' => $item->id]) }}"
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