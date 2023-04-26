@php
    $url = $url ?? $item->getUrl();
@endphp

<div class="{{$isotope_item_selector_class ?? 'item'}} {{$class_names ?? ''}}">

    @include('components.item_image_responsive', [
        'item' => $item,
         'limitRatio' => 3,
         'url' => $url,
    ])

    <div class="item-title">
        <div class="tw-float-right">
            <user-collections-favourite-button
                label-add="{{ utrans('general.item_add_to_favourites') }}"
                label-remove="{{ utrans('general.item_remove_from_favourites') }}"
                id="{{ $item->id }}"
            ></user-collections-favourite-button>
            @if( !isset($hide_zoom) && $item->has_iip)
                <a href="{{ route('item.zoom', ['id' => $item->id]) }}" data-toggle="tooltip" data-placement="left" title="{{ utrans('general.item_zoom') }}" class="ml-1">
                    <i class="fa fa-search-plus"></i>
                </a>
            @endif
        </div>
        <a href="{{ $url }}">
            @if( !isset($hide_authors) ) <em>{!! implode(', ', $item->authorsFormatted) !!}</em><br> @endif
            @if( !isset($hide_title) ) <strong>{!! $item->title !!}</strong><br> @endif
            @if( !isset($hide_dating) ) <em>{!! nl2br($item->getDatingFormated(true)) !!}</em> @endif
        </a>
    </div>
</div>
