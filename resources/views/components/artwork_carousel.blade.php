<div class="{{$class_names}}">
@foreach ($items as $item)
    <a href="{!! $item->getUrl() !!}">
      <img data-lazy="{!! $item->getImagePath() !!}" class="img-responsive-width " alt="{!! $item->getTitleWithAuthors() !!} ">
    </a>
@endforeach
</div>