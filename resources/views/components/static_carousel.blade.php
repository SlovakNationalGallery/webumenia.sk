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

            case 'preview':
                $size = 800;
                break;
        }
    }
@endphp

<div class="artwork-carousel-container {{$class_names or ''}}">
  <div class="{{$slick_target}} {{$slick_variant or ''}}">
    @foreach ($img_urls as $img_url)

    @php
        list($width, $height) = getimagesize('https://webumenia.sk/' . $img_url);
        $width = $width * ($size / $height);
        $height = $size;
    @endphp

    <a
      href="{!! $item->getUrl() !!}"
      width="{{ round($width) }}"
      height="{{ $height }}">

      <img
        width="{{ round($width) }}"
        height="{{ $height }}"
        src="{{ $img_url }}"
        {{-- data-srcset="{!! route('dielo.nahlad', ['id' => $item->id, 'width'=> 0, 'height' => $size]) !!} 1x, {!! route('dielo.nahlad', ['id' => $item->id, 'width'=> 0, 'height' => ($size*2)]) !!} 2x" --}}
        {{-- data-src="{!! route('dielo.nahlad', ['id' => $item->id, 'width'=> 0, 'height' => $size]) !!}" --}}
        class="lazyload"
        style=""
        alt="{!! $item->getTitleWithAuthors() !!} ">
    </a>
    @endforeach
  </div>
</div>