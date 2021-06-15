<div class="artwork-carousel-container {{$class_names or ''}}">
  <div class="{{$slick_target}} {{$slick_variant or ''}}">
    @foreach ($items as $item)
    <a href="{!! $item->getUrl(isset($collection) ? ['collection' => $collection->id] : []) !!}">
      <img data-lazy="{!! $item->getImagePath() !!}" class="img-responsive-width " alt="{!! $item->getTitleWithAuthors() !!} ">
    </a>
    @endforeach
  </div>
</div>