@props(['author'])

@foreach ($author->events as $i => $event)
    <strong><a
            href="{!! route('frontend.author.index', ['place' => $event->place]) !!}">{!! $event->place !!}</a></strong>{{ $formatEvent($event) }}{{ $i + 1 < $author->events->count() ? ', ' : '' }}
@endforeach
