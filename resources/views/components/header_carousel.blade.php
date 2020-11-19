<div class="webumeniaCarousel">
    <div class="gallery-cell header-image">
        @if (method_exists($item,'hasHeaderImage') && $item->hasHeaderImage() ||  $item->header_image_src )
        <img src="{!! $item->header_image_src !!}" srcset="{!! $item->header_image_srcset !!}"
             onerror="this.onerror=null;this.srcset=''">
        @endif

        <div class="outer-box">
            <div class="inner-box"
                 style="text-shadow:0px 1px 0px {!! $item->title_shadow !!}; color: {!! $item->title_color !!}">
                 {{$slideContent}}
            </div>
        </div>
    </div>
</div>