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
        }
    }
@endphp

<div class="artwork-carousel-container {{$class_names ?? ''}}">
  <div class="artwork-carousel {{$slick_target}} {{$slick_variant ?? ''}}">
    @foreach ($items as $item)

    @php
        list($width, $height) = getimagesize(public_path() . $item->getImagePath());
        $width = $width * ($size / max($height,1));
        $height = $size;
    @endphp

    <a
      href="{!! $item->getUrl() !!}"
      width="{{ round($width) }}"
      height="{{ $height }}">

      <img
        width="{{ round($width) }}"
        height="{{ $height }}"
        data-srcset="{!! route('dielo.nahlad', ['id' => $item->id, 'width'=> 0, 'height' => $size]) !!} 1x, {!! route('dielo.nahlad', ['id' => $item->id, 'width'=> 0, 'height' => ($size*2)]) !!} 2x"
        data-src="{!! route('dielo.nahlad', ['id' => $item->id, 'width'=> 0, 'height' => $size]) !!}"
        class="lazyload"
        style=""
        alt="{!! $item->getTitleWithAuthors() !!} ">
    </a>
    @endforeach
  </div>
</div>