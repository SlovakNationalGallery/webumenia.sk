@section('daily_art')
    @if($author)
        <div class="container hidden-xs " style="height:23vh; text-align:center">
            <a href="{!! $author -> getUrl() !!}">
                @php
                    $title = $item->title . '&#013;'
                             . utrans('daily_art.author') . ' '
                             . $author->getFormatedNameAttribute() . ' ';
                    
                    if($author->death_year){
                        $title .=  trans('daily_art.author_anniversary_start') . ' '
                                .  $author -> age . ' '
                                .  trans('daily_art.author_anniversary_end');
                    }
                    else {
                        $title .=  trans('daily_art.author_celebrating_start') . ' '
                                .  $author -> age . ' '
                                .  trans('daily_art.author_celebrating_end');
                    }
                @endphp
                <img src="{!! $item->getImagePath() !!}" 
                    style="height:100%;padding-top:20px" 
                    title="{!! $title !!}"/>
            </a>
        </div>
    @elseif ($item)
        <div class="container hidden-xs " style="height:23vh; text-align:center">
            <a href="{!! $item -> getUrl() !!}">
                @php
                    $title = $item->title . '&#013;'
                            . implode(', ', $item ->getAuthorFormated(''));
                @endphp
                <img src="{!! $item -> getImagePath() !!}" 
                    style="height:100%;padding-top:20px" 
                    title="{!! $title !!}"/>
            </a>
        </div> 
    @endif
@stop()
