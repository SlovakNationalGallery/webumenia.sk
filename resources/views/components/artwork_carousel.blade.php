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

<div class="artwork-carousel-container {{$class_names or ''}}">
  <div class="{{$slick_target}} {{$slick_variant or ''}}">
    @foreach ($items as $item)
    <a href="{!! $item->getUrl() !!}">
      <img
        data-srcset="{!! route('dielo.nahlad', ['id' => $item->id, 'width'=> 0, 'height' => $size]) !!} 1x, {!! route('dielo.nahlad', ['id' => $item->id, 'width'=> 0, 'height' => ($size*2)]) !!} 2x"
        class="img-responsive-width lazyload"
        alt="{!! $item->getTitleWithAuthors() !!} ">
    </a>
    @endforeach
  </div>
</div>