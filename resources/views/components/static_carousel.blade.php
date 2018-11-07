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
    @foreach ($item_images as $item_image)
    <a
      href="{{ route('item.zoom', ['id' => $item->id]) }}"
      >
      <img
        data-sizes="
            (min-width: 1200px) 750,
            (min-width: 992px) calc(970px * 8/12 - 30px),
            (min-width: 768px) 720px,
            calc(100vw - 30px)
        "
        data-srcset="{!! $item_image->getFullIIIFImgURL('full', '750,', 0, 'default') !!} 750w
            {!! $item_image->getFullIIIFImgURL('full', '400,', 0, 'default') !!} 400w,
            {!! $item_image->getFullIIIFImgURL('full', '617,', 0, 'default') !!} 617w,
            {!! $item_image->getFullIIIFImgURL('full', '750,', 0, 'default') !!} 750w,
            {!! $item_image->getFullIIIFImgURL('full', '1500,', 0, 'default') !!} 1500w
        "
        alt="{!! $item->getTitleWithAuthors() !!} "
        class="img-responsive lazyload"
        >
    </a>
    @endforeach
  </div>
</div>