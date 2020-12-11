<div class="{{$isotope_item_selector_class ?? 'item'}} {{$class_names ?? ''}}">
    
    @include('components.item_image_responsive', [
        'item' => $item,
         'limitRatio' => 3,
         'url' => $item->getUrl(), 
         ])
    
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
            @if( !isset($hide_authors) ) <em>{!! implode(', ', $item->authorsFormatted) !!}</em><br> @endif
            @if( !isset($hide_title) ) <strong>{!! $item->title !!}</strong><br> @endif
            @if( !isset($hide_dating) ) <em>{!! $item->getDatingFormated() !!}</em> @endif
        </a>
    </div>
</div>
