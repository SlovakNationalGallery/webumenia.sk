<div id="zoomed">
  <div id="viewer"></div>

  <div id="toolbarDiv" class="autohide">
    <a id="zoom-in" href="#zoom-in" title="zoom in"><i class="fa fa-plus"></i></a> 
    <a id="zoom-out" href="#zoom-out" title="zoom out"><i class="fa fa-minus"></i></a>
    <a id="home" href="#home" title="zoom to fit"><i class="fa fa-home"></i></a> 
    <a id="full-page" href="#full-page" title="zobraz fullscreen"><i class="fa fa-expand"></i></a> 
    @if ($related_items)
      <a id="previous" href="#previous" title="predchádzajúce súvisiace dielo"><i class="fa fa-arrow-up"></i></a> 
      <a id="next" href="#next" title="nasledujúce súvisiace dielo"><i class="fa fa-arrow-down"></i></a> 
    @endif
  </div>
  
  @if (!Request::has('noreturn'))
    <a class="btn btn-default btn-outline return" href="{!! $item->getUrl() !!}" role="button"><i class="fa fa-arrow-left"></i> {{ trans('general.back') }}</a>
  @endif

  @if ($related_items)
    <div class="autohide"><div class="currentpage"><span id="index">{!! array_search($item->iipimg_url, $related_items ) + 1 !!}</span> / {!! count($related_items) !!}</div></div>
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