<div class="artwork-carousel-container {{$class_names or ''}}">
    <div class="artwork-carousel {{$slick_target}} {{$slick_variant or ''}}">
        @foreach ($images as $image)
        <a
          href="{{$anchor_href}}" data-toggle="tooltip" data-placement="top" title="{{$anchor_title}}" class="ratio-box" style="padding-bottom: {{ $image->ratio }}%;">

          <img
              data-sizes="auto"
              data-src="{!! $image->getPreviewUrl(600) !!}"
              data-srcset="{!! $image->getPreviewUrl(600) !!} 600w,
                      {!! $image->getPreviewUrl(220) !!} 220w,
                      {!! $image->getPreviewUrl(300) !!} 300w,
                      {!! $image->getPreviewUrl(600) !!} 600w,
                      {!! $image->getPreviewUrl(800) !!} 800w"
              class="lazyload"
              alt="{!! $img_title !!} ">
        </a>
        @endforeach
    </div>
</div>