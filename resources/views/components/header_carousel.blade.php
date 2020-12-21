<div class="webumeniaCarousel">
    <div class="gallery-cell header-image">
        @if (method_exists($item,'hasHeaderImage') && $item->hasHeaderImage() ||  $item->header_image_src )
        <img src="{!! $item->header_image_src !!}" srcset="{!! $item->header_image_srcset !!}"
             onerror="this.onerror=null;this.srcset=''">
        @endif

        <div class="outer-box">
            <div class="inner-box-shadow"
                 style="--text-shadow : {!! $item->title_shadow !!};">
                 {{$slideContent}}
            </div>
            <div class="inner-box"
                 style="color: {!! $item->title_color !!}">
                 {{$slideContent}}
            </div>
        </div>
    </div>
</div>