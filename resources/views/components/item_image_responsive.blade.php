<img
    data-sizes="auto"
    data-src="{!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'600']) !!}"
    data-srcset="{!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'600']) !!} 600w,
            {!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'220']) !!} 220w,
            {!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'300']) !!} 300w,
            {!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'600']) !!} 600w,
            {!! route('dielo.nahlad', ['id' => $item->id, 'width'=>'800']) !!} 800w"
    class="lazyload"
    style="{!! isset($width) ? 'max-width:'.$width.'px;': '' !!} {!! isset($height) ? 'max-height:'.$height.'px;' : '' !!}"
    alt="{!! $item->getTitleWithAuthors() !!} "/>