@php

if (isset($id)) {
  $collection = App\Collection::where('id',  $id)->firstOrFail();
  $previewItems[$id] = $collection->items()->withTranslation()->limit(10);
}
    
@endphp

<div class="row collection">
    {{-- <div class="col-sm-2 col-xs-4">
        <a href="{!! $collection->getUrl() !!}">
            <img src="{!! $collection->getHeaderImage() !!}" class="img-responsive pentagon" alt="{{ $collection->name }}">
        </a>
    </div> --}}
    <div class="col-sm-6 col-xs-12">
        <div class="collection-title">
            <a href="{!! $collection->getUrl() !!}" class="underline">
                <strong>{!! $collection->name !!}</strong>
            </a>
        </div>
        <div class="collection-meta grey">
            {{--  {!! $collection->author !!} &nbsp;&middot;&nbsp; --}}
            {!! $collection->created_at->format('d. m. Y') !!} &nbsp;&middot;&nbsp;
            {!! $collection->user->name !!} &nbsp;&middot;&nbsp;
            {{ $collection->items_count }} {{ trans('kolekcie.collections_artworks') }}
        </div>
        <div>
            {!! $collection->getShortTextAttribute($collection->text, 350) !!}
        </div>
    </div>
    <div class="clearfix visible-xs bottom-space"></div>
    <div class="col-sm-6">
        @include('components.artwork_carousel', [
        'slick_target' => "artworks-preview",
        'slick_variant' => "small",
        'items' => $previewItems[$collection->id] ?? []
        ])
    </div>
</div>
