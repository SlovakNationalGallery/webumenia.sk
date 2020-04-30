@php
    list($width, $height) = getimagesize(public_path() . $item->getImagePath());
    $width = max($width,1); // prevent division by zero exception
    $ratio = round(($height / $width) , 4);
    if (isset($limitRatio)){
        $ratio = min($limitRatio, $ratio);
    }
@endphp

<!-- limitHeight is used to set maximum container height, good for large images (e.g. artwork) -->
@if (isset($limitHeight))

  <div style="text-align:center;width: 100%;{{isset($limitHeight) ? 'max-height:'.$limitHeight : ''}}">
      <div style="margin: auto; {!! 'max-width:'.$width.'px;' !!} {!! isset($height) ? 'max-height:'.$height.'px;' : '' !!}">
          <a href="{!! isset($url) ? $url : $item->getUrl() !!}">
              <div style="width: 100%; height: 100%">
                  <img data-sizes="auto" data-src="{!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'600']) !!}"
                      data-srcset="{!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'600']) !!} 600w,
                      {!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'220']) !!} 220w,
                      {!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'300']) !!} 300w,
                      {!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'600']) !!} 600w,
                      {!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'800']) !!} 800w" class="lazyload"
                      style="object-fit:contain; width:100%; max-height:{!! $limitHeight !!}"
                      alt="{!! $item->getTitleWithAuthors() !!} " />
              </div>
          </a>
      </div>
  </div>

@else

<!-- otherwise we stick to container width and maximum aloved ratio, good for narrow containers (e.g. preview columns) -->
  <div style="text-align:center;width: 100%;">
      <div style="margin: auto; {!! 'max-width:'.$width.'px;' !!} {!! isset($height) ? 'max-height:'.$height.'px;' : '' !!}">
          <a href="{!! isset($url) ? $url : $item->getUrl() !!}">
              <div class="ratio-box" style="padding-bottom: {{ round(($ratio) * 100, 4) }}%;">
                  <img data-sizes="auto" data-src="{!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'600']) !!}"
                      data-srcset="{!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'600']) !!} 600w,
                      {!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'220']) !!} 220w,
                      {!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'300']) !!} 300w,
                      {!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'600']) !!} 600w,
                      {!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'800']) !!} 800w" class="lazyload"
                      style="object-fit:contain" alt="{!! $item->getTitleWithAuthors() !!} " />
              </div>
          </a>
      </div>
  </div>

@endif