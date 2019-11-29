<div class="{{$isotope_item_selector_class or 'item'}} {{$class_names or ''}}">
    <a href="{!! $item->getUrl() !!}">
        @php
            list($width, $height) = getimagesize(public_path() . $item->getImagePath());
            $width =  max($width,1); // prevent division by zero exception
        @endphp
        <div class="ratio-box" style="padding-bottom: {{ round(($height / $width) * 100, 4) }}%;">
             @include('components.item_image_responsive', ['item' => $item])
        </div>
    </a>
    <div class="item-title">
        @if( !isset($hide_zoom) )
            @if ($item->has_iip)
                <div class="pull-right">
                    <a href="{{ route('item.zoom', ['id' => $item->id]) }}" data-toggle="tooltip" data-placement="left" title="{{ utrans('general.item_zoom') }}">
                        <i class="fa fa-search-plus"></i>
                    </a>
                </div>
            @endif
        @endif
        <a href="{!! $item->getUrl() !!}"> 
            @if( !isset($hide_authors) ) <em>{!! implode(', ', $item->authors) !!}</em><br> @endif
            @if( !isset($hide_title) ) <strong>{!! $item->title !!}</strong><br> @endif
            @if( !isset($hide_dating) ) <em>{!! $item->getDatingFormated() !!}</em> @endif
        </a>
    </div>
</div>