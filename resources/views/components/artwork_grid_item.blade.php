<div class="{{$isotope_item_selector_class ?? 'item'}} {{$class_names ?? ''}}">

    @include('components.item_image_responsive', [
        'item' => $item,
         'limitRatio' => 3,
         'url' => $item->getUrl(),
         ])

    <div class="item-title">
        <div class="pull-right">
            <user-collections-favourite-button
                label="{{ utrans('general.item_favourite') }}"
                id="{{ $item->id }}"
            ></user-collections-favourite-button>
            @if( !isset($hide_zoom) && $item->has_iip)
                <a href="{{ route('item.zoom', ['id' => $item->id]) }}" data-toggle="tooltip" data-placement="left" title="{{ utrans('general.item_zoom') }}" class="ml-1">
                    <i class="fa fa-search-plus"></i>
                </a>
            @endif
        </div>
        <a href="{!! $item->getUrl() !!}">
            @if( !isset($hide_authors) ) <em>{!! implode(', ', $item->authors) !!}</em><br> @endif
            @if( !isset($hide_title) ) <strong>{!! $item->title !!}</strong><br> @endif
            @if( !isset($hide_dating) ) <em>{!! $item->getDatingFormated() !!}</em> @endif
        </a>
    </div>
</div>
