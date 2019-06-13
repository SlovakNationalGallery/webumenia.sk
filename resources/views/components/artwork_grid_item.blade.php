<div class="col-md-3 col-sm-4 col-xs-6 item">
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
        @if ($item->has_iip)
            <div class="pull-right"><a href="{{ route('item.zoom', ['id' => $item->id]) }}" data-toggle="tooltip" data-placement="left" title="{{ utrans('general.item_zoom') }}"><i class="fa fa-search-plus"></i></a></div>
        @endif
        <a href="{!! $item->getUrl() !!}"> 
            <em>{!! implode(', ', $item->authors) !!}</em><br>
            <strong>{!! $item->title !!}</strong><br>
            <em>{!! $item->getDatingFormated() !!}</em>
        </a>
    </div>
</div>