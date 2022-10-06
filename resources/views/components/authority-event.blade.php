@props(['event', 'count' => 0, 'i' => 0])
<strong><a
        href="{!! route('frontend.author.index', ['place' => $event->place]) !!}">{!! $event->place !!}</a></strong>{{ $formatEvent($event) }}{{ $i + 1 < $count ? ', ' : '' }}
