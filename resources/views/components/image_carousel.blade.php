<div class="artwork-carousel-container {{$class_names ?? ''}}">
    <div class="artwork-carousel {{$slick_target}} {{$slick_variant ?? ''}}">
        @foreach ($img_urls as $img_url)
        <div class="img-container">
            <a href="{{$anchor_href}}" data-toggle="tooltip" data-placement="top" title="{{$anchor_title}}">
                <img
                    src="{{$img_url}}"
                    class="mw-100 lazyload position-relative"
                    alt="{!! $img_title !!} "
                >
            </a>
        </div>
        @endforeach
    </div>
</div>