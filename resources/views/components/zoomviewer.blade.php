<div 
  class="zoomviewer" 
  id="{!! $id !!}" 
  data-index="{!! $index !!}" 
  data-item-url="{!! $item->getUrl() !!}" 
  data-image-count="{!! count($images) !!}"
  data-image-iipimg-urls='{!! json_encode($images->pluck('iipimg_url')->all()) !!}'
  >
  <div id="viewer"></div>

  <div id="toolbarDiv" class="autohide">
    <a id="zoom-in" href="#zoom-in" title="zoom in"><i class="fa fa-plus"></i></a> 
    <a id="zoom-out" href="#zoom-out" title="zoom out"><i class="fa fa-minus"></i></a>
    <a id="home" href="#home" title="zoom to fit"><i class="fa fa-home"></i></a> 
    <a id="full-page" href="#full-page" title="zobraz fullscreen"><i class="fa fa-expand"></i></a> 
    @if (count($images) > 1)
      <a id="previous" href="#previous" title="predchádzajúce súvisiace dielo"><i class="fa fa-arrow-up"></i></a> 
      <a id="next" href="#next" title="nasledujúce súvisiace dielo"><i class="fa fa-arrow-down"></i></a> 
    @endif
  </div>
  
  @if (!Request::has('noreturn'))
    <a class="btn btn-default btn-outline return" href="{!! $item->getUrl() !!}" role="button"><i class="fa fa-arrow-left"></i> {{ trans('general.back') }}</a>
  @endif

  @if (count($images) > 1)
    <div class="autohide"><div class="currentpage"><span id="index">{!! $index + 1 !!}</span> / {!! count($images) !!}</div></div>
  @endif

  <div class="credit">
    @if ($item->isFree())
      <img alt="Creative Commons License" style="height: 20px; width: auto; vertical-align: bottom;" src="/images/license/zero.svg">
    {{ trans('general.public_domain') }}
    @else
      &copy; {!! $item->gallery !!}
    @endif
  </div>

</div>

{!! Html::script('js/openseadragon.js') !!}
{!! Html::script('js/components/zoomviewer.js') !!}